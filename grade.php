<?php

include_once("GradeInc.php");

$course = Course::createCourse($config['homework_file']);

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Grading Checker</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>

  <script src="js/hotkey.js"></script>

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

function restoreButton() {
  $("#submitButton").removeClass("disabled").prop("disabled",false);
}

function validateSubmit() {
  let title = 'Grading Checker';
  $(document).prop('title', title);
  $("#errorDiv").empty();
  $("#submitButton").addClass("disabled").prop("disabled",true);
  
  var hwNum = $("#hw_num").val();
  var login = $("#cse_login").val();

  if(hwNum < 0) {
    raiseError("You must choose a valid assignment number.");
    restoreButton();
    return false;
  } else if(!login || login === '') {
    raiseError("Enter your login.");
    restoreButton();
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
      //add necessary classes for google code prettifier
      // and run if code is displayed to student-facing interface
      //$('pre').addClass('prettyprint linenums');
      //PR.prettyPrint();
      $(document).prop('title', '\u2714 ' + title);
      restoreButton();
    },
    error: function (xhr, statusText) {
      $('#graderResults').html("<span style='color:red;font-weight:bold'>Internal Server Error</span>");
      $(document).prop('title', '\u2716 ' + title);
      restoreButton();
    },
    dataType: 'html'
  });

  return false;
}
</script>

<script src="./google-code-prettify/run_prettify.js" defer></script>

<style type="text/css">

/* Necessary for wrapping long lines, 
   however it only breas on whitespace;
   very long lines of "data" will still 
   extend past the pre area.  */
pre {
  white-space: pre-wrap;
  border: 1px solid #d3d3d3;
  padding: 10px;
}

/* Necessary for line numbering on every line */
li.L0, li.L1, li.L2, li.L3,
li.L5, li.L6, li.L7, li.L8 {
  list-style-type: decimal !important;
}
</style>

</head>

<body onload="redirectHTTPS()">
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href="https://github.com/cbourke/grade" target="_blank">CSE Webgrader</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <?php 
          if( $config['homepage'] ) {
            print '<a class="nav-link" target="_blank" href="' . $config['homepage'] . '">' . $course->getCourseNumber() . '</a>';
          } else {
            print '<a class="nav-link">' . $course->getCourseNumber() . '</a>';
          }
        ?>
      </li>
      <li>
        <button class="btn btn-dark" data-toggle="modal" data-target="#modalHelp">Help</button>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Resources</a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="https://cse-apps.unl.edu/handin" target="_blank">CSE Handin</a>
            <a class="dropdown-item" href="https://canvas.unl.edu/" target="_blank">Canvas</a>
            <a class="dropdown-item" href="https://cse.unl.edu/faq" target="_blank">System FAQ</a>
            <a class="dropdown-item" href="https://cse.unl.edu/" target="_blank">Department Home</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item">Version <?php print $config['version']; ?></a>
          </div>
      </li>
    </ul>
  </div>
</nav>

<main role="main">

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron">
    <div class="container">
      <?php
      if(!$course) {
        print '<div class="alert alert-danger"><strong>Error!</strong> The grader has not been properly setup, please notify your instructor that the homework file has not been synched with the webhandin.</div>';
        exit(1);
      }
      ?>
      <h4>Student Grader Interface</h4>
      <p>This web interface allows you to run the same grading script
      that we use in evaluating and grading your program(s).</p>

      <div id="errorDiv"></div>

      <div class="col-md-6">
      <form name="grade_form" action="grade.php" method="post" onsubmit="return validateSubmit()">

        <div class="form-group row">
          <label for="cse_login" class="col-sm-3 col-form-label">Login</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" id="cse_login" placeholder="CSE Login">
          </div>
        </div>
        
        <div class="form-group row">
          <label for="cse_password" class="col-sm-3 col-form-label">Password</label>
          <div class="col-sm-9">
            <input type="password" class="form-control" type="password" name="cse_password" id="cse_password" placeholder="CSE Password"/>
          </div>
        </div>

        <div class="form-group row">
          <label for="hw_num" class="col-sm-3 col-form-label">Assignment</label>
          <div class="col-sm-9">
            <select name="hw_num" class="custom-select" id="hw_num">
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
          <small class="form-text text-muted">Disabled assignments cannot be graded as the grade script has not been setup.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="cse_password" class="col-sm-3 col-form-label">&nbsp;</label>
          <div class="col-sm-9">
            <button id="submitButton" type="submit" class="btn btn-primary active">Grade Me</button>
          </div>
        </div>

        

      </form>
      </div>
    </div>
  </div>

  <div class="container-fluid" style="margin: auto">
    <div id="graderResultsHeader"></div>
    <div id="graderResults"></div>
  </div>
  <div style="color: white;"><?php print Egg::getEgg(); ?></div>


</main>

</body>

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

</html>
