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

//Challenge 19 - Signal decode


define("CHALLENGE", "19");
include("TuentiLib.php");

function getKey($int){ //KeyGenerator::getKey(int)
	$keys1 = array("4","8","15","16","23");	
	$keys2 = array("42","4","8","15","16");
	return $keys2[$int].$keys1[$int];

}
echo getKey((int) TuentiLib::getLine()).PHP_EOL;