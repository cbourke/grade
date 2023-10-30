<?php
ini_set('session.save_path', dirname(__FILE__)."/sessions");
$includeDir = "./include/";
$scriptFiles = array("Config.php", "Course.php", "Egg.php", "UNLCAS.php");

foreach($scriptFiles as $file) {
  include_once("$includeDir$file");
}

?>
