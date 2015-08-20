<?php


$testModule = new TestModule("Program: Cell Phone Usage (Java Version)", null, $isGrader);

$fileName = "CellPhone.java";
$className = basename($fileName, ".java");
$testModule->addRequiredFile($fileName);
$testModule->addSourceFile($fileName, file_get_contents($fileName));

$testModule->addPreTestCommand("$javac $fileName");

$testModule->addPostTestCommand("rm *.class");

$arr = array( 
"250 10 150" => "
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