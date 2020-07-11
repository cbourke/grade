<?php

class Course {

  private $courseNumber = null;
  private $assignments = array();
  
  /**
   * The given line is expect to be in the proper format
   */
  public function __construct() {
    $this->courseNumber = get_current_user();
  }

  public function getCourseNumber() {
    return $this->courseNumber;
  }

  public function addAssignment($assignment) {
    if($assignment != null) {
      $this->assignments[$assignment->getNumber()] = $assignment;
    }
  }

  public function getAssignments() {
    return $this->assignments;
  }

  public static function createCourse($file) {

    $result = new Course();
    if(!file_exists($file)) {
      return $result;
    }
    $handle = fopen($file, "r");

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
