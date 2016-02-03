<?php

include_once("GradeInc.php");

$course = Course::createCourse($config['homework_file']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Grading Checker</title>

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script type="text/javascript">
function redirectHTTPS() {
  if (window.location.protocol != "https:") {
      window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);
  }
}

function raiseError(msg) {
  var newDiv = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Warning!</strong> '+msg+'</div>';
  $("#errorDiv").append(newDiv).hide().fadeIn("slow");
}

function validateSubmit() {
  $("#errorDiv").empty();
  //butt, huh huh
  var butt = document.getElementById('submitButton');
  butt.disabled = true;
  var hwNum = $("#hw_num").val();
  var login = $("#cse_login").val();

  if(hwNum < 0) {
    raiseError("You must choose a valid assignment number.");
    butt.disabled = false;
    return false;
  } else if(!login || login === '') {
    raiseError("Enter your login.");
    butt.disabled = false;
    return false;
  }

  $("#graderResultsHeader").html("<h2>Grader Results</h2>");
  $("#graderResults").html("");
  $("#graderResults").append("<div id='grade_content'><img src='images/loading.gif' alt='loading'/></div>");

  $.ajax({
    url: 'grade.php',
    method: "POST",
    data: {
      hw_num: hwNum,
      cse_login: login,
      cse_password: document.grade_form.cse_password.value,
    },
    success: function (data) {
      $('#graderResults').html(data).hide().fadeIn("slow");
      //prettyPrint();
      butt.disabled = false;
    },
    error: function (xhr, statusText) {
      $('#graderResults').html("<span style='color:red;font-weight:bold'>Internal Server Error</span>");
      butt.disabled = false;
    },
    dataType: 'html'
  });

  return false;
}
</script>
</head>

<body onload="redirectHTTPS()">
<div id="bodyContainer" class="container">
<h1>Program Grade Checker v<?php print $config['version']; ?></h1>
<?php
if(!$course) {
  print '<div class="alert alert-danger"><strong>Error!</strong> The grader has not been properly setup, please notify your instructor that the homework file has not been synched with the webhandin.</div>';
  exit(1);
}
?>
<h4><?php print $course->getCourseNumber(); ?></h4>

<p>This web interface allows you to run the same grading script
that we use in evaluating and grading your program(s).</p>

<div id="errorDiv"></div>

<div class="container">
<h4>Grade My Program</h4>

<form class="form-horizontal" name="grade_form" action="grade.php" method="post" onsubmit="return validateSubmit()">
  <div class="form-group">
    <label for="cse_login" class="col-sm-2 control-label">Login</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" id="cse_login" placeholder="CSE Login">
    </div>
  </div>
  <div class="form-group">
    <label for="cse_password" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-6">
      <input type="password" class="form-control" id="cse_password" placeholder="CSE Password">
    </div>
  </div>
  <div class="form-group">
    <label for="hw_num" class="col-sm-2 control-label">Assignment</label>
    <div class="col-sm-6">

<select id="hw_num" name="hw_num" class="form-control">
<option value="-1">(choose)</option>
<?php
  foreach ($course->getAssignments() as $assign) {
   $option = "<option value=\"".$assign->getNumber()."\"";
   if(!$assign->hasGradeScript()) {
     $option .= " disabled=\"disabled\"";
   }
   $option .= ">".$assign->getNumber()." ".$assign->getLabel()."</option>\n";
   print $option;
  }
?>
</select>
    <p style="opacity: .75">Disabled assignments cannot be graded as the grade script has not been setup.</p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button id="submitButton" type="submit" class="btn btn-primary">Grade Me</button>

      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalHelp">Help</button>

    </div>
  </div>
</form>
</div>

<div id="graderResultsHeader"></div>
<div id="graderResults" class="container"></div>

<div style="color: white;"><?php print Egg::getEgg(); ?></div>

</div><!--end bodyContainer -->

<div class="modal fade" id="modalHelp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Webgrader Help</h4>
      </div>
      <div class="modal-body">
        <h3>Frequently Asked Questions</h3>

<ol>
  <li><b>What is webhandin?</b></li>

<p>Webhandin is our web-based interface that we use that allows you to hand-in electronic versions of files (source, data, archive, JARs, etc.).  
Instructions (how to create an account, reset a password, handin for a specific course) are available on that page.  Use webhandin to handin all electronic copies of your homework as specified.  You can re-handin any materials as many times as you want up to the due date.  Older versions are archived, but only the most recently handed-in version will be considered.</p>

  <li><b>What is webgrader</b></li>
<p>Webgrader is the companion system that allows you to run the same script(s) that we.ll run when we grade your assignments.  You can use it to determine if your program(s) will compile and execute as specified.  You can regrade yourself as many times as you like.  Keep in mind that webgrader should be your last sanity check.  It is not a useful tool for debugging or figuring out what is wrong with your program(s).  You should thoroughly test and debug your code prior to using webgrader.</p>

  <li><b>I tried to use webgrader, but it never came back with a response, my browser just sat there loading.</b></li>
  <p>Most likely, your program is either 1) stuck in an infinite loop, or 2) prompting a user for input when no input is being given.  You need to a) debug and thoroughly test your program before you rely on the webgrader; and b) you need to follow instructions on how input should be given and handled (command line arguments, not re-prompting when given bad input, etc.)</p>

  <li><b>Okay, I used webgrader and I get something like the following error:</b>
  <code>Exception in thread "main" java.lang.NoClassDefFoundError: unl/cse/Foo</code></li>

  <p>Your source file(s) likely did not compile correctly, so when the grader went to run your class, it couldn't find it because the compilation failed.  The compiler errors should be output by the grader; address them and try again.  It may also be related to the next question.</p>

  <li><b>I used webgrader, there were <i>no</i> compiler errors, but I still get the <code>NoClassDefFoundError</code> exception.</b></li>

  <p>You may have failed to follow instructions and either placed your code in an incorrectly named class or in the wrong package.  Double check what the expectations are on your class and package names.</p>

  <li><b>Okay, so webgrader is still failing, but I don't know why.  Help!</b></li>

  <p>Webgrader is not a debugging tool.  You need to debug using the proper tools.  You should have learned the general principles of debugging in 155.  If you are new to Eclipse, Java, or have forgotten how to debug Java using Eclipse, then:</p>
  <ul>
    <li>Review any materials on debugging from your 155 course</li>
    <li>Watch some video tutorials on debugging in your language/IDE</li>
  </ul>
</ol>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>

