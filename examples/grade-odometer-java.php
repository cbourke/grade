<?php


$testModule = new TestModule("Program: Cost Per Mile (Java Version)", null, $isGrader);

$fileName = "Odometer.java";
$className = basename($fileName, ".java");
$testModule->addRequiredFile($fileName);
$testModule->addSourceFile($fileName, file_get_contents($fileName));

$testModule->addPreTestCommand("$javac $fileName");

$testModule->addPostTestCommand("rm *.class");

$arr = array( 
"50125 50430 10 3.25" => "
TODO
"
);

$i = 1;
foreach($arr as $k => $v) {
  $testCase = new TestCase("Test Case $i", $v);
  $testCase->addTestCaseCommand("echo '$k' | $java $className");
  $testModule->addTestCase($testCase);
  $i++;
}

$testSuite->addTestModule($testModule);

?>