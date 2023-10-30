<?php

class Course {

  private $assignments = array();
  private $roster = array();

  /**
   * The given line is expect to be in the proper format
   */
  private function __construct() {
  }

  public function getCourseNumber() {
    return $this->courseNumber;
  }

  public function addAssignment($assignment) {
    if($assignment != null) {
      $this->assignments[$assignment->getNumber()] = $assignment;
    }
  }

  public function addStudent($student) {
    $roster[] = $student;
  }

  public function getAssignments() {
    return $this->assignments;
  }

  public static function createCourse() {

    $result = new Course();
    $handle = fopen($config['homework_file'], "r");

    while(!feof($handle)) {
      $line = fgets($handle);
      $line = trim($line);
      if(!empty($line)) {
        $assignment = Assignment::createAssignment($line);
        if($assignment) {
          $result->addAssignment($assignment);
        }
      }
    }

    $handle = fopen($config['mail_file'], "r");

    while(!feof($handle)) {
      $line = fgets($handle);
      if(strlen(trim($line)) > 0) {
        $tokens = explode(" ", $line);
        $name = "";
        for($i=1; $i<count($tokens); $i++) {
          if(strlen(trim($tokens[$i])) > 1) {
	          $name .= "$tokens[$i] ";
	        }
        }
        $s = new Student(trim($tokens[0]), trim($name));
        $result->addStudent($s);
      }
    }

    return $result;
  }

  public function __toString() {
    $result = "$this->courseNumber";
    $result .= "\n";

    foreach($this->assignments as $assign) {
      $result .= "$assign\n";
    }
    return $result;
  }

}

?>
