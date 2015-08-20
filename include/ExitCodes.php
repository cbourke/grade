<?php

//TODO: more exit codes as necessary 
//http://www.slac.stanford.edu/BFROOT/www/Computing/Environment/Tools/Batch/exitcode.html

$exitCodes = Array();

$exitCodes[0] = "No Error";
$exitCodes[134] = "Your job received an abort signal, weird, huh?";
$exitCodes[137] = "CPU Time Limit Exceeded";
$exitCodes[139] = "Segmentation Fault";


?>