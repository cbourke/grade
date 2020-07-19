<?php

$casService = 'https://cse-apps.unl.edu/cas';
$thisService = 'https://cse.unl.edu' . $_SERVER['PHP_SELF'];
session_start();

function responseForTicket($ticket) {
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

function get_username() {
    global $casService, $thisService;
    if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET["ticket"]) {
        if ($response = responseForTicket($_GET["ticket"])) {

            $xml = simplexml_load_string($response);
            $user = $xml->children('http://www.yale.edu/tp/cas')->authenticationSuccess->user[0];

            $_SESSION['cas_user'] = $user;
            $_SESSION['timeout'] = time() + 900;
            return $user;
        } else {
            session_destroy();
            header("Location: $casService/login?service=$thisService");
        }
    } else {
        if (isset($_SESSION['cas_user']) && isset($_SESSION['timeout']) && $_SESSION['timeout'] > time()) {
            return $_SESSION['cas_user'];
        } else {
            session_destroy();
            header("Location: $casService/login?service=$thisService");
        }
    }
    return null;
}

function logout() {
    global $casService,$thisService;
    $thisService = str_replace('logout.php', '', $thisService);

    if (isset($_SESSION['cas_user'])) {
        unset($_SESSION['cas_user']);
        unset($_SESSION['timeout']);
        session_destroy();
    }
    header("Location: $casService/logout?service=$thisService");
}
