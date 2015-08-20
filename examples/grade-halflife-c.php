<?php

$testModule = new TestModule("Program: Half-Life (C version)", null, $isGrader);

$fileName = "halflife.c";
$testModule->addRequiredFile($fileName);
$testModule->addSourceFile($fileName, file_get_contents($fileName));

$testModule->addPreTestCommand("/usr/bin/gcc $fileName -lm -rdynamic");

$testModule->addPostTestCommand("rm a.out");

$arr = array( 
"10 28.79 2" => "
TODO
"
);

$i = 1;
foreach($arr as $k => $v) {
  $testCase = new TestCase("Test Case $i", $v);
  $testCase->addTestCaseCommand("echo '$k' | ./a.out");
  $testModule->addTestCase($testCase);
  $i++;
}

$testSuite->addTestModule($testModule);

?>