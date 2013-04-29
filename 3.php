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

//Challenge 3 - Lost in Lost


define("CHALLENGE", "3");
include("TuentiLib.php");

$cases = intval(trim(TuentiLib::getLine()));

for($case = 1; $case <= $cases; ++$case){
	$scenes = null;
	preg_match_all('#[\.\<\>][^\.\<\>]*#', trim(TuentiLib::getLine()), $scenes); //Get the different scenes with the type character.
	$order = array();
	$index = 0;
	$error = false;
	foreach($scenes[0] as $s){
		$type = $s{0};
		$text = substr($s, 1);
		switch($type){
			case ".":
				if(isset($order[$text])){
					if($order[$text][1] > $index or $order[$text][2] < $index){
						$error = "invalid";
					}
				}
				$order[$text] = array($index, false, false);
				break;
			case ">":
				if(isset($order[$text])){
					if($order[$text][1] === false and $order[$text][2] === false){
					}elseif($order[$text][1] !== false){
						$order[$text] = array($index, max($order[$text][1], $index), $order[$text][2]);
					}else{
						$order[$text] = array($index, $index, $order[$text][2]);
					}
				}else{
					$order[$text] = array($index, $index, false);
				}
				break;
			case "<":
				if(isset($order[$text])){
					if($order[$text][1] === false and $order[$text][2] === false){
					}elseif($order[$text][2] !== false){
						$order[$text] = array($index, $order[$text][1], min($order[$text][2], $index));
					}else{
						$order[$text] = array($index, $order[$text][1], $index);
					}
				}else{
					$order[$text] = array($index, false, $index);
				}
				break;
		}
		++$index;
	}
	if($error !== false){
		echo $error.PHP_EOL;
		continue;
	}
	$newOrder = array();
	$cnt = count($order) - 1;
	foreach($order as $text => $data){
		if($data[1] === false and $data[2] === false){
			$newOrder[$text] = $data[0] + 1;
		}elseif($data[1] !== false and $data[2] !== false and ($data[2] - $data[1]) == 2){
			$newOrder[$text] = $data[1] + 2;
		}elseif($data[1] !== false and $data[2] !== false and ($data[2] - $data[1]) > 2){
			$error = "valid";
		}elseif($data[1] !== false and $data[2] !== false and ($data[2] - $data[1]) < 2){
			$error = "invalid";
		}elseif($data[1] === false and $data[2] <= 1){
			$newOrder[$text] = 0;
		}elseif($data[1] === false){
			$error = "valid";
		}elseif($data[2] === false and $data[1] >= $cnt){
			$newOrder[$text] = $cnt + 1;
		}elseif($data[2] === false){
			$error = "valid";
		}
	}
	asort($newOrder);
	$ordered = array_flip($newOrder);	
	if($error === false){
		echo implode(",",$ordered).PHP_EOL;
	}else{
		echo $error.PHP_EOL;
	}
}