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

// This needs SQLite3!

//Challenge 7 - Boozzle


define("CHALLENGE", "7");
include("TuentiLib.php");

$cases = (int) TuentiLib::getLine();
$raw = explode("\n", file_get_contents("dictionaries/boozzle-dict.txt"));
$words = array();
foreach($raw as $w){
	if($w !== ""){
		$words[$w] = strlen($w);
	}
}

for($case = 1; $case <= $cases; ++$case){
	$scores = json_decode(str_replace("'", '"', TuentiLib::getLine()), true); //Muahahaha
	$duration = (int) TuentiLib::getLine();
	$rows = (int) TuentiLib::getLine();
	$columns = (int) TuentiLib::getLine();
	$table = array();
	$multiplier = array();
	for($row = 0; $row < $rows; ++$row){
		$table[$row] = array();
		$charMultiplier[$row] = array();
		$wordMultiplier[$row] = array();
		foreach(explode(" ",TuentiLib::getLine()) as $i => $val){
			$table[$row][$i] = $val{0};
			$multiplier[$row][$i] = array((int) $val{1}, (int) $val{2});
		}
	}
	$wordDict = array();
	foreach($words as $w => $len){
		if($len < $duration){//Delete unused
			$wordDict[$w] = $len;
		}
	}
	arsort($wordDict);
	
	$lookup = new SQLite3(":memory:");
	$lookup->query("PRAGMA journal_mode = OFF;");
	$lookup->query("PRAGMA secure_delete = OFF;");
	$lookup->query("CREATE TABLE strings (line TEXT, type NUMERIC, start NUMERIC,len NUMERIC);");
	//Type 0 => Horizontal .--> (start nRow)
	//Type 1 => Horizontal .<--
	//Type 2 => Vertical .--> (start nColumn)
	//Type 3 => Vertical .<--
	//Type 4 => Diagonal1 .-->
	//Type 5 => Diagonal1 .<--
	//Type 6 => Diagonal2 .-->
	//Type 7 => Diagonal2 .<--
	
	
	//I'll do this in multiple loops, too lazy to think about a way to fo it in one.
	$query = "BEGIN TRANSACTION;";
	$horizontal = array();
	$vertical = array();
	$diagonal1 = array();
	$diagonal2 = array();
	for($row = 0; $row < $rows; ++$row){
		$str = implode($table[$row]);
		$horizontal[$row] = array($str, strrev($str));
		$len = strlen($str);
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$horizontal[$row][0]."',0,$row,$len);";
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$horizontal[$row][1]."',1,$row,$len);";
	}
	
	for($column = 0; $column < $columns; ++$column){
		$str = "";		
		for($row = 0; $row < $rows; ++$row){
			$str .= $table[$row][$column];
		}
		$vertical[$column] = array($str, strrev($str));
		$len = strlen($str);
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$vertical[$column][0]."',2,$column,$len);";
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$vertical[$column][1]."',3,$column,$len);";
	}
	$total = $rows + $columns - 1;
	for($index = 0; $index < $total; ++$index){
		$diagonal1[$index] = array();
		$column = min($index, $columns - 1);
		$row = max($index + 1, $columns) - $columns;
		$diagonal1[$index][2] = array($column, $row);
		$diagonal1[$index][3] = array();
		$str = "";
		$cnt = 0;
		while(true){
			if(!isset($table[$row][$column])){
				break;
			}
			$str .= $table[$row][$column];
			$diagonal1[$index][3][$cnt] = $multiplier[$row][$column];
			++$cnt;
			--$row;
			--$column;
		}
		$diagonal1[$index][0] = $str;
		$diagonal1[$index][1] = strrev($str);
		$len = strlen($str);
		if($len === 1){
			unset($diagonal1[$index]);
			continue;
		}
		echo $diagonal1[$index][0].PHP_EOL;
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$diagonal1[$index][0]."',4,$index,$len);";
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$diagonal1[$index][1]."',5,$index,$len);";
	}
	for($index = 0; $index < $total; ++$index){
		$diagonal2[$index] = array();
		$column = min($index, $rows - 1);
		$row = max($index + 1, $rows) - $rows;
		$diagonal2[$index][2] = array($column, $row);
		$diagonal2[$index][3] = array();
		echo $column," ", $row, PHP_EOL;
		$str = "";
		$cnt = 0;
		while(true){
			if(!isset($table[$row][$column])){
				break;
			}
			$str .= $table[$row][$column];
			$diagonal2[$index][3][$cnt] = $multiplier[$row][$column];
			++$cnt;
			--$row;
			--$column;
		}
		$diagonal2[$index][0] = $str;
		$diagonal2[$index][1] = strrev($str);
		$len = strlen($str);
		if($len === 1){
			unset($diagonal2[$index]);
			continue;
		}
		echo $diagonal2[$index][0].PHP_EOL;
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$diagonal2[$index][0]."',6,$index,$len);";
		$query .= "INSERT INTO strings (line,type,start,len) VALUES ('".$diagonal2[$index][1]."',7,$index,$len);";
	}
	$query .= "COMMIT;";
	$lookup->query($query);
	$max = $lookup->query("SELECT MAX(len) FROM strings;");
	$max = $max->fetchArray(SQLITE3_NUM)[0];
	$resultsPoints = array();
	$resultsLen = array();
	foreach($wordDict as $word => $len){
		if($len > $max){
			continue;
		}
		$result = $lookup->query("SELECT type,start FROM strings WHERE line LIKE '%".$word."%';");
		if($result instanceof SQLite3Result){
			$maxResult = 0;
			while(($data = $result->fetchArray(SQLITE3_NUM)) !== false){				
				$type = $data[0];
				$index = $data[1];
				$points = 0;
				$whole = 1;
				switch($type){
					case 0:
					case 1:
						$target = $horizontal[$index];
						if($type === 1){
							$offset = strpos($target[1], $word);
							$w = strrev(substr($target[1], $offset, $len));
							$offset = strpos($target[0], $w);
						}else{
							$offset = strpos($target[0], $word);
							$w = $word;
						}
						for($i = 0; $i < $len; ++$i){
							$m = $multiplier[$index][$i + $offset];
							if($m[0] === 1){
								$points += $scores[$w{$i}] * $m[1];
							}else{
								$points += $scores[$w{$i}];
								$whole = max($m[1], $whole);
							}
						}
						$points *= $whole;
						break;
					case 2:
					case 3:
						$target = $vertical[$index];
						if($type === 3){
							$offset = strpos($target[1], $word);
							$w = strrev(substr($target[1], $offset, $len));
							$offset = strpos($target[0], $w);
						}else{
							$offset = strpos($target[0], $word);
							$w = $word;
						}
						for($i = 0; $i < $len; ++$i){
							$m = $multiplier[$i + $offset][$index];
							if($m[0] === 1){
								$points += $scores[$w{$i}] * $m[1];
							}else{
								$points += $scores[$w{$i}];
								$whole = max($m[1], $whole);
							}
						}
						$points *= $whole;
						break;
					case 4:
					case 5:
						$target = $diagonal1[$index];
						if($type === 5){
							$offset = strpos($target[1], $word);
							$w = strrev(substr($target[1], $offset, $len));
							$offset = strpos($target[0], $w);
						}else{
							$offset = strpos($target[0], $word);
							$w = $word;
						}
						for($i = 0; $i < $len; ++$i){
							$m = $target[3][$i + $offset];
							if($m[0] === 1){
								$points += $scores[$w{$i}] * $m[1];
							}else{
								$points += $scores[$w{$i}];
								$whole = max($m[1], $whole);
							}
						}
						$points *= $whole;
						break;
				}
				$maxResult = max($points, $maxResult);
			}
			$result->finalize();
			if($maxResult > 0){
				echo $word." $type FOUND $maxResult".PHP_EOL;
				$resultsPoints[$word] = $maxResult;
				$resultsLen[$word] = $len + 1; //Add Submit
			}
		}
	}
	arsort($resultsPoints);
	asort($resultsLen);
	reset($resultsPoints);
	reset($resultsLen);
	echo key($resultsPoints)." ".current($resultsPoints);
	$lookup->close();
	echo PHP_EOL;
}