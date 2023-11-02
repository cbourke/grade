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


/**
 * General configuration settings; likely you will not need to (or should not)
 * change these.
 */
$config['version'] = "4.0.2";
$config['version_date'] = "2023-11-02";

/**
 * Grader log file (same directory as the grade app)
 */
$config['log_file_name'] = "grade.log";

/**
 * Absolute path to the handin directory; generally:
 */
$config['handin_path'] = $config['base_dir'].$config['course_dir']."/";

/**
 * Absolute path/file of the homework file generated by the
 * handin system (should be updated by syncing through the handing
 * app).
 */
$config['homework_file'] = $config['handin_path']."homework";

/**
 * Absolute path/file of the mail.list file generated by the
 * handin system (should be updated by syncing through the handing
 * app).
 */
$config['mail_file'] = $config['handin_path']."mail.list";

/**
 * Absolute path/file of the gta-mail.list file.  This file is *not*
 * generated by the handin system and will need to be updated manually.
 * Copy over any line(s) from the mail.list file of anyone who should
 * have grader access for this course.
 */
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

/**
 * Creates and returns a collapsible HTML div containing log entries for
 * the specified login (required) and assignment (optional).
 */
function getGraderLogDiv($login, $assignNum = "") {

  global $config;
  $grepCmd = "grep ' " . $login . " " . $assignNum . "' " . $config["log_file_name"] . " | egrep -v 'GRADER|UNAUTHORIZED'";
  $grepResult = shell_exec($grepCmd);
  if(strlen($grepResult) == 0) {
    $grepResult = "<p style='color:#F00'>No WebGrader runs recorded</p>";
  }
  $jsCommand = "\$(\"#collapseIdWGR\").toggle(\"blind\"); $(this).text() == \"[-]\"?$(this).text(\"[+]\"):$(this).text(\"[-]\");";
  $grepHtmlDiv = "<div style='clear: both'>" .
                 "<h2><span style='cursor: pointer;' onclick='$jsCommand'>[-]</span> WebGrader Runs</h2>".
                 "</div>\n" .
                 "<div id='collapseIdWGR' style='margin-left: 2em;'><pre>" . $grepResult . "</pre></div>";
  return  $grepHtmlDiv;
}



?>
