#!/usr/bin/php

<?php

include_once('grade.inc.php');

if($argc != 2) {
  print "Usage: php grade.php cseLogin\n";
  exit(1);
}

$isGrader = true;
$isHonors = true;

$javac = "javac -J-Xmx2048m";
$java = "java -Xmx2048m";

chdir($argv[1]);

$testSuite = new TestSuite("CSCE 155H/RAIK 183H - Fall 2015 - Assignment 1 Grader Suite");

//Question 1...
include_once('grade-investment-c.php');
include_once('grade-odometer-java.php');
include_once('grade-bearing-c.php');
include_once('grade-timedilation-java.php');
include_once('grade-halflife-c.php');
include_once('grade-cellphone-java.php');



print $testSuite->getLogs();
print $testSuite->run(); 

?>
