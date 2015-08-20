
Webhandin Web Grader Interface 

Chris Bourke
cbourke@cse.unl.edu

Installation
 1. Make sure to have setup the handin for your course (https://cse-apps.unl.edu/handin)
 2. Unzip/untar the provided tarball into the course account's public_html directory

Usage
 1. Some permissions may need to be set on certain files depending on your umask and/or shell
    settings.  This depends on your mask settings, but in general you will need to do the
    following (in the ~/public_html/grade/ directory):

      chmod 644 images/*
      chmod 644 google-code-prettify-minified/*

 2. Inform your students that they can access the grader interface by pointing their browser to:
    https://cse.unl.edu/~classAcct/grade
    Where "classAcct" is the course login (ex: cse156).  Graders can access a separate grader
    interface by going to:
    https://cse.unl.edu/~classAcct/grade/grader.php

 3. The webhandin will automatically sync all necessary files (homework and mail.list as well
    as copies of all files handed in by students), do not modify these files directly. 
    Changes should be made through the webhandin interface.

 4. The script expects that there is a grading script called "grade.php" in each assignment
    subdirectory.  For example: for homework 3, it would expect there to be an executable file,
    ~/handin/3/grade.php
    If it does not exist, the page will work but give the users notifications that the
    script has not been setup.
     -The script can be any shell executable, but it will be executed without the usual user's
      environment setup.  Therefore, you need to write the script to establish the usual
      environment (source an rc file) or use absolute paths for external programs (compilers, etc)
     -All output, (stdout and stderr) is output to the user, so beware
     -The name of the grade script is configurable in the includes/Config.php file

 5. Sometimes students turn in garbage code; code that gets caught in an infinite loop
    or has a memory leak, etc.  Though there are limits on the server itself, you 
    still need to establish a crontab to kill out-of-control processes.  A script
    has been provided to do this (processKiller/Process.php).  An example crontab 
    is provided in the same directory.  Failure to install this crontab may mean
    that the sysadmins will suspend your course account until it is addressed.

 6. Because limits have been placed on processes launched through apache, when grading
    Java programs, you may need to throttle the Java Virtual Machine to use no more
    than 2GB of memory when launched.  To do this, instead of the usual java command, 
    you would use:
      java -Xmx2048m ...
    The same limitation would need to be there for the Java compiler: 
      javac -J-Xmx2048m ...

 7. A PHP grading script has been provided (see /phpExamples) that can be used to design
    a testing suite including pre- and post- test commands.
