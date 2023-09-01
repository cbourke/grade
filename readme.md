
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
     When requesting a handin account for a course, please indicate if you want to
     host the handin assignments in your own directory or in a course account.
  2. Verify the setup the handin account for your course using the handin interface 
     (https://cse-apps.unl.edu/handin). A connectivity test will verify if the handin
     application can access your cse-linux-01 account and the handin directory within it.
     
  3. Create all of your assignments and "Sync Assignments" to the **cse-linux-01** server.
  4. Create a `public_html` directory in your class account and
     clone this repository to it:  
     `git clone https://github.com/cbourke/grade`

## Usage

1. Inform your students that they can access the grader interface
by pointing their browser to  
https://cse.unl.edu/~classAcct/grade  
Where `classAcct` is either your own account or the separate local class account (ex: `c-cbourke2` for a local class account).  Graders can
access a separate grader interface by going to:  
https://cse.unl.edu/~classAcct/grade/grader.php  
A "grader" can login using their CSE credentials if they are listed
as a TA in the webhandin or they can use the course account credentials.

2. The webhandin will automatically sync files that students
handin to the CSE file system.  These are copies and the originals
are stored in the webhandin's database.  You can modify these files
but take care as doing so will leave them out of sync with the
originals stored by webhandin.

3. The webgrader interface expects that there is a grading script
called `grade.php` in each assignment subdirectory.  For example,
for and assignment with a label of `3`, it would expect there to
be an executable script in `~/handin/3/grade.php`.  If such a file
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

4. Sometimes students turn in garbage code; code that gets caught
in an infinite loop or has a memory leak, etc.  Though there are
limits on the server itself, you should establish a crontab to kill
out-of-control processes.  A script has been provided to do this
(`processKiller/Process.php`).  An example crontab is provided in
the same directory.  Failure to install this crontab may mean
that the sysadmins will suspend your course account until it is
addressed.

5. Because limits have been placed on processes launched through apache,
when grading Java programs, you may need to throttle the JVM to use
no more than 2GB of memory when launched.  To do this, instead of the
usual java command, you would use:
`java -Xmx2048m ...`
The same limitation would need to be there for the Java compiler:
`javac -J-Xmx2048m ...`

6. An example grading script written in PHP has been provided (see `/examples`) 
that can be used to design a testing suite including pre- and post- test commands.
