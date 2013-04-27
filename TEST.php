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

//Test Challenge: Super hard sum


define("CHALLENGE", "TEST");
include("TuentiLib.php");

while(($line = TuentiLib::getLine()) !== false){
	$result = "0";
	$line = explode(" ", str_replace("\t", " ", trim($line)));
	foreach($line as $number){
		if($number === ""){
			continue;
		}
		$result = bcadd($result, $number);
	}
	echo $result . PHP_EOL;
}