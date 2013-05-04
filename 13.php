<?php

/*
	Tuenti Programming Challenge 3
	https://contest.tuenti.net/
	
	@author: shoghicp
	@email: shoghicp@gmail.com
	
	This must be run using at least PHP 5.4,
	enabling BCMath and the pthreads extension.
	TuentiLib: http://bit.ly/14jgAPV
	Windows binaries: http://bit.ly/14j6jTO
*/

//Challenge 13 - Sparse randomness

define("CHALLENGE", "13");
include("TuentiLib.php");

ini_set("memory_limit", "-1");

$cases = (int) TuentiLib::getLine();

for($case = 1; $case <= $cases; ++$case){
	$counts = explode(" ", TuentiLib::getLine());
	$numbers = array_map("intval", explode(" ", TuentiLib::getLine()));
	$pointer = false;
	$last = false;
	$bursts = array();
	foreach($numbers as $i => $n){
		if($last === $n){
			if(!is_array($pointer)){
				$pointer = array($i - 1, $i);
			}else{
				$pointer[1] = $i;
			}
		}elseif($pointer !== false){
			$bursts[$pointer[1]] = $pointer;
			$pointer = false;
		}
		$last = $n;
	}
	echo "Test case #".$case.PHP_EOL;
	for($study = 1; $study <= $counts[1]; ++$study){
		$ranges = explode(" ", TuentiLib::getLine());
		$startR = ((int) $ranges[0]) - 1;
		$endR = ((int) $ranges[1]) - 1;
		$maxR = 1;
		foreach($bursts as $i => $range){
			if($range[0] <= $endR and $range[1] >= $startR){
				$count = min($endR, $range[1]) - max($startR, $range[0]) + 1;
				if($count > $maxR){
					$maxR = $count;
				}
			}elseif($i >= $endR){
				break;
			}
		}
		
		echo $maxR.PHP_EOL;
	}
}