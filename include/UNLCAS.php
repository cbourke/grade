<?php

session_start();

$casService = 'https://cse-apps.unl.edu/cas';
$thisService = 'https://cse.unl.edu' . $_SERVER['PHP_SELF'];
$data_file_name = "persisted_users.json";
$seconds_until_ticket_timeout = 900;

if(!file_exists($data_file_name)) {
  file_put_contents($data_file_name, "{}", LOCK_EX);
}

/**
 * Validates this service with the given <code>$casTicket</code>.
 * Upon success, returns the user name provided by CAS (cse login).
 * Upon failure, returns <code>false</code>
 */
function getCasUserName($casTicket) {
    global $casService, $thisService;
    $casTicket = trim($casTicket);
    $casGet = "$casService/serviceValidate?ticket=$casTicket&service=$thisService";

    $response = file_get_contents($casGet);

    if (preg_match('/cas:authenticationSuccess/', $response)) {
        $xml = simplexml_load_string($response);
        $user = $xml->children('http://www.yale.edu/tp/cas')->authenticationSuccess->user[0];
        return trim($user);
        
    } else {
        gradeLog("CAS ERROR: response = $response");
        return false;
    }
}

/**
 * Attempts to load and return the user name (cse login) persisted in
 * the local session/user file
 */
function loadPersistedUser($casTicket) {
    global $data_file_name;
    $sessionId = session_id();
    $handleLock = fopen($data_file_name, "r");
    if(!$handleLock) {
      return false;      
    }
    flock($handleLock, LOCK_SH);
    $file_contents = file_get_contents($data_file_name);
    fclose($handleLock);

    $data = json_decode($file_contents);
    if(isset($data->{$sessionId})) {
      if ($data->{$sessionId}->{'casTicket'} !== $casTicket) {
        gradeLog("Something fishy: Session ID $sessionId is attempting to use ticket $casTicket", session_id(), $ip);
        return false;
      } else if($data->{$sessionId}->{'timeout'} >= time()) {
        return $data->{$sessionId}->{'username'};
      } else {
        return "TIMED_OUT_USER";
      }
    } else {
      return false;
    }
}

/**
 * Given a map of <code>$data</code> mapping session IDs to
 * ticket/username/expire time data, iterates through and removes
 * any expired entries.
 */
function removeExpiredSessions(&$data) {
  $now = time();
  foreach($data as $sessionId => $entry) {
    if($entry->{'timeout'} <= $now) {
      unset($data->{$sessionId});
    }
  }
  return;
}

/**
 * Removes the session data from the local session json
 * file for this session.
 */
function removeSession() {
  global $data_file_name;
  $sessionId = session_id();
  $handleLock = fopen($data_file_name, "r+");
  flock($handleLock, LOCK_SH);
  $file_contents = file_get_contents($data_file_name);
  $data = json_decode($file_contents);
  unset($data->{$sessionId});
  ftruncate($handleLock, 0);
  rewind($handleLock);
  fwrite($handleLock, json_encode($data));
  fclose($handleLock);
}

/**
 * Persists session data to the local session json file.
 * Also removes all expired sessions before saving the 
 * json file.
 */
function persistUser($user, $ticket) {
    global $data_file_name, $seconds_until_ticket_timeout;
    $sessionId = session_id();
    $handleLock = fopen($data_file_name, "r+");
    flock($handleLock, LOCK_SH);
    $file_contents = file_get_contents($data_file_name);
    if(!$file_contents) {
      $data = new stdClass(); 
    } else {
      $data = json_decode($file_contents);
    }
    $data->{$sessionId} = (object)[
        "casTicket" => $ticket,
        "username" => strval($user),
        "timeout" => time() + $seconds_until_ticket_timeout,
    ];
    removeExpiredSessions($data);
    ftruncate($handleLock, 0);
    rewind($handleLock);
    fwrite($handleLock, json_encode($data));
    fclose($handleLock);
}

function getUsername() {
    $ticket = null;
    if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["ticket"]) {
        $ticket = $_GET["ticket"];
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["ticket"]) {
        $ticket = $_POST["ticket"];
    }
    if ($ticket) {
        $user = "";
        if ($user = loadPersistedUser($ticket)) {
          return $user;
        } else if ($user = getCasUserName($ticket)) {
          gradeLog("USER LOGGED IN: $user", session_id(), $ip);
          persistUser($user, $ticket);
        } else {
          login();
        }
        return $user;
    } else {
        login();
    }
    return null;
}

function login() {
    global $casService, $thisService;
    session_destroy();
    header("Location: $casService/login?service=$thisService");
}

function logout() {
    global $casService, $thisService;
    $thisService = str_replace('logout.php', '', $thisService);
    //delete the session cookie
    gradeLog("SESSION ENDING");
    removeSession();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: $casService/logout?service=$thisService");
}

