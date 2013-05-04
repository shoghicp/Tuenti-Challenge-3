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

//Challenge 10 - The Checking Machine
//This needs mhash

define("CHALLENGE", "10");
include("TuentiLib.php");

function recurse($md5, &$buffer, $str, $i){
	while(($c = $str{$i}) !== ""){
		if(is_numeric($c)){
			$i = calculate($md5, $buffer, $str, $i);
		}elseif($c !== "]"){
			$buffer .= $c;
			++$i;
		}else{
			return $i;
		}
	}
	return $i;
}

function calculate($md5, &$buffer, $str, $i){
	$repeat = 0;
	while(is_numeric($str{$i})){
		$repeat *= 10;
		$repeat += (int) $str{$i};
		++$i;
	}

	$oldIndex = $i + 1;
	for($r = 0; $r < $repeat; ++$r){
		$i = recurse($md5, $buffer, $str, $oldIndex);
	}
	if(isset($buffer{1048575})){
		echo $i.PHP_EOL;
		hash_update($md5, $buffer);
		$buffer = "";
	}
	++$i;
	return $i;
}

$case = 1;
while(($line = TuentiLib::getLine()) !== false){
	$md5 = hash_init("md5");
	$buffer = "";
	recurse($md5, $buffer, $line, 0);
	if(strlen($buffer) > 0){
		hash_update($md5, $buffer);
	}
	echo hash_final($md5).PHP_EOL;

	++$case;
}