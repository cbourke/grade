<?php

include_once("Grade.php");
$roster = Roster::createRoster($config['mail_file']);

$hwNum         = $_POST["hw_num"];
$login         = $_POST["cse_login"];
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