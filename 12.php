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

//Challenge 12 - Whispering paRTTY

define("CHALLENGE", "12");
include("TuentiLib.php");
$str = "";
while(($line = TuentiLib::getLine()) !== false){
	$str .= $line;
}

TuentiLib::dump(base64_decode($str), "proto"); //then, to MP3 and MMTTY ;)

echo "FACE BADD C0DE FACE FACE DEAD C0DE DEAD".PHP_EOL;