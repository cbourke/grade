<?php

/**
 * Represents a student in the handin/grader system.  By default
 * all instructors and graders are included as students to enable
 * them to handin, grade, and troubleshoot files.
 */
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
?>
