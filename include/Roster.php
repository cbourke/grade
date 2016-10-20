<?php

class Student {

  private $name = null;
  private $login = null;

  public function __construct($login, $name = null) {
    $this->login = trim($login);
    $this->name = $name;
  }

  public function getLogin() {
    return $this->login;
  }

  public function getName() {
    return $this->name;
  }

  public function __toString() {
    $result = "$this->login ( $this->name )";
    return $result;
  }
}

function findStudentByLogin($students, $login) {
  foreach($students as $student) {
    if( strcmp($student->getLogin(), $login) == 0 ){
      return $student;
    }
  } 
  return null;
}

class Roster {

  private $students = array();
  
  public function getStudents() {
    return $this->students;
  }

  public function addStudent($s) {
    $this->students[] = $s;
  }

  public static function createRoster($file) {

    if(!file_exists($file)) {
      return false;
    }
    $handle = fopen($file, "r");

    $result = new Roster();
    
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
    $result = "Roster:\n";

    foreach($this->students as $s) {
      $result .= "$s\n";
    }
    return $result;
  }
    									
}
/*
$b = Roster::createRoster("../../mail.list");

print "$b\n";
*/
?>
