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

 //Little-Endian integers. That makes everything easier due to the patterns ;)
define("CHALLENGE", "4");
include("TuentiLib.php");
//Split the integers file into 4, each one of 2147483647 bytes except the last one. (total "8589934188")
// split -b 2147483647 integers
$f0 = fopen("dictionaries/xaa", "r");
$f1 = fopen("dictionaries/xab", "r");
$f2 = fopen("dictionaries/xac", "r");
$f3 = fopen("dictionaries/xad", "r");
//Start of high byte in order: 399800

function getNumber($n){
	global $f0, $f1, $f2, $f3, $pointer;
	$pos = bcmul($n, 4, 0);
	$file = (int) bcdiv($pos, "2147483647", 0);
	$pos = bcmod($pos, "2147483647");
	if($file > 3){
		return false;
	}
	$pos = (int) bcmod($pos, "2147483647");
	fseek(${"f".$file}, $pos, SEEK_SET);
	return fread(${"f".$file}, 4);
}
//Get the sequence start ;)
$sequenceStart = 0;
$cnt = 0;
$n = 0;
$maxNum = 2147483547; // -100
while(true){	
	$int = getNumber($n);
	if($cnt > 16){
		TuentiLib::debug(__LINE__, "sequenceStart n $sequenceStart");
		break;
	}elseif($int{3} !== "\x7F"){
		if($sequenceStart === 0){
			$sequenceStart = $n;
		}
		++$cnt;
	}else{
		$sequenceStart = 0;
		$cnt = 0;
	}
	++$n;
}
//Get the sequence end

$sequenceEnd = bcdiv(bcsub("8589934188", ($sequenceStart + 1) * 4), 4, 0);
TuentiLib::debug(__LINE__, "sequenceEnd n $sequenceEnd");

$startNumber = TuentiLib::readLInt(getNumber($sequenceStart));
$endNumber = TuentiLib::readLInt(getNumber($sequenceEnd));
$slice = array();
for($n = 0; $n < $sequenceStart; ++$n){
	$slice[] = TuentiLib::readLInt(getNumber($n));
}
for($n = $sequenceEnd + 1; $n <= $maxNum; ++$n){
	$slice[] = TuentiLib::readLInt(getNumber($n));
}
sort($slice);
$last = count($slice) - 1;
$lost = array();
foreach($slice as $index => $val){
	if(($val >= $startNumber and $val <= $endNumber) or $index === 0 or $index === $last){
		continue;
	}
	$diff = $slice[$index + 1] - $val;
	if($diff >= 2 and (($slice[$index + 1] >= $endNumber and $val >= $endNumber) or $slice[$index + 1] < $startNumber)){
		for($i = 1; $i < $diff; ++$i){
			$lost[] = $val + $i;
		}
	}
}

$cases = intval(trim(TuentiLib::getLine()));
for($case = 0; $case < $cases; ++$case){
	echo $lost[intval(trim(TuentiLib::getLine())) - 1].PHP_EOL;
}