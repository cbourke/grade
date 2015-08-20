<?php

class Assignment {

  private $number = null;
  private $label = null;

  private $hasGradeScript = null;
  private $hasGraderGradeScript = null;

  public function __construct($number, $label, $hasGradeScript = false, $hasGraderGradeScript = false) {
    $this->number = $number;
    $this->label = $label;
    $this->hasGradeScript = $hasGradeScript;
    $this->hasGraderGradeScript = $hasGraderGradeScript;
  }

  public function getNumber() {
    return $this->number;
  }

  public function getLabel() {
    return $this->label;
  }

  public function hasGradeScript() {
    return $this->hasGradeScript;
  }

  public function hasGraderGradeScript() {
    return $this->hasGraderGradeScript;
  }

  public static function createAssignment($strLine) {

    $cleanLine = trim($strLine);

    $tokens = preg_split("/[\s]+/", $cleanLine, 2);

    $number = $tokens[0];
    $label  = $tokens[1];

    $gradeScriptExists = false;
    $graderGradeScriptExists = false;

    global $config;
    $webhandin_home = $config["webhandin_relative_path"];

    $gradeDir = "$webhandin_home$number/";
    $gradeScript = $config["script_name"];
    $graderScript = $config["script_name_grader"];
    $gradeScriptPath = "$gradeDir$gradeScript";
    if(file_exists($gradeScriptPath)) {
      $gradeScriptExists = true;
    }
    if(file_exists("$gradeDir$graderScript")) {
      $graderGradeScriptExists = true;
    }
    return new Assignment($number, $label, $gradeScriptExists, $graderGradeScriptExists);

  }

  public function __toString() {
    $tmp = $this->number . " " . $this->label;
    if(!$this->hasGradeScript) {
      $tmp .= " (no grade script)";
    }
    return $tmp;
  }
    									
}

/*
$a = Assignment::createAssignment("1 Quicksort homework 2 2003-8-23 14:30 2003-8-25 14:30 ");
$b = Assignment::createAssignment("2a Quicksort homework 2 2003-8-23 14:30");
$c = Assignment::createAssignment("2b Quicksort homework 2");

print "$a\n";
print "$b\n";
print "$c\n";
*/

?>
