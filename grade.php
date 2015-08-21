<?php

include_once("GradeInc.php");

$hwNum  = $_POST["hw_num"];
$login  = $_POST["cse_login"];
$passwd = $_POST["cse_password"];
$ip = $_SERVER['REMOTE_ADDR'];
$host = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : "";

$auth = new CSEAuthenticator($config['authorization_service_token']);

   if($auth->authenticate($login, $passwd)) {
     gradeLog("$ip $host $login $hwNum");
     $webhandin_home = $config["webhandin_relative_path"];
     $gradeDir = "$webhandin_home/$hwNum/";
     $gradeScript = $config["script_name"];
     $gradeScriptPath = "$gradeDir$gradeScript";
     $userDir = "$webhandin_home/$hwNum/$login";
     $result = "";
     if(file_exists($gradeScriptPath)) {
       if(file_exists($userDir)) {
         $chdirSuccess = chdir($gradeDir);
         if($chdirSuccess) {
           $cmd = "./$gradeScript " . escapeshellarg($login) . " 2>&1";
           $result = system($cmd, $exitCode);
	   if($exitCode !== 0) {
	     $result .= "WARNING: process exited with code " . $exitCode . " (".$exitCodes[$exitCode] . ")";
           }
         } else {
           $result = "ERROR: internal error.";
         }
       } else {
         $result = "ERROR: well, you gotta hand something in first, noob.";
       }
     } else {
       $result = "ERROR: Grade script does not appear to have been setup, your instructor either screwed up or didn't care enough to do so.  Oh well.";
     }

  $outputType = $config["script_output"];
  if($outputType == 1) {
    print "<pre>$result</pre>";
  } else {
    print "$result";
  }
} else {
  gradeLog("UNAUTHORIZED ATTEMPT: $ip $host $login $hwNum");
  print "<h1>Unauthorized Access Attempt</h1>";
  print "<p>Your username/password was incorrect.  This unauthorized attempt has been logged. Big Brother is watching.</p>";
}
?>

