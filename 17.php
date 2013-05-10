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

//Challenge 17 - Silence on the wire


define("CHALLENGE", "17");
include("TuentiLib.php");

/*
WTF, Tuenti??
*/

/*
$crc = array(
	TuentiLib::hexToStr("540abf48") => "0",
	TuentiLib::hexToStr("a7a22903") => "1",
);
$bits = "";
for($i = 0; $i <= 3423; ++$i){
	$bits .= $crc[hash_file("crc32", "debug/video/frames/im".str_pad($i, 4, "0", STR_PAD_LEFT).".png", true)];
	if(isset($bits{7})){
		echo chr(bindec($bits));
		$bits = "";
	}
}
*/


//For each input N return the sum of digits of N!


//Clean 2,5 multiples (so no 0's appear)
function clean_factorial10($number){
	$numbers = array();
	$n2 = 0;
	$n3 = 0;
	$n5 = 0;
	for($i = 2; $i <= $number; ++$i){
		$n = $i;
		while(($n % 10) === 0){
			$n /= 10;
		}
		while(($n % 2) === 0){
			$n = $n >> 1;
			++$n2;
		}
		while(($n % 3) === 0){
			$n /= 3;
			++$n3;
		}
		while(($n % 5) === 0){
			$n /= 5;
			++$n5;
		}
		if($n > 1){
			@++$numbers[$n];
		}
	}
	if($n2 > $n5){
		@++$numbers[gmp_strval(gmp_pow(2, $n2 - $n5))];
	}elseif($n5 > $n2){
		@++$numbers[gmp_strval(gmp_pow(5, $n2 - $n5))];
	}
	@++$numbers[gmp_strval(gmp_pow(3, $n2 - $n5))];
	return $numbers;
}

while(($n = TuentiLib::getLine()) !== false){
	
	$numbers = clean_factorial10((int) $n);
	$factorial = 1;
	foreach($numbers as $number => $count){
		$factorial = gmp_mul($factorial, gmp_pow($number, $count));
	}
	$factorial = gmp_strval($factorial);
	$len = strlen($factorial);
	$sum = 0;
	for($i = 0; $i < $len; ++$i){
		$sum += intval($factorial{$i});
	}

	echo $sum.PHP_EOL;
}