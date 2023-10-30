<?php

/*
 * Course/semester-specific configuration - required
 */

/**
 * Set to the course_semester-code of the handin course
 * This must match directory name under the handin directory
 * Example: CSCE251_1238 (CSCE 251, Fall 2023)
 * (required)
 */
$config['course_dir'] = "REQUIRED";

/**
 * Set this to the course name as you want it to appear
 * in the navigation bar; in addition, you can set the
 * homepage below.
 */
$config['course_name'] = "REQUIRED";

/**
 * Set the `USER` portion of this path to the course account
 * user name (ex: `c-cbourke3`)
 */
$config['base_dir'] = "/home/USER/handin/";

/*
 * Course/semester-specific configuration - optional
 */

/**
 * Defines a link to your course home page in the navigation bar.
 */
$config['homepage'] = "";

/**
 * Name of the grading script that the grade app will use.
 * Change this if you use scripts in a different language;
 * `grade.py` for example.
 */
$config['script_name'] = "grade.php";

/**
 * Name of the grading script that the grader interface will use.
 */
$config['script_name_grader'] = "grade.team.php";

/**
 * Global script timeout duration (in seconds).  Kills the
 * grading script (and all child processes) after timeout.
 *
 * Default: 150 seconds = 2.5 minutes
 */
$config['global_timeout'] = 150;


/*
 * General configuration settings; likely you will not need to (or should not)
 * change these.
 */
$config['version'] = "4.0.beta";
$config['version_date'] = "2023/11/xx";

//log file
$config['log_file_name'] = "grade.log";

//absolute path to the handin directory
// ex: `/home/c-cbourke3/handin/`
$config['handin_path'] = $config['base_dir'].$config['course_dir']."/";

//homework file
$config['homework_file'] = $config['handin_path']."homework";

//mail file
$config['mail_file'] = $config['handin_path']."mail.list";

//(g)ta mail file (permits only TA's, GTA's, instructors to grade
$config['ta_mail_file'] = $config['handin_path']."gta-mail.list";


/**
 * Appends the given message to the log file.  Prepends a current
 * date/time stamp and appends an endline character to the message.
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
