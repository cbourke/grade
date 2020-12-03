<?php

// change this to provide a link to your course home page in your navigation bar
$config['homepage'] = "";

$config['version'] = "1.0.0";
$config['version_date'] = "2020/12/02";

//log file
$config['log_file_name'] = "grade.log";

//path to the webhandin directory relative to the grade app
$config['webhandin_relative_path'] = "../../handin/";

//homework file
$config['homework_file'] = "../../homework";

//mail file
$config['mail_file'] = "../../mail.list";

//(g)ta mail file (Optional to setup. Permits TA's, GTA's, and instructors to grade without logging in as the course)
$config['ta_mail_file'] = "../../gta-mail.list";

//name of the grading script that the grade app will use
$config['script_name'] = "grade.php";

//name of the grading scrip that the grader interface will use
$config['script_name_grader'] = "grade.team.php";

//global script timeout duration (seconds)
$config['global_timeout'] = 150;

/**
 * Appends the given message to the log file (as defined in
 * config.php).  Prepends a current date/time stamp and appends an
 * endline character to the message.
 */
function gradeLog($msg, $sessionId = null, $ip = null) {
  //future proof/config proof: don't rely on the system timezone
  //being set; use our own, Central Time
  $date = new DateTime("now", new DateTimeZone("America/Chicago"));
  $dateStamp = $date->format(DateTime::ISO8601);
  if(!$sessionId) {
    $sessionId = session_id();
  }
  if(!$ip) {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  global $config;
  file_put_contents($config["log_file_name"], sprintf("%s (session=%s, ip=%s) %s\n", $dateStamp, $sessionId, $ip, $msg), FILE_APPEND);
}

/**
 * Utility function that formats a bootstrap alert box with
 * the given title/message.  Optional parameter can be used
 * for different colors/types: success, info, warning, danger
 */
function getBootstrapDiv($title, $message, $type = "danger") {

  $html = '<div class="alert alert-'.$type.' alert-dismissible" role="alert">' .
          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' .
          '<span aria-hidden="true">&times;</span></button>' .
          '<strong>'.$title.'</strong> '.$message.'</div>';

  return $html;
}

?>
