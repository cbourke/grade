#!/usr/bin/php

<?php

include_once('grade.inc.php');

if($argc != 2) {
  print "Usage: php grade.php loginId\n";
  exit(1);
}

$isGrader = true;
$isHonors = false;

// Set "global" values for compilers, flag, etc.
$javac = "javac -J-Xmx2048m";
$java = "java -Xmx2048m";

$gcc = "/usr/bin/gcc";
$gccFlags = "-lm -rdynamic";

chdir($argv[1]);

//name, expected output, isGrader flag
$testSuite = new TestSuite("CSCE Hello World Test Suite", null, $isGrader);

include_once('grade-hello-c.php');

print $testSuite->run();

?>
