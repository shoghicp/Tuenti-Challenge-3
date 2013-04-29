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

//Challenge 1 - Bitcoin to the future


define("CHALLENGE", "1");
include("TuentiLib.php");

$cases = (int) trim(TuentiLib::getLine());

for($case = 1; $case <= $cases; ++$case){
	TuentiLib::debug(__LINE__, "case $case");
	$budget = (int) trim(TuentiLib::getLine());
	$bitCoins = 0;
	$exchangeRates = array_map("intval", explode(" ", trim(TuentiLib::getLine())));
	$count = count($exchangeRates);
	$limit = $count - 1;
	$lastBuy = 0;
	$offset = 0;
	for($offset = 0; $offset < $count; ++$offset){
		$current = $exchangeRates[$offset];
		if(($offset === 0 or $exchangeRates[$offset - 1] >= $current) and ($offset !== $limit and $exchangeRates[$offset + 1] >= $current)){
			//Min, buy
			if($current <= $budget){
				$buy = (int) ($budget - ($budget % $current));
				$bitCoins += (int) ($buy / $current);
				$budget -= $buy;
				TuentiLib::debug(__LINE__, "buy $current $buy");
			}
		}elseif(($offset !== 0 and $exchangeRates[$offset - 1] <= $current) and ($offset === $limit or $exchangeRates[$offset + 1] <= $current)){
			//Max, sell
			$sell = (int) ($bitCoins * $current);
			$budget += $sell;
			$bitCoins = 0;
			TuentiLib::debug(__LINE__, "sell $current $sell");
		}
		TuentiLib::debug(__LINE__, "bitcoins $bitCoins");		
	}
	echo $budget.PHP_EOL;
}