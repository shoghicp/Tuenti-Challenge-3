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

//Challenge 7 - Boozzle


define("CHALLENGE", "7");
include("TuentiLib.php");

ini_set("memory_limit", -1); //??

$cases = (int) TuentiLib::getLine();
$raw = explode("\n", file_get_contents("dictionaries/boozzle-dict.txt"));
$words = array();
foreach($raw as $w){
	if($w !== ""){
		$words[$w] = strlen($w);
	}
}

class Board{
	private $table;
	public function __construct($rows, $columns){
		for($row = 0; $row < $rows; ++$row){
			$this->table[$row] = array();
			foreach(explode(" ",TuentiLib::getLine()) as $i => $val){
				$this->table[$row][$i] = array($val{0}, (int) $val{1}, (int) $val{2});
			}
		}
	}
	
	public function get($row, $column){
		if(isset($this->table[$row][$column])){
			return $this->table[$row][$column];
		}
		return false;
	}
	
	public function getAround($row, $column, $lastRow, $lastCol){
		$results = array();
		for($crow = $row - 1; $crow <= ($row + 1); ++$crow){
			for($ccolumn = $column - 1; $ccolumn <= ($column + 1); ++$ccolumn){
				if(!($crow === $lastRow and $ccolumn === $lastCol) and !($crow === $row and $ccolumn === $column) and isset($this->table[$crow][$ccolumn])){
					$results[$crow.".".$ccolumn] = $this->table[$crow][$ccolumn];
				}
			}
		}
		return $results;
	}
}


function searchTree(Board $table, $lookup, $row, $col, $visited, $path, &$found, $lastCol, $lastRow, $max){
	global $scores, $words;
	$len = strlen($path) + 1;
	if($len > $max){
		return;
	}
	foreach($table->getAround($row, $col, $lastRow, $lastCol) as $index => $data){
		if(isset($visited[$index])){
			continue;
		}
		$curr = explode(".", $index);
		$str = $path . $data[0];
		
		if(isset($lookup[$data[0]])){
			$visited[$index] = $data;
			if(isset($words[$str])){
				$points = 0;
				$whole = 1;
				foreach($visited as $i => $d){
					if($d[1] === 1){
						$points += $scores[$d[0]] * $d[2];
					}else{
						$points += $scores[$d[0]];
						$whole = max($d[2], $whole);
					}						
				}
				$points *= $whole;
				$points += strlen($str);
				if(isset($found[$str])){
					$found[$str] = max($found[$str], $points);
				}else{
					$found[$str] = $points;
				}
			}
			searchTree($table, $lookup[$data[0]], (int) $curr[0], (int) $curr[1], $visited, $str, $found, $col, $row, $max);
		}
	}
}

function optimize($duration, $check, $points){
	global $found, $calls;
	++$calls;
	$maxTotal = $points;
	foreach($check as $w => $p){
		$len = strlen($w);
		if(($len + 1) > $duration){
			unset($check[$w]);
			continue;
		}
		$total = array($points[0] + $p, $points[1]);
		$total[1][] = $w;
		$time = $duration - $len - 1;
		if($time <= 2){
			if($time >= 0 and $total[0] > $maxTotal[0]){
				$maxTotal = $total;
			}
			continue;
		}
		$check2 = $check;
		unset($check2[$w]);
		$total = optimize($time, $check2, $total);
		if($total[0] > $maxTotal[0]){
			$maxTotal = $total;
		}
		unset($check[$w]);
	}
	return $maxTotal;
}

for($case = 1; $case <= $cases; ++$case){
	//echo "274".PHP_EOL;continue;
	$scores = json_decode(str_replace("'", '"', TuentiLib::getLine()), true); //Muahahaha
	$duration = (int) TuentiLib::getLine();
	$rows = (int) TuentiLib::getLine();
	$columns = (int) TuentiLib::getLine();
	$table = new Board($rows, $columns);
	$lookup = array();
	foreach($words as $w => $len){
		if($len < $duration){//Delete unused
			$curr =& $lookup;
			for($i = 0; $i < $len; ++$i){
				if(!isset($curr[$w{$i}])){
					$curr[$w{$i}] = array();
				}
				$curr =& $curr[$w{$i}];
			}
		}
	}
	$max = min($duration, 15 - 1); //15 = max length
	$results = array();
	$found = array();
	for($row = 0; $row < $rows; ++$row){
		for($col = 0; $col < $columns; ++$col){
			$v = array($row.".".$col => $table->get($row, $col));
			searchTree($table, $lookup[$v[$row.".".$col][0]], $row, $col, $v, $v[$row.".".$col][0], $found, $col, $row, $max);
		}
	}

	asort($found);
	
	$search = $found;
	
	$check = $search;
	$pairs = new SQLite3(":memory:");
	$pairs->query("CREATE TABLE pairs (word1 TEXT, word2 TEXT, points NUMERIC, len NUMERIC);");
	foreach($found as $w => $p){
		unset($check[$w]);
		foreach($check as $w2 => $p2){
			$pairs->query("INSERT INTO pairs (word1,word2,points,len) VALUES ('$w','$w2',".($p + $p2).",".(strlen($w) + strlen($w2) + 2).");");
		}
	}
	
	$search = array();
	foreach($found as $w => $points){
		$len = strlen($w);
		if(!isset($search[$len])){
			$search[$len] = array();
		}
		$search[$len][$w] = $points;
	}
	foreach($search as $len => $data){
		arsort($search[$len]);
	}
	krsort($search);
	$total = 0;
	while(true){
		$best = array();
		foreach($search as $len => $data){
			$maxp = 0;
			foreach($data as $w => $p){
				if($maxp > $p){
					break;
				}
				$maxp = $p;
				if(isset($best[$p]) and strlen($best[$p]) > strlen($w)){
					$best[$p] = $w;
				}elseif(!isset($best[$p])){
					$best[$p] = $w;
				}
			}
		}
		krsort($best);
		if(count($best) > 0){
			$w = array_shift($best);
			$len = strlen($w);
			if(($len + 1) <= $duration){
				$total += $found[$w];
				$duration -= $len + 1;
			}
			unset($found[$w]);
			unset($search[$len][$w]);
			if(count($search[$len]) === 0){
				unset($search[$len]);
			}
		}
		
		ksort($search);
		reset($search);
		if($duration <= 0 or count($search) === 0 or (key($search) + 1) > $duration){
			break;
		}
		krsort($search);
		
		if($duration < 20){
			$calls = 0;
			$result = optimize($duration, $found, array($total, array(), 0));
			foreach($result[1] as $w){
				$duration -= strlen($w) + 1;
			}
			$total = $result[0];
			break;
		}
	}
	echo $calls ." ". $total.PHP_EOL;
}