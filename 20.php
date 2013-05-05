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

//Challenge 20 - Alien invasion


define("CHALLENGE", "20");
include("TuentiLib.php");

$str = "";
while(($line = TuentiLib::getLine()) !== false){
	$str .= $line;
}

TuentiLib::dump(base64_decode($str), "alien");