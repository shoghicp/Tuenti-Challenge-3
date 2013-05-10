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

//THIS NEEDS GMP!


//Challenge 20 - Alien invasion


define("CHALLENGE", "20");
include("TuentiLib.php");

$str = "";
while(($line = TuentiLib::getLine()) !== false){
	$str .= $line;
}
TuentiLib::dump(base64_decode($str), "alien");

$bytes = "\x78\xA3\x65\x55\xED\xF5\x90\xDA\x54\xDA\x5C\x68\xC8\xE1\x75\xD6\x42\xB7\x7E\x86\x0A\x17\x92\x65\x0C\xAE\x47\x78\xF7";
$blen = strlen($bytes);
$password = str_repeat("\x00", $blen);

function calculateS($v7){
	$v7 = gmp_mul($v7, 16807);
	$v7 = gmp_and($v7, "0xFFFFFFFF");
	$v7 = gmp_mod($v7, 0x7FFFFFF);
	return (int) gmp_strval($v7, -10);
}


$v7 = 1337;
for($pointer = 0; $pointer < $blen; ++$pointer){
	$v4 = $v7 = calculateS($v7);
	$password{$pointer} = chr(ord($bytes{$pointer}) ^ ($v4 & 0xFF));
}

echo $password.PHP_EOL;


exit(0);


//-------------- SOURCE PROGRAM CONVERTED ----------------

$bytes = "\x78\xA3\x65\x55\xED\xF5\x90\xDA\x54\xDA\x5C\x68\xC8\xE1\x75\xD6\x42\xB7\x7E\x86\x0A\x17\x92\x65\x0C\xAE\x47\x78\xF7";

array_shift($argv); //delete the script name
$argv = implode(" ", $argv);

$v2 = 0;

$pointer = 0; //argv pointer
$v7 = 1337;

while(true){
	if(!isset($argv{$pointer})){
		break;
	}
	$v5 = ord($argv{$pointer});
	$v7 = $v4 = calculateS($v7);
	if($pointer > 0x1C || (($v4 & 0xFF) ^ $v5) !== ord($bytes{$pointer})){
		$v2 = -1;
	}
	++$pointer;
}

if($pointer <= 0x1C){
	$v2 = -1;
}

exit($v2);