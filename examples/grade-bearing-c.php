<?php


$testModule = new TestModule("Program: Directional Bearing (C version)", null, $isGrader);

$fileName = "bearing.c";
$testModule->addRequiredFile($fileName);
$testModule->addSourceFile($fileName, file_get_contents($fileName));

$testModule->addPreTestCommand("/usr/bin/gcc $fileName -lm -rdynamic");

$testModule->addPostTestCommand("rm a.out");

$arr = array( 
"40.8206 -96.7056 41.9483 -87.6556" => "
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