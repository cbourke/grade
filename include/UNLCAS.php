<?php

$casService = 'https://cse-apps.unl.edu/cas';
$thisService = 'https://cse.unl.edu' . $_SERVER['PHP_SELF'];
$data_file_name = "persisted_users.json";
$seconds_until_ticket_timeout = 900;
session_start();

function response_for_ticket($ticket) {
    global $casService, $thisService;
    $ticket = trim($ticket);
    $casGet = "$casService/serviceValidate?ticket=$ticket&service=$thisService";

    $response = file_get_contents($casGet);

    if (preg_match('/cas:authenticationSuccess/', $response)) {
        return $response;
    } else {
        error_log($response);
        return false;
    }
}

function get_username_from_persisted_data($ticket) {
    global $data_file_name;
    if ($file_contents = file_get_contents($data_file_name)) {
        $data = json_decode($file_contents);
        if (isset($data->{$ticket})) {
            if ($data->{$ticket}->{'timeout'} >= time()) {
                return $data->{$ticket}->{'username'};
            } else {
                return "TIMED_OUT_USER";
            }
        }
    } else {
        file_put_contents($data_file_name, "{}");
    }
    return false;
}

function prune_data($data) {
    foreach ($data as $ticket => $entry) {
        if ($entry->{'timeout'} <= time()) {
            unset($data->{$ticket});
        }
    }
    return $data;
}

function persist_user($user, $ticket) {
    global $data_file_name, $seconds_until_ticket_timeout;
    $data = new stdClass();
    if ($file_contents = file_get_contents($data_file_name)) {
        $data = json_decode($file_contents);
    }
    $data->{$ticket} = (object)[
        "username" => strval($user),
        "timeout" => time() + $seconds_until_ticket_timeout,
    ];
    file_put_contents($data_file_name, json_encode(prune_data($data)));
}

function get_username() {
    $ticket = null;
    if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["ticket"]) {
        $ticket = $_GET["ticket"];
    } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["ticket"]) {
        $ticket = $_POST["ticket"];
    }
    if ($ticket) {
        $user = "";
        if ($stored_ticket = get_username_from_persisted_data($ticket)) {
            return $stored_ticket;
        } else if ($response = response_for_ticket($ticket)) {
            $xml = simplexml_load_string($response);
            $user = $xml->children('http://www.yale.edu/tp/cas')->authenticationSuccess->user[0];
            $user = trim($user);
            persist_user($user, $ticket);
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

    if (isset($_SESSION['cas_user'])) {
        unset($_SESSION['cas_user']);
        unset($_SESSION['timeout']);
        session_destroy();
    }
    header("Location: $casService/logout?service=$thisService");
}

