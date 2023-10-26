<?php


$testModule = new TestModule("Program: Hello World (C version)", null, $isGrader);

$fileName = "hello.c";
$testModule->addRequiredFile($fileName);
$testModule->addSourceFile($fileName, file_get_contents($fileName));

$testModule->addPreTestCommand("$gcc $fileName $gccFlags");

$testModule->addPostTestCommand("rm a.out");

// input/output pairs:
$testCases = array(
"" => "Hello World"
);

$i = 1;
foreach($arr as $k => $v) {
  //name, expected output
  $testCase = new TestCase("Test Case $i", $v);
  //echo/pipe input
  //$testCase->addTestCaseCommand("echo '$k' | ./a.out");
  //or use CLAs:
  $testCase->addTestCaseCommand("./a.out $k");
  $testModule->addTestCase($testCase);
  $i++;
}

$testSuite->addTestModule($testModule);

?>
