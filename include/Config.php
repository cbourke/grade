<?php

// change this to provide a link to your course home page in your navigation bar
$config['homepage'] = "";

$config['version'] = "2.8.0";
$config['version_date'] = "2020/07/19";
$config['log_file_name'] = "grade.log";

//path to the webhandin directory relative to the grade app
$config['webhandin_relative_path'] = "../../handin/";

//homework file
$config['homework_file'] = "../../homework";

//mail file
$config['mail_file'] = "../../mail.list";

//(g)ta mail file
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
