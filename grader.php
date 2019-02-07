<?php

include_once("GradeInc.php");
$course = Course::createCourse($config['homework_file']);
$roster = Roster::createRoster($config['mail_file']);

?>
<html>
<head>
  <title>Grading Checker</title>

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

  <link href="google-code-prettify-minified/prettify.css" type="text/css" rel="stylesheet" />
  <script type="text/javascript" src="google-code-prettify-minified/prettify.js"></script>

<script type="text/javascript">
<?php
  $jsArray = "[";
  $students = $roster->getStudents();
  $n = count($students);
  for($i=0; $i<$n-1; $i++) {
    $jsArray .= "\"" . $students[$i]->getLogin() . "\", ";
  }
  $jsArray .= "\"" . $students[$n-1]->getLogin() . "\"]\n";
?>
$loginArray = <?php print $jsArray; ?>;
$(document).ready(function() {
    $("#student_cse_login").autocomplete({
    source: $loginArray
});
  });

function redirectHTTPS() {
  if (window.location.protocol != "https:") {
    window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);
  }
}

function loadIt() {

  $("#results_header").html("Individual Grade Results");
  $("#results").html("");

  var student_login = document.grade_form.student_cse_login.value;
  let title = 'Grading Checker';
  $(document).prop('title', title);

  $("#results").append("<div id='grade_content'><img src='images/loading.gif' alt='loading'/></div>");

  $.ajax({
    url: 'grader_grade.php',
    method: 'POST',
    data: {
      hw_num: document.grade_form.hw_num.value,
      student_cse_login: student_login,
      cse_password: document.grade_form.cse_password.value,
    },
    success: function (data) {
      $('#grade_content').html(data);
      $(document).prop('title', '\u2714 ' + title);
      prettyPrint();
    },
    error: function (xhr, statusText) {
      $(document).prop('title', '\u2716 ' + title);
      $('#grade_content').html("<span style='color:red;font-weight:bold'>Internal Server Error</span>");
    },
    dataType: 'html'
  });


}
</script>
</head>

<body onload="redirectHTTPS()">
<h1><?php print $course->getCourseNumber(); ?> Program Grade Checker - Grader Interface</h1>

<p>This interface is for graders</p>

<h2>Grade A Student's Program</h2>
<form id="grade_form" name="grade_form" action="" method="get">
<div style="margin: 1em;">
  <table>
    <tr>
      <td>CSE Login</td>
      <td><?php print $course->getCourseNumber(); ?></td>
    </tr>
    <tr>
      <td>CSE Password</td>
      <td><input type="password" name="cse_password"/></td>
    </tr>
    <tr>
      <td>Student CSE Login</td>
      <td><input type="text" name="student_cse_login" id="student_cse_login" /></td>
    </tr>
    <tr>
      <td>Assignment</td>
<td>
<select name="hw_num">
<?php
  foreach ($course->getAssignments() as $assign) {
   print "<option value=\"".$assign->getNumber()."\" ";
   if(!$assign->hasGraderGradeScript()) {
     print " disabled=\"disabled\"";
   }
   print ">".$assign->getNumber()." ".$assign->getLabel()."</option>\n";
  }
?>
</select>
</td>
    </tr>
    <tr>
      <td></td>
      <td><input type="button" value = "Grade Them!" onclick="loadIt();"/>
      </td>
    </tr>

  </table>
</form>

<h1 id="results_header"></h1>
<div id="results"></div>

</body>
</html>
