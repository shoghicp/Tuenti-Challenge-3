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

//This needs SQLite3 !!!!!!!!

//Challenge 5 - Dungeon Quest


define("CHALLENGE", "5");
include("TuentiLib.php");

$cases = (int) TuentiLib::getLine();

for($case = 1; $case <= $cases; ++$case){
	$db = new SQLite3(":memory:");
	$db->query("PRAGMA journal_mode = OFF;");
	$db->query("PRAGMA secure_delete = OFF;");
	$db->query("CREATE TABLE dist (i NUMERIC, j NUMERIC, distance NUMERIC);");
	$table = array(); //Test
	$d = explode(",",TuentiLib::getLine());
	$width = (int) $d[0];
	$height = (int) $d[1];
	$startPos = array_map("intval",explode(",",TuentiLib::getLine()));
	$table[$startPos[0].":".$startPos[1]] = "x";
	$moves = (int) TuentiLib::getLine();
	$gemCount = (int) TuentiLib::getLine(); //Not used
	$gems = array();
	$g = 0;
	foreach(explode("#", TuentiLib::getLine()) as $gem){
		if($gem == ""){
			continue;
		}
		$gems[$g] = array_map("intval", explode(",", $gem));
		$table[$gems[$g][0].":".$gems[$g][1]] = $gems[$g][2];
		++$g;
	}
	
	$tb = "";
	for($y = 0; $y < $height; ++$y){
		if($y > 0){
			$tb .= trim(str_repeat("|   ", $width)).PHP_EOL;
		}
		for($x = 0; $x < $width; ++$x){
			if($x > 0){
				$tb .= " - ";
			}
			if(isset($table[$x.":".$y])){
				$tb .= $table[$x.":".$y];
			}else{
				$tb .= "0";
			}
		}
		$tb .= PHP_EOL;
	}
	$tb .= PHP_EOL;
	

	$check = $gems;
	$cnt = 0;
	$query = "BEGIN TRANSACTION;";
	
	//All, gemCount^2. Done some optimizations so it is lowered a lot ;)
	foreach($gems as $i => $gem){
		unset($check[$i]);
		$d = abs($gem[0] - $startPos[0]) + abs($gem[1] - $startPos[1]);
		if($d > $moves){
			unset($gems[$i]);
			continue;
		}
		foreach($check as $j => $g){
			$d = abs($g[0] - $gem[0]) + abs($g[1] - $gem[1]);
			if($d > $moves){
				continue;
			}
			$query .= "INSERT INTO dist (i,j,distance) VALUES ($i,$j,$d);";
			++$cnt;
		}
	}
	$query .= "COMMIT;";
	$db->query($query);
	echo $cnt.PHP_EOL;
	TuentiLib::dump($tb, "table_".$case);
	
	$db->close();
}