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


function calculate($str, $rt, $iteration){
	$len = strlen($str);
	$write = fopen("dictionaries/temp_md5$iteration", "w+");
	stream_set_write_buffer($write, 0);
		$loop = 0;
		$repeat = 0;
		$number = "";
		$rstr = "";
		$buffer = "";
		for($i = 0; $i < $len; ++$i){
			$c = $str{$i};
			switch($c){
				case "[":
					if($loop === 0){
						$repeat = $number === "" ? 1:intval($number);
						$start = $i + 1;
						$number = "";
					}else{
						$rstr .= $c;
					}
					++$loop;
					break;
				case "]":
					--$loop;
					if($loop === 0){
						$w = calculate($rstr, $repeat, $iteration + 1);
						fseek($w, 0);
						fwrite($write, $buffer);
						$buffer = "";
						while($read = fread($w, 67108864)){
							if($read === ""){
								break;
							}
							fwrite($write, $read);
						}
						ftruncate($w, 0);
						fclose($w);
						$rstr = "";
						$repeat = 0;
					}else{
						$rstr .= $c;
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
					}else{
						$rstr .= $c;
					}
					break;
				default:
					if($loop === 0){
						$buffer .= $c;
					}else{
						$rstr .= $c;
					}				
					break;
			}
		}
	fwrite($write, $buffer);
	@unlink("dictionaries/temp_md5_$iteration");
	@copy("dictionaries/temp_md5$iteration", "dictionaries/temp_md5_$iteration");
	$write2 = fopen("dictionaries/temp_md5_$iteration", "a+");
	stream_set_write_buffer($write2, 0);
	for($r = 1; $r < $rt; ++$r){
		fseek($write, 0);
		while($read = fread($write, 67108864)){
			if($read === ""){
				break;
			}
			fwrite($write2, $read);
		}
	}
	ftruncate($write, 0);
	fclose($write);
	return $write2;
}

$case = 1;
while(($line = TuentiLib::getLine()) !== false){
$md5 = hash_init("md5");

$buffer = "";
$stream = calculate($line, 1, 0);
fseek($stream, 0);
hash_update_stream($md5, $stream);
echo hash_final($md5).PHP_EOL;

++$case;
}