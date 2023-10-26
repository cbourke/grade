<?php

include_once("GradeInc.php");
$course = Course::createCourse($config['homework_file']);
$roster = Roster::createRoster($config['mail_file']);

$username = getUsername();
if ($username === "TIMED_OUT_USER") {
    login();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Grading Checker</title>

    <script type="text/javascript" src="./js/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="./css/jquery-ui.css">
    <script type="text/javascript" src="./js/jquery-ui.1.13.2.min.js"></script>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"
            integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm"
            crossorigin="anonymous"></script>

    <script src="./google-code-prettify/run_prettify.js" defer></script>

    <script src="js/hotkey.js"></script>

    <script type="text/javascript">
        <?php
        $jsArray = "[";
        $students = $roster->getStudents();
        $n = count($students);
        for ($i = 0; $i < $n - 1; $i++) {
            $jsArray .= sprintf("{ value: '%s', label: '%s %s'}, ", $students[$i]->getLogin(), $students[$i]->getLogin(), $students[$i]->getName());
        }
        $jsArray .= sprintf("{ value: '%s', label: '%s %s'}]", $students[$n - 1]->getLogin(), $students[$i]->getLogin(), $students[$n - 1]->getName());
        ?>
        $loginArray = <?php print $jsArray; ?>;
        $(document).ready(function () {
            $("#student_cse_login").autocomplete({
                source: $loginArray,
                focus: function (event, ui) {
                    $("#student_cse_login").val(ui.item.value);
                    return false;
                },
                select: function (event, ui) {
                    $("#student_cse_login").val(ui.item.value);
                    return false;
                }
            });
        });

        function redirectHTTPS() {
            if (window.location.protocol !== "https:") {
                window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);
            }
        }

        function loadIt() {

            $("#results_header").html("Individual Grade Results");
            $("#results").html("");

            var studentLogin = document.grade_form.student_cse_login.value;
            index = studentLogin.indexOf(' ');
            studentLogin = index > 0 ? studentLogin.substr(0, index) : studentLogin;
            document.grade_form.student_cse_login.value = studentLogin;

            let title = 'Grading Checker';
            $(document).prop('title', title);
            let urlParams = new URLSearchParams(window.location.search);
            let ticket = urlParams.get('ticket');

            $("#results").append("<div id='grade_content'><img src='images/loading.gif' alt='loading'/></div>");

            $.ajax({
                url: 'grader_grade.php',
                method: 'POST',
                data: {
                    hw_num: document.grade_form.hw_num.value,
                    student_cse_login: studentLogin,
                    grader_login: '<?php echo $username; ?>',
                    ticket,
                },
                success: function (data) {
                    $('#grade_content').html(data);
                    $(document).prop('title', `\u2714 ${title}`);
                    PR.prettyPrint();
                },
                error: function (xhr, statusText) {
                    $(document).prop('title', `\u2716 ${title}`);
                    $('#grade_content').html("<span style='color:red;font-weight:bold'>Internal Server Error</span>");
                },
                dataType: 'html'
            });


        }
    </script>

    <style type="text/css">
        /* Necessary for wrapping long lines,
           however it only breaks on whitespace;
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

<body class="container-fluid" onload="redirectHTTPS()">
<h2><?php print $course->getCourseNumber(); ?> Program Grade Checker - Grader Interface</h2>

<p>This interface is for graders</p>

<h4>Grade a Student's Program</h4>

<div class="col-md-6">
    <form id="grade_form" name="grade_form" action="" method="get">
        <div class="form-group row">
            <label for="cseLogin" class="col-sm-3 col-form-label">Login</label>
            <div class="col-sm-9">
                <input class="form-control" type="text" placeholder="<?php print $username; ?>"
                       id="cseLogin" readonly/>
            </div>
        </div>
        <div class="form-group row">
            <label for="student_cse_login" class="col-sm-3 col-form-label">Student Login</label>
            <div class="col-sm-9">
                <input class="form-control" type="text" name="student_cse_login" id="student_cse_login"/>
            </div>
        </div>
        <div class="form-group row">
            <label for="hw_num" class="col-sm-3 col-form-label">Assignment</label>
            <div class="col-sm-9">
                <select name="hw_num" class="form-control" id="hw_num">
                    <?php
                    foreach ($course->getAssignments() as $assign) {
                        print "<option value=\"" . $assign->getNumber() . "\" ";
                        if (!$assign->hasGraderGradeScript()) {
                            print " disabled=\"disabled\"";
                        }
                        print ">" . $assign->getNumber() . " " . $assign->getLabel() . "</option>\n";
                    }
                    ?>
                </select>
            </div>
        </div>
        <button id="submitButton" type="button" class="btn btn-primary" onclick="loadIt();">Submit</button>
        <button id="submitButton" type="button" class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
    </form>
</div>

<h1 id="results_header"></h1>
<div id="results"></div>

</body>
</html>
