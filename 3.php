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
	$safe = array();
	$order = array();
	$index = 0;
	$error = false;
	if($scenes[0]{0} === "<"){ //No start
		$error = "invalid";
	}
	foreach($scenes[0] as $s){
		$type = $s{0};
		$text = substr($s, 1);
		switch($type){
			case ".":
				if(isset($order[$text])){
					if($order[$text][0] > $index or $order[$text][1] < $index){
						$error = "invalid";
						break;
					}
					unset($order[$text]);
				}elseif(isset($safe[$text])){
					$error = "invalid";
					break;
				}
				$safe[$text] = $index;
				break;
			case ">":
				if(isset($safe[$text])){
					$error = "invalid";
					break;
				}
				if(isset($order[$text])){
					$order[$text] = array(max($index, $order[$text][0]), $order[$text][1]);
				}else{
					$order[$text] = array($index, 2147483647);
				}
				break;
			case "<":
				if(isset($safe[$text])){ //Is valid ;)
					break;
				}
				if(isset($order[$text])){
					$order[$text] = array($order[$text][0], min($index - 2, $order[$text][1]));
					if($order[$text][0] === $order[$text][1]){
						/*$error = "invalid";
						break;*/
					}
				}else{
					$order[$text] = array(-1, $index - 2);
				}
				break;
		}
		++$index;
		if($error !== false){
			break;
		}
	}
	if($error === false and $type === ">"){ //No end
		$error = "invalid";
	}
	$min = count($safe) + count($order);
	foreach($order as $text => $data){
		$order[$text] = range($data[0], min($min, $data[1]));
	}
	$changes = count($order);
	while($changes > 0 and $error === false){
		$changes = 0;
		foreach($order as $text => $range){
			foreach($range as $i => $n){
				if(in_array($n, $safe, true)){
					unset($order[$text][$i]);
					++$changes;
				}
			}
			if(count($order[$text]) === 1){
				$index = array_pop($order[$text]);
				$safe[$text] = $index;
				unset($order[$text]);
				++$changes;
			}elseif(count($order[$text]) === 0){
				$error = "valid";
				break;
			}
		}
	}
	if($error !== false){
		echo $error.PHP_EOL;
		continue;
	}
	asort($safe);
	echo implode(",", array_flip($safe)).PHP_EOL;
	
}