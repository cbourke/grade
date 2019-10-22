<?php

include_once("GradeInc.php");
$roster = Roster::createRoster($config['mail_file']);
$course = Course::createCourse($config['homework_file']);
$courseLogin = $course->getCourseNumber();

$hwNum         = $_POST["hw_num"];
$login         = $courseLogin;
$student_login = $_POST["student_cse_login"];
$passwd        = $_POST["cse_password"];
$ip            = $_SERVER['REMOTE_ADDR'];
$host          = $_SERVER['REMOTE_HOST'];

$auth = new CSEAuthenticator($config['authorization_service_token']);

if($auth->authenticate($login, $passwd)) {

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
    
    // print the logs of WebGrader usage for the given student for this assignment
    $grepCmd = "grep '" . $student->getLogin() . " " . $hwNum . "' ./" . $config["log_file_name"] . " | grep -v 'GRADER'";
    $grepResult = shell_exec($grepCmd);
    if (strlen($grepResult) == 0) $grepResult = "<p style='color:#F00'>No WebGrader runs recorded</p.";
    $jsCommand = "\$(\"#collapseIdWGR\").toggle(\"blind\"); $(this).text() == \"[-]\"?$(this).text(\"[+]\"):$(this).text(\"[-]\");";
    $grepHtmlDiv = "<div style='clear: both'><h2><span style='cursor: pointer;' onclick='$jsCommand'>[-]</span> WebGrader Runs</h2></div>\n" .
               "<div id='collapseIdWGR' style='margin-left: 2em;'><pre>" . $grepResult . "</pre></div>";
    $result .= $grepHtmlDiv;

    if(file_exists($gradeScriptPath)) {
      if(file_exists($userDir)) {
        $chdirSuccess = chdir($gradeDir);
        if($chdirSuccess) {
          $cmd = "./$gradeScript " . escapeshellarg($student_login) . " 2>&1";
          $cmd_result = system($cmd);
          $result = $result . $cmd_result;
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
  printf("<span style='color: red; font-weight: bold'>ERROR: </span> Unauthorized attempt");
  gradeLog("UNAUTHORIZED GRADER ATTEMPT: $ip $host $student_login $hwNum");
}

?>
