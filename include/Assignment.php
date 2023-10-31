<?php

/**
 * Represents an assignment in the handin/grader system.
 */
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
    $webhandin_home = $config["handin_path"];

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

?>
