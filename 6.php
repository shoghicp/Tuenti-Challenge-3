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

//Challenge 6 - Ice Cave


define("CHALLENGE", "6");
include("TuentiLib.php");


class Map{
	private $map, $width, $height, $startPos, $endPos;
	public function __construct($width, $height){
		$this->width = (int) $width;
		$this->height = (int) $height;
		$this->map = "";
		for($h = 0; $h < $this->height; ++$h){
			$line = TuentiLib::getLine();
			$line = str_replace("\xc2\xb7", ".", $line); //Clean UTF-8 chars
			$start = strpos($line, "X");
			$end = strpos($line, "O");
			if($start !== false){
				$this->startPos = array($start, $h);
				$line{$start} = ".";
			}
			/*if($end !== false){
				$this->endPos = array($end, $h);
				$line{$end} = ".";
			}*/
			$this->map .= $line;
		}
	}
	
	public function getFree($x, $y){
		$borders = array();
		if($this->getPos($x + 1, $y) !== "#"){
			$borders[] = array(1, 0);
		}
		if($this->getPos($x - 1, $y) !== "#"){
			$borders[] = array(-1, 0);
		}
		if($this->getPos($x, $y + 1) !== "#"){
			$borders[] = array(0, 1);
		}
		if($this->getPos($x, $y - 1) !== "#"){
			$borders[] = array(0, -1);
		}
		return $borders;
	}
	
	public function getPos($x, $y){
		return $this->map{($y * $this->width) + $x};
	}
	
	public function getStart(){
		return ($this->startPos);
	}
	
	/*public function getEnd(){
		return ($this->endPos);
	}*/
}


function getOptimal($startPos, &$optimal, $visited, $time, Map $map, $respawn, $speed){
	foreach($map->getFree($startPos[0], $startPos[1]) as $i => $free){
		$pos = $startPos;
		$advance = 0;
		$total = $time;
		$next = array($pos[0] + $free[0], $pos[1] + $free[1]);
		if(isset($visited[$next[0].".".$next[1]])){ //OOPS! loop
			continue;
		}
		while(true){			
			$t = $map->getPos($next[0], $next[1]);
			if($t === "#"){
				if(isset($visited[$next[0].".".$next[1]])){ //OOPS! more loop
					break;
				}
				$total += ($advance / $speed) + $respawn;
				getOptimal($pos, $optimal, $visited, $total, $map, $respawn, $speed);
				break;
			}elseif($t === "O"){
				++$advance;
				$total += $advance / $speed;
				$optimal = min($optimal, $total);
				break;
			}
			++$advance;
			$pos = $next;
			echo $pos[0]." ".$pos[1].PHP_EOL;
			$visited[$pos[0].".".$pos[1]] = true;
			$next = array($pos[0] + $free[0], $pos[1] + $free[1]);
		}
	}
}

$cases = (int) TuentiLib::getLine();

for($case = 1; $case <= $cases; ++$case){
	$c = array_map("intval", explode(" ", TuentiLib::getLine()));
	$width = $c[0];
	$height = $c[1];
	$speed = $c[2];
	$respawn = $c[3];
	$time = $respawn;	
	$map = new Map($width, $height);
	$optimal = PHP_INT_MAX;
	getOptimal($map->getStart(), $optimal, array($map->getStart()[0].".".$map->getStart()[1] => true), $time, $map, $respawn, $speed);
	echo round($optimal).PHP_EOL;
}