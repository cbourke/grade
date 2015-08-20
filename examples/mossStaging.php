<?php

if($argc != 2) {
  print "Usage: php $argv[0] zipFileName.zip\n";
  exit(1);
}

$baseDir = "./Windows";
$zipFile = $argv[1]; //"DataConverter.zip";
$stagingDir = "./mossStaging";
$absBaseDir = getcwd();
$mossSrcDir = "mossSrc";

if(!file_exists($baseDir)) {
  print "ERROR: expected directory, $baseDir, not found, quitting.\n";
  print "This script should be run in ~/webhandin/n/ where n is the homework number.\n";
  exit(1); 
}

mkdir($stagingDir);

foreach(new DirectoryIterator($baseDir) as $fileInfo) {
  if($fileInfo->isDir() && !$fileInfo->isDot()) {
    $filePath = $baseDir . "/" . $fileInfo->getFilename() . "/" . $zipFile;
    if(file_exists($filePath)) {
      $stagingSubDir = $stagingDir . "/" . $fileInfo->getFilename(); 
      mkdir($stagingSubDir);
      $destFile = $stagingSubDir . "/" . $zipFile;
      print "Copying $filePath to $destFile\n";
      copy($filePath, $destFile);
      chdir($stagingSubDir);
      //print "now working in " . getcwd() . "\n";
      //system("unzip $zipFile");
      exec("unzip $zipFile");

      mkdir($mossSrcDir);
      $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("./"), RecursiveIteratorIterator::SELF_FIRST);
      foreach($files as $name => $object){
        if(substr_compare($name, ".java", -5, 5) === 0) {
	  $fname = $object->getFilename();
	  //print "Copying $name to ./$mossSrcDir/$fname ...\n";
	  //print $object->getFilename() . "\n";
          copy($name, "./$mossSrcDir/$fname");
        }
      }

      chdir($absBaseDir);
      //print "now back in " . getcwd() . "\n";
    } else {
      print "WARNING: no zip file for " . $fileInfo->getFilename() . "\n";
    }
  }
}

$cmd = "moss -l java -d $stagingDir/*/$mossSrcDir/*.java";
print "Running: $cmd\n";
system($cmd);
system("rm -R $stagingDir");

?>
