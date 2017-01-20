<?php

$config['version'] = "2.5.0";
$config['version_date'] = "2017/01/20";
$config['log_file_name'] = "grade.log";

//The token is stored in the following system file:
include_once('/srv/www/php/include/WebGrader.php');
//check that it was included properly:
if( isset($webGraderAuthToken) ) {
  $config['authorization_service_token'] = $webGraderAuthToken;
} else {
  printf("ERROR: unable to load token\n");
}

//path to the webhandin directory relative to the grade app
$config['webhandin_relative_path'] = "../../handin/";

//homework file
$config['homework_file'] = "../../homework";

//mail file
$config['mail_file'] = "../../mail.list";

//name of the grading script that the grade app will use
$config['script_name'] = "grade.php";

//name of the grading scrip that the grader interface will use
$config['script_name_grader'] = "grade.team.php";

/**
 * Appends the given message to the log file (as defined in
 * config.php).  Prepends a current date/time stamp and appends an
 * endline character to the message.
 */
function gradeLog($msg) {
  //future proof/config proof: don't rely on the system timezone
  //being set; use our own, Central Time
  $date = new DateTime("now", new DateTimeZone("America/Chicago"));
  $dateStamp = $date->format(DateTime::ISO8601);
  global $config;
  file_put_contents($config["log_file_name"], "$dateStamp $msg\n", FILE_APPEND);
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
