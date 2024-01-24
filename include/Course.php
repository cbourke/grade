<?php

include_once("Config.php");
include_once("Assignment.php");
include_once("Student.php");

/**
 * Represents a course in the handin/grader system.  Includes the
 * course assignments, roster, and graders.
 */
class Course {

  private $assignments = array();
  private $roster = array();
  private $rosterMap = array();
  private $graderMap = array();

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
    $this->roster[] = $student;
    $this->rosterMap[$student->getLogin()] = $student;
  }

  public function addGrader($login, $name = "N/A") {
    $this->graderMap[$login] = $name;
  }

  public function getRoster() {
    return $this->roster;
  }

  public function getAssignments() {
    return $this->assignments;
  }

  public function getStudent($login) {
    if(array_key_exists($login, $this->rosterMap)) {
      return $this->rosterMap[$login];
    } else {
      return null;
    }
  }

  public function isStudent($login) {
    return array_key_exists($login, $this->rosterMap);
  }

  public function isGrader($login) {
    return array_key_exists($login, $this->graderMap);
  }

  public static function createCourse() {

    global $config;
    $result = new Course();
    $handle = fopen($config['homework_file'], "r");
    if(!$handle) {
      return;
    }

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
    if(!$handle) {
      return;
    }

    while(!feof($handle)) {
      $line = fgets($handle);
      if(strlen(trim($line)) > 0) {
        //mail.list and gta-mail.list line format:
        //cbourke3 chris.bourke@unl.edu (Christopher Bourke)
        // however, students can have many names, not just last/first
        // limit to 3
        $tokens = explode(" ", $line, 3);
        if(count($tokens) == 3) {
          $canvasLogin = $tokens[0];
          $email = $tokens[1];
          $name = $tokens[2];
          $s = new Student(trim($canvasLogin), trim($name));
          $result->addStudent($s);
        }
      }
    }

    $handle = fopen($config['ta_mail_file'], "r");
    if($handle) {
      //only process if the file existed
      while(!feof($handle)) {
        $line = fgets($handle);
        $tokens = explode(" ", $line, 3);
        if(count($tokens) == 3) {
          $canvasLogin = $tokens[0];
          $email = $tokens[1];
          $name = $tokens[2];
          $result->addGrader(trim($canvasLogin), trim($name));
        }
      }
    }

    return $result;
  }

}

?>
