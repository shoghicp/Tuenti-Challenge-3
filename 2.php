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

//Challenge 2 - Did you mean...?


define("CHALLENGE", "2");
include("TuentiLib.php");

@unlink("dictionaries/cached.db");
//Easy parsing, but I won't be tricked. ;)
TuentiLib::debug(__LINE__, "loading dicts");
while(trim(TuentiLib::getLine()) != "#Dictionary file"){}
$dict = trim(TuentiLib::getLine());
while(trim(TuentiLib::getLine()) != "#Suggestion numbers"){}
$cases = intval(trim(TuentiLib::getLine()));
while(trim(TuentiLib::getLine())!= "#Find the suggestions"){}
//Ready
$dict = fopen("dictionaries/".$dict, "r"); //Don't mess with this. ".$dict
if(!file_exists("dictionaries/cached.db")){
	$database = new SQLite3("dictionaries/cached.db");
	$database->query("PRAGMA journal_mode = OFF;");
	$database->query("PRAGMA secure_delete = OFF;");
	$database->query("CREATE TABLE dict (len NUMERIC, letters TEXT, offset NUMERIC);");
	$offset = 0;
	$count = 0;
	$query = "BEGIN TRANSACTION;";
	while(($line = fgets($dict)) !== false){
		$word = str_split(trim($line));
		sort($word);
		$word = implode($word);
		$query .= "INSERT INTO dict (len,letters,offset) VALUES (".strlen($word).",'".$word."',$offset);";
		if($count >= 64000){
			$count = 0;
			$database->query($query.";COMMIT;");
			$query = "BEGIN TRANSACTION;";
		}else{
			++$count;
		}
		$offset = ftell($dict);
	}
	if($count > 0){
		$database->query($query.";COMMIT;");
	}
}else{
	$database = new SQLite3("dictionaries/cached.db");
}

$prep2 = $database->prepare("SELECT offset FROM dict WHERE len = :len AND letters = :letters;");

TuentiLib::debug(__LINE__, "loaded");

while(($line = TuentiLib::getLine()) !== false){
	$line = trim($line);
	$word = str_split($line);
	sort($word);
	$word = implode($word);
	TuentiLib::debug(__LINE__, "word $line $word");
	$prep2->reset();
	$prep2->bindValue(":len", strlen($word), SQLITE3_INTEGER);
	$prep2->bindValue(":letters", $word, SQLITE3_TEXT);
	$result = $prep2->execute();
	$s = $line." -> ";
	if($result !== false and $result !== true){
		$l = array();
		while(($off = $result->fetchArray(SQLITE3_NUM)) !== false){
			$offset = $off[0];
			fseek($dict, $offset);
			$l[] = trim(fgets($dict));
		}
		sort($l);
		foreach($l as $w){
			if($w !== $line){
				$s .= $w." ";
			}
		}
	}
	echo trim($s).PHP_EOL;
}
$database->close();