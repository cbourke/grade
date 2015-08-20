<?php


$testModule = new TestModule("Program: Return on Investment (C version)", null, $isGrader);

$fileName = "investment.c";
$testModule->addRequiredFile($fileName);
$testModule->addSourceFile($fileName, file_get_contents($fileName));

$testModule->addPreTestCommand("/usr/bin/gcc $fileName -lm -rdynamic");

$testModule->addPostTestCommand("rm a.out");

$arr = array( 
"100000 120000" => "
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