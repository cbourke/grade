<?php
ini_set('session.save_path', dirname(__FILE__)."/sessions");
$includeDir = "./include/";
$scriptFiles = array("Config.php", "Roster.php", "Assignment.php", "Course.php", "Egg.php", "ExitCodes.php", "UNLCAS.php");

foreach($scriptFiles as $file) {
  include_once("$includeDir$file");
}

?>
