<?php

class Process {

  private $user = null;
  private $pid = null;
  private $cpu_perc = null;
  private $mem_perc = null;
  private $startTime = null;
  private $totalTime = null;
  private $command = null;

  public function __construct($user = null, $pid = null, $cpu_perc = null, $mem_perc = null, $startTime = null, $totalTime = null, $command = null) {
    $this->user = $user;
    $this->pid = $pid;
    $this->cpu_perc = $cpu_perc;
    $this->mem_perc = $mem_perc;
    $this->startTime = $startTime;
    $this->totalTime = $totalTime;
    $this->command = $command;
  }

  public function getUser() {
    return $this->user;
  }

  public function getPid() {
    return $this->pid;
  }

  public function getMemoryPercentage() {
    return $this->mem_perc;
  }

  public function getTotalTimeSeconds() {
    $toks = explode(":", $this->totalTime);
    return ($toks[0] * 60 + $toks[1] * 1.0);
  }

  public static function createProcess($strLine) {

    $tokens = preg_split("/[\s]+/", $strLine); //explode("\s", $strLine);
    $n = count($tokens);

    $cmd = "";
    for($i=10; $i<$n; $i++) {
      $cmd .= "$tokens[$i] ";
    }

    $proc = new Process($tokens[0],
    	    		$tokens[1],
			$tokens[2],
			$tokens[3],
			$tokens[8],
			$tokens[9],
			$cmd);
    return $proc;
  }

  public function __toString() {
    $tmp = "$this->user $this->pid $this->cpu_perc% $this->mem_perc% $this->startTime $this->totalTime $this->command";
    return $tmp;
  }

  public static function getCurrentProcesses() {

    $result = Array();
    $runResult = "";
    $exitCode = "";
    exec("ps ux", $runResult, $exitCode);
    for($i=1; $i<count($runResult); $i++) {
      $result[] = Process::createProcess($runResult[$i]);
    }
    return $result;
  }

  public static function processGenocide($timeLimitSec, $memLimitPerc) {

    $all = Process::getCurrentProcesses();
    foreach($all as $one) {
      //print $one . "\n";
      if($one->getTotalTimeSeconds() > $timeLimitSec || $one->getMemoryPercentage() > $memLimitPerc) {
        echo "Killing process " . $one->getPid() . ", run for " . $one->getTotalTimeSeconds() . " seconds, with " . $one->getMemoryPercentage() . "% memory...\n";
	//posix_kill($one->getPid(), SIGQUIT);
	$runResult = "";
	$exitCode = "";
	exec("kill ".$one->getPid(), $runResult, $exitCode);
	foreach($runResult as $line) {
	  echo "\t" . $line . "\n";
	}
      }
    }

  }

}

$timeLimitSec = 300;  //5 minutes
$memLimitPerc = 10.0; //10%%

if(isset($argv[1])) {
  $timeLimitSec = intval($argv[1]);
}
if(isset($argv[2])) {
  $memLimitPerc = doubleval($argv[2]);
}

Process::processGenocide($timeLimitSec, $memLimitPerc);

?>
