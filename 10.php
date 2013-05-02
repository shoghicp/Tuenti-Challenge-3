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

function calculate(&$md5, $str, $rt){
	$len = strlen($str);
	for($r = 0; $r < $rt; ++$r){
		$loop = 0;
		$repeat = 0;
		$number = "";
		$start = 0;
		$hash = "";
		for($i = 0; $i < $len; ++$i){
			$c = $str{$i};
			switch($c){
				case "[":
					if($loop === 0){
						if(strlen($hash) > 0){
							hash_update($md5, $hash);
							$hash = "";
						}
						$repeat = $number === "" ? 1:intval($number);
						$start = $i + 1;
						$number = "";
					}
					++$loop;
					break;
				case "]":
					--$loop;
					if($loop === 0){
						calculate($md5, substr($str, $start, $i - $start), $repeat);
						$start = 0;
						$repeat = 0;
					}
					break;
				case "0":
				case "1":
				case "2":
				case "3":
				case "4":
				case "5":
				case "6":
				case "7":
				case "8":
				case "9":
					if($loop === 0){
						$number .= $c;
					}
					break;
				default:
					if($loop === 0){
						$hash .= $c;
					}				
					break;
			}
		}
		if(strlen($hash) > 0){
			hash_update($md5, $hash);
		}
	}
}

$case = 1;
while(($line = TuentiLib::getLine()) !== false){
$md5 = hash_init("md5");
calculate($md5, $line, 1);
echo hash_final($md5).PHP_EOL;

++$case;
}