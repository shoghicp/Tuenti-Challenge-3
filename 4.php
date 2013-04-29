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

//Challenge 4 - Missing numbers


define("CHALLENGE", "4");
include("TuentiLib.php");
$f = fopen("dictionaries/integers", "r"); //Little-Endian integers. That makes everything easier due to the patterns ;)
//Start of high byte in order: 399804


//Get the sequence start ;)
$sequenceStart = 0;
$cnt = 0;
fseek($f, 0);
while(true){	
	$n = TuentiLib::readLInt(fread($f, 4));
	if($cnt > 16){
		TuentiLib::debug(__LINE__, "sequenceStart offset $sequenceStart");
		break;
	}elseif(($n >> 24) !== 0x7F){
		if($sequenceStart === 0){
			$sequenceStart = ftell($f) - 4;
		}
		++$cnt;
	}else{
		$sequenceStart = 0;
		$cnt = 0;
	}
}

//Get the sequence end
$sequenceEnd = 0;
$cnt = 0;
fseek($f, -4, SEEK_END);
while(true){	
	$n = TuentiLib::readLInt(fread($f, 4));
	if($cnt > 16){
		TuentiLib::debug(__LINE__, "sequenceEnd offset $sequenceEnd");
		break;
	}elseif(($n >> 24) !== 0x7F){
		if($sequenceEnd === 0){
			$sequenceEnd = ftell($f) + 4;
		}
		++$cnt;
	}else{
		$sequenceEnd = 0;
		$cnt = 0;
	}
	fseek($f, -8);
}

fclose($f);