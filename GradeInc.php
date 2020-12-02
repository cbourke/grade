<?php

$includeDir = "./include/";
$scriptFiles = array("Config.php", "Roster.php", "Assignment.php", "Course.php", "Egg.php", "ExitCodes.php", "UNLCAS.php");

foreach($scriptFiles as $file) {
  include_once("$includeDir$file");
}

?>
