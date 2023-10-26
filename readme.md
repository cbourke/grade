
# Webhandin Web Grader Interface

Chris Bourke
cbourke@cse.unl.edu

Webhandin is a PHP front end that allows you to expose grading scripts for
assignments handed in through The School of Computing's webhandin (https://cse.unl.edu/handin) system.
It allows students and graders to execute scripts (that you provide) using
their MyRed login credentials.

A short tutorial video has been made available:
https://www.youtube.com/watch?v=CRvXsOfp1Vo

Note: the tutorial video uses CSE login credentials, you will instead use your MyRed login.
## Installation
  1. Handin can now be configured to use your own account for handin assignments
     or a separate, local class account on the **cse-linux-01** server. Local class
     accounts created will have a `c-` prefix to your faculty MyRed login, e.g. `c-bourke2`.
     The class accounts replace the previous handin account and offer a persistent way to
     maintain past handin data for courses. Local class accounts will now have classes
     separated under the ~/handin directory by semester, e.g. ~/handin/CSE251_1238 for CSCE251 in Fall 2023.
     When requesting a handin account for a course, please indicate if you want to
     host the handin assignments in your own directory or in a course account.
  3. Login to the handin system and verify your course has been configured. Before you can sync, you will need to
     copy the public key shown in the connectivity test into the local class account's
     **.ssh/authorized_keys** file, e.g. ~c-cbourke2/.ssh/authorized_keys.
  4. Create all of your assignments and "Sync Assignments" to the **cse-linux-01** server.    
  5. Create a `public_html` directory in your local class account and
     clone this repository to it:  
     `git clone https://github.com/cbourke/grade`
  6. Modify the GradeInc.php, grader.php and index.php to contain the path to your local class account directories & files.
     Usually only the class account home directory name is all that is required in these files. Modify Include/Config.php with the
     relative paths to the handin/CourseName for webhandin_relative_path, homework_file, and mail_file.

## Usage

1. Inform your students that they can access the grader interface
by pointing their browser to  
https://cse-linux-01.unl.edu/~classAcct/grade  
Where `classAcct` is either your own account or the separate local class account (ex: `cbourke2` for your local faculty account, or `c-cbourke2` for a local class account).  Graders can
access a separate grader interface by going to:  
https://cse-linux-01.unl.edu/~classAcct/grade/grader.php  
A "grader" can login using their MyRed credentials if they are listed
as a TA in the webhandin.

2. The webhandin will automatically sync files that students
handin to the cse-linux-01.unl.edu file system where webgrader is running.  These are copies and the originals
are stored in the webhandin's database.  You can modify these files
but take care as doing so will leave them out of sync with the
originals stored by webhandin.

3. The webgrader interface expects that there is a grading script
called `grade.php` in each course-name/assignment subdirectory.  For example,
for and assignment with a label of `3`, it would expect there to
be an executable script in `~/handin/course-name/3/grade.php`.  If such a file
does not exist, the page will work but users will not be able to
select that assignment to be graded. Additionally:
    * The script can be any shell executable, but it will be execute
    without the user's environment setup.  Therefore, you *may* need
    to write the script to establish environmental variables if you
    need them (examples: source an rc file, or use absolute paths for
    external programs such as compilers, etc.).
    * All output, (stdout and stderr) is output to the user, so beware
    * The name of the grade script is configurable in the
      `includes/Config.php` file

4. Because limits have been placed on processes launched through apache,
when grading Java programs, you may need to throttle the JVM to use
no more than 2GB of memory when launched.  To do this, instead of the
usual java command, you would use:
`java -Xmx2048m ...`
The same limitation would need to be there for the Java compiler:
`javac -J-Xmx2048m ...`

5. An example grading script written in PHP has been provided (see `/examples`)
that can be used to design a testing suite including pre- and post- test commands.
