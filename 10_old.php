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


define("CHALLENGE", "10");
include("TuentiLib.php");

$case = 1;
while(($line = TuentiLib::getLine()) !== false){
file_put_contents("dictionaries/temp_md50", $line);
$read = fopen("dictionaries/temp_md50", "r");
$x = 1;
$p = true;
$buflen = 1024 * 1024 * 4;
$temp = fopen("dictionaries/temp_md5_temp", "w+");
while($p === true){
	fseek($read, 0);
	$write = fopen("dictionaries/temp_md5$x", "w+");
	$number = "";
	$buffer = "";
	$buffer2 = "";
	$buffer3 = "";
	$repeat = 1;
	$par = 0;
	$p = false;
	while($buffer3 = fread($read, $buflen)){
		if($buffer3 === ""){
			break;
		}
		$llen = strlen($buffer3);
		for($in = 0; $in < $llen; ++$in){
			$c = $buffer3{$in};
			if($c === "]"){
				--$par;
				if($par > 0){
					$buffer2 .= $c;
				}else{
					while($repeat > 0){
						if($writes2 > 0){
							fseek($temp, 0);
							while($r = fread($temp, 65535)){
								if($r === ""){
									break;
								}
								$buffer .= $r;
							}
						}
						$buffer .= $buffer2;
						--$repeat;
						if(strlen($buffer) > $buflen){
							fwrite($write, $buffer);
							$buffer = "";
						}
					}
					if($writes2 > 0){
						$writes2 = 0;
						ftruncate($temp, 0);
					}
					$buffer2 = "";
				}
			}elseif($c === "["){
				if($par > 0){
					$buffer2 .= $c;
				}else{
					fseek($temp, 0);
					$repeat = $number === "" ? 1:intval($number);
					$number = "";
					$p = true;
					$writes2 = 0;
				}
				++$par;
			}elseif($par === 0){
				$o = ord($c);
				if($o >= 0x30 and $o <= 0x39){ //Number
					$number .= $c;
				}else{
					$buffer .= $c;
				}
			}else{
				$buffer2 .= $c;
			}
		}
		
		if(strlen($buffer) > $buflen){
			fwrite($write, $buffer);
			$buffer = "";
		}
		if(strlen($buffer2) > $buflen){
			++$writes2;
			fwrite($temp, $buffer2);
			$buffer2 = "";
		}
	}
	fwrite($write, $buffer);
	$buffer = "";
	@fclose($read);
	@unlink("dictionaries/temp_md5".($x-1));
	$read = $write;
	++$x;
}
fclose($read);
$md5 = md5_file("dictionaries/temp_md5".($x-1));
@unlink("dictionaries/temp_md5".($x-1));
TuentiLib::dump($md5, "md5_".$case);
echo $md5.PHP_EOL;
++$case;
}