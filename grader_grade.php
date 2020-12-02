<?php

include_once("GradeInc.php");
$roster = Roster::createRoster($config['mail_file']);
$ta_roster = Roster::createRoster($config['ta_mail_file']);
$course = Course::createCourse($config['homework_file']);
$courseLogin = $course->getCourseNumber();

$hwNum         = $_POST["hw_num"];
$login         = $courseLogin;
$student_login = $_POST["student_cse_login"];
$grader_login  = $_POST['grader_login'];
$grader        = $ta_roster == null ? null : findStudentByLogin($ta_roster->getStudents(), $grader_login);
$ip            = $_SERVER['REMOTE_ADDR'];
$host          = $_SERVER['REMOTE_HOST'];
$timeout       = $config['global_timeout'];


$username_for_ticket = get_username();
gradeLog($username_for_ticket);
if ($username_for_ticket === "TIMED_OUT_USER") {
  gradeLog("UNAUTHORIZED ATTEMPT - EXPIRED TICKET: $ip $host $grader_login $hwNum");
  $result = getBootstrapDiv("Expired Session", "<a onclick=\"window.location.href = window.location.href.split('?')[0].replace(/\/$/, '')\">Click here to reset</a>");
} else if($grader_login === $username_for_ticket && ($grader_login === $courseLogin || $grader != null)) {
  gradeLog("GRADER ATTEMPT: $ip $host $student_login $hwNum");

  $students = $roster->getStudents();
  $student = findStudentByLogin($students, $student_login);

  if($student != null) {

    printf("<h2>Results for " . $student->getName() . " (" . $student->getLogin() . ")</h2>");
    printf("<h3>Homework $hwNum</h3>");
    printf("<div id='grade_content'>");

    $webhandin_home = $config["webhandin_relative_path"];
    $gradeDir = "$webhandin_home$hwNum/";
    $gradeScript = $config["script_name_grader"];
    $gradeScriptPath = "$gradeDir$gradeScript";
    $userDir = "$webhandin_home/$hwNum/$student_login";
    $result = "";

    if(file_exists($gradeScriptPath)) {
      if(file_exists($userDir)) {
        $chdirSuccess = chdir($gradeDir);
        if($chdirSuccess) {
          $cmd = "timeout $timeout ./$gradeScript " . escapeshellarg($student_login) . " 2>&1";
          $cmd_result = system($cmd, $exitCode);
          if($exitCode === 124) {
            //by default, timeout results in an exit code of 124, so 
            //we give them a different message:
            $result = "Error: Program(s) timed out.  You may have an infinite loop or extremely inefficient program.";
          } else {
            $result = $result . $cmd_result;
          }
        } else {
          $result = "<p>Invalid directory</p>";
        }
      } else {
        $result = "<p><span style='color: red; font-weight:bold'>ERROR:</span> nothing handed in.".$userDir."</p>";
      }
    } else {
       $result = "<p><span style='color: red; font-weight:bold'>ERROR:</span> grade script ($gradeScriptPath) not setup</p>";
    }
    print $result;
    printf("</div>");
  } else {
    printf("<span style='color: red; font-weight: bold'>ERROR: </span> Unable to find student, $student_login");
  }
} else {
  $result = "<span style='color: red; font-weight: bold'>ERROR: </span> Unauthorized attempt";
  gradeLog("UNAUTHORIZED GRADER ATTEMPT: $ip $host $grader_login - $student_login $hwNum");
}

?>

