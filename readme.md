
# School of Computing Web Grader Interface

Chris Bourke  
chris.bourke@unl.edu

Webgrader is a web-based front end that allows you to expose grading
scripts for assignments handed in through the School of Computing's webhandin
<https://cse.unl.edu/handin>) system. It allows students and graders to
execute scripts (that you write) using their Canvas (Active Directory, AD)
login credentials.

A short tutorial video has been made available:
https://www.youtube.com/watch?v=CRvXsOfp1Vo

Note: the tutorial video uses CSE login credentials, you will instead use your
AD credentials

## Setup & Installation

These setup instructions assume that you are using the handin and grader
system using a **class account** on the `cse-linux-01.unl.edu` server.
Class accounts begin with a `c-` prefix to your faculty MyRed login
(ex: `c-cbourke3`) and will have a different password not managed by
Active Directory.  To request an account contact the SoC System Admins.
Alternatively, you can host the grader on your own (MyRed/AD) account, but
it will require extra configuration changes that are not documented here.

1. Change the permissions on your root directory to enable apache to
   read it:  
   `chmod 711 ~`

2. Edit your `~/.profile` file to set the default `umask` to `0022` by
   adding the following line:  
   `umask 0022`

3. Setup the `public_html` directory.  Since multiple courses may be
   hosted at once, you'll need to do this for each course and distinguish
   them using the `CLASS` directory name (which can be any valid directory
   name, example: `CSCE156`).  Execute the following commands.

   Create the `public_html` directory and move into it:  
   ```bash
   mkdir public_html
   cd public_html
   ```

   Create the directory for your `CLASS` and move into it:  
   ```bash
   mkdir CLASS
   cd CLASS
   ```

   Clone the grader app:  
   ```bash
   git clone https://github.com/cbourke/grade/
   ```

## Configuration

1. Be sure to setup all assignments and TAs (graders) in the SoC handin system
   and sync these with the grader.  This will automatically create the following
   files in the handin directory (`~handin/CLASS`).
     * `homework` - a formatted list of assignments and due dates
     * `mail.list` - a full roster (instructors, graders, students)
     * `gta-mail.list` a list of instructors and graders

2. Edit the `grade/include/Config.php` file and specify the 3 required items as
   indicated in the file.

3. Setup your grading scripts.  By default, the grader app expects that there
   is a grading script called `grade.php` in *each* `CLASS/ASSIGNMENT`
   subdirectory.  For example, for and assignment with a label of `3`, it
   would expect there to be an *executable* script in `~/handin/CLASS/3/grade.php`.  
   If such a file does not exist, the page will work but users will not be
   able to select that assignment to be graded. Additionally:  
     * The grader interface expects a *different* executable named
       `grade.team.php` (so you can run a different script if you want through
       the grader interface).
     * The scripts **must be executable** (`chmod 700`)
     * The script can be any shell executable, but it will be execute
       without the user's environment setup.  Therefore, you *may* need
       to write the script to establish environmental variables if you
       need them (examples: source an rc file, or use absolute paths for
       external programs such as compilers, etc.).
     * All output, (stdout and stderr) is output to the user, so beware, it is
       recommended that the script produce well-formatted HTML.
     * The name of the grade script is configurable in the `includes/Config.php`
       file.  This is particularly useful if you use a different language
       (`grade.py`)

## Usage

1. Inform your students that they can submit files through the handin system:  
   <https://cse-apps.unl.edu/handin>  
   And that they can grade through the grader app:  
   <https://cse-linux-01.unl.edu/~classAcct/CLASS/grade/>  
   A concrete example:  
   <https://cse-linux-01.unl.edu/~c-cbourke3/CSCE155E/grade/>

2. Inform your graders that they can grade students through the grader interface:  
   <https://cse-linux-01.unl.edu/~c-cbourke3/CSCE155E/grade/grader.php>  
   They can also turn in files and grade themselves to troubleshoot code and
   grading scripts.


## Other Items

* The webhandin will automatically sync files that students submit to the
  `cse-linux-01.unl.edu` file system where webgrader is running.  These are
  **copies** and the originals are stored in the webhandin's database.  You can
  modify these files but take care as doing so will leave them out of sync
  with the originals stored by webhandin.

* Because limits have been placed on processes launched through apache,
  when grading Java programs, you may need to throttle the JVM to use
  no more than 2GB of memory when launched.  To do this, instead of the
  usual java command, you would use:  
  `java -Xmx2048m ...`  
  The same limitation would need to be there for the Java compiler:  
  `javac -J-Xmx2048m ...`

* An example grading script written in PHP has been provided (see `/examples`)
  that can be used to design a testing suite including pre- and post- test commands.
