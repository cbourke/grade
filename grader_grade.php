<?php

include_once("GradeInc.php");
$course = Course::createCourse($config['homework_file']);

$hwNum         = $_POST["hw_num"];
$student_login = $_POST["student_cse_login"];
$student       = $course->getStudent($student_login);
$ip            = $_SERVER['REMOTE_ADDR'];
$host          = $_SERVER['REMOTE_HOST'];
$timeout       = $config['global_timeout'];

$username_for_ticket = getUsername();

if ($username_for_ticket === "TIMED_OUT_USER") {
  gradeLog("UNAUTHORIZED ATTEMPT - EXPIRED TICKET: $username_for_ticket $hwNum");
  $result = getBootstrapDiv("Expired Session", "<a onclick=\"window.location.href = window.location.href.split('?')[0].replace(/\/$/, '')\">Click here to reset</a>");
} else if( !$course->isGrader($username_for_ticket) ) {
  gradeLog("UNAUTHORIZED ATTEMPT - USER IS NOT A GRADER: $username_for_ticket $hwNum");
  printf("<span style='color: red; font-weight: bold'>ERROR: </span> You do not appear to be a grader in this course.  Please contact the instructor.");
} else if( $student === null ) {
  printf("<span style='color: red; font-weight: bold'>ERROR: </span> Student <code>$student_login</code> does not appear to be enrolled in this course.");
} else {
  gradeLog("GRADER ($username_for_ticket) ATTEMPT: $student_login $hwNum");

  printf("<h2>Results for " . $student->getName() . " (" . $student->getLogin() . ")</h2>");
  printf("<h3>Homework $hwNum</h3>");
  $logDiv = getGraderLogDiv($username_for_ticket, $hwNum);
  printf($logDiv);
  printf("<div id='grade_content'>");

  $webhandin_home = $config["handin_path"];
  $gradeDir = "$webhandin_home$hwNum/";
  $gradeScript = $config["script_name_grader"];
  $gradeScriptPath = "$gradeDir$gradeScript";
  $userDir = "$webhandin_home/$hwNum/$student_login";
  $result = "";

  if( !file_exists($gradeScriptPath) ) {
    $result = "<p><span style='color: red; font-weight:bold'>ERROR:</span> grade script ($gradeScriptPath) not setup</p>";
  } else if( !file_exists($userDir) ) {
    $result = "<p><span style='color: red; font-weight:bold'>ERROR:</span> nothing handed in for ".$userDir."</p>";
  } else {
    $chdirSuccess = chdir($gradeDir);
    if($chdirSuccess) {
      $cmd = "timeout $timeout ./$gradeScript " . escapeshellarg($student_login) . " 2>&1";
      $cmd_result = system($cmd, $exitCode);
      if($exitCode === 124) {
        //by default, `timeout` results in an exit code of 124, so
        //we give them a different message:
        $result = "Error: Program(s) timed out.  You may have an infinite loop or extremely inefficient program.";
      } else {
        $result = $result . $cmd_result;
      }
    } else {
      $result = "<p>Invalid directory</p>";
    }
  }
  print $result;
  printf("</div>");

}

?>
