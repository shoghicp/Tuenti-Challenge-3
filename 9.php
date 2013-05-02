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

//Challenge 9 - Defenders of the Galaxy


define("CHALLENGE", "9");
include("TuentiLib.php");

$cases = (int) TuentiLib::getLine();

for($case = 1; $case <= $cases; ++$case){
	$d = explode(" ", TuentiLib::getLine());
	$zorgs = $width = (int) array_shift($d);
	$height = (int) array_shift($d);
	$soldierCost = (int) array_shift($d);
	$burnCost = (int) array_shift($d);
	$gold = (int) array_shift($d);
	$time = 0;
	
	if($soldierCost == 0 or $burnCost == 0 or floor($gold / $soldierCost) >= $zorgs){ //Win!
		echo "-1".PHP_EOL;
		continue;
	}
	$maxBurns = (int) floor($gold / $burnCost);
	$maxTime = 0;
	for($burns = 0; $burns <= $maxBurns; ++$burns){
		$soldiers = floor(($gold - ($burns * $burnCost))/$soldierCost);
		$fillRate = $zorgs - $soldiers;
		$timeToFill = floor(($width * $height - $soldiers) / $fillRate);//Area / fillRate
		$time = $timeToFill * ($burns + 1);
		$maxTime = max($maxTime, (int) $time);
	}
	echo $maxTime . PHP_EOL;
}