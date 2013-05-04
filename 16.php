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

//Challenge 16 - Legacy code


define("CHALLENGE", "16");
include("TuentiLib.php");

//This code got so complex that I couldn't understand it, so I had to rewrite it xD

ini_set("memory_limit", "-1");

$script = <<<'SCRIPT'
start,#:%,R,state0
state0,0:0,R,state0
state0,1:1,R,state0
state0,#:#,S,state1
state1,#:#,L,state1
state1,$:$,L,state1
state1,1:0,R,state2
state1,0:1,L,state1
state2,1:1,R,state2
state2,0:0,R,state2
state2,#:#,L,state3
state2,$:$,L,state3
state3,0:0,L,state3
state3,1:1,R,state4
state3,%:%,R,state5
state4,0:0,R,state4
state4,1:1,R,state4
state5,1:1,R,state5
state5,0:0,R,state5
state5,#:#,S,state6
state5,$:$,S,state6
state4,#:#,S,state7
state4,$:$,S,state7
state7,#:$,R,state8
state7,$:$,R,state9
state8,1:1,R,state8
state8,0:0,R,state8
state8,#:#,R,state8
state8,_:_,L,state10
state10,#:$,L,state11
state11,1:1,L,state11
state11,0:0,L,state11
state11,#:#,L,state11
state11,$:$,R,state9
state9,1:_,R,state12
state9,0:_,R,state13
state9,#:_,R,state14
state9,$:$,S,state15
state12,1:1,R,state12
state12,0:0,R,state12
state12,#:#,R,state12
state12,$:$,R,state12
state12,_:1,L,state16
state16,1:1,L,state16
state16,0:0,L,state16
state16,#:#,L,state16
state16,$:$,L,state16
state16,_:1,R,state9
state13,1:1,R,state13
state13,0:0,R,state13
state13,#:#,R,state13
state13,$:$,R,state13
state13,_:0,L,state17
state17,1:1,L,state17
state17,0:0,L,state17
state17,#:#,L,state17
state17,$:$,L,state17
state17,_:0,R,state9
state14,1:1,R,state14
state14,0:0,R,state14
state14,#:#,R,state14
state14,$:$,R,state14
state14,_:#,L,state18
state18,1:1,L,state18
state18,0:0,L,state18
state18,#:#,L,state18
state18,$:$,L,state18
state18,_:#,R,state9
state15,1:1,R,state15
state15,0:0,R,state15
state15,#:#,R,state15
state15,$:$,R,state15
state15,_:#,L,state19
state19,1:1,L,state19
state19,0:0,L,state19
state19,#:#,L,state19
state19,$:$,L,state20
state20,1:1,L,state20
state20,0:0,L,state20
state20,#:#,L,state20
state20,$:$,S,state1
state6,1:1,R,state6
state6,0:0,R,state6
state6,#:#,R,state6
state6,$:#,R,state6
state6,_:_,L,state21
state21,#:#,L,state22
state22,1:1,L,state22
state22,0:0,L,state22
state22,#:#,L,state23
state22,%:#,S,end
state23,1:1,R,state23
state23,0:0,R,state23
state23,#:#,R,state23
state23,_:_,L,state24
state24,#:#,L,state25
state25,0:0,L,state25
state25,1:1,R,state26
state25,#:#,R,state27
state26,0:0,R,state26
state26,1:1,R,state26
state27,1:1,R,state27
state27,0:0,R,state27
state27,#:_,L,state28
state26,#:#,L,state29
state29,1:0,L,state30
state29,0:1,L,state29
state30,1:1,L,state30
state30,0:0,L,state30
state30,#:#,L,state31
state31,1:0,L,state31
state31,0:1,R,state32
state32,1:1,R,state32
state32,0:0,R,state32
state32,#:#,R,state32
state32,_:_,L,state21
state28,1:_,L,state28
state28,0:_,L,state28
state28,#:#,S,state21
SCRIPT;

define("STATE_START", -1);
define("STATE_END", -2);
$charlist = array(
	"0" => 0,
	"1" => 1,
	"#" => 2,
	'$' => 3,
	"%" => 4,
	"_" => 5,
);
$numlist = array(
	"0",
	"1",
	"#",
	'$',
	"%",
	"_",
);

$script = explode("\n", $script);
$program = array();

foreach($script as $instruction){
	$instruction = explode(",", trim($instruction));
	$state = $instruction[0] === "start" ? STATE_START:intval(substr($instruction[0], 5));
	$character = explode(":", $instruction[1]);
	$character_to_write = $charlist[$character[1]];
	$character = $charlist[$character[0]];
	$movement = $instruction[2] === "R" ? 1:($instruction[2] === "L" ? 2:0);
	$new_state = $instruction[3] === "end" ? STATE_END:intval(substr($instruction[3], 5));
	if(!isset($program[$state])){
		$program[$state] = array();
	}
	//$program[$state][$character] = $character_to_write." ".$movement." ".$new_state;
	$program[$state][$character] = $character_to_write | ($movement << 3) | ($new_state << 5);
}
ksort($program);

$skip = array( //optimization, fast skips ;)
	8 => array(1, 5),
	11 => array(-1, 3),
	12 => array(1, 5),
	13 => array(1, 5),
	14 => array(1, 5),
	15 => array(1, 5),
	16 => array(-1, 5),
	17 => array(-1, 5),
	18 => array(-1, 5),
	19 => array(-1, 3),
	20 => array(-1, 3),
	23 => array(1, 5),
	26 => array(1, 2),
	27 => array(1, 2),
	30 => array(-1, 2),
	32 => array(1, 5),
);

$alias = array( //more optimizations, direct bypass (alias) ;)
	21 => array(-1, 22),
	24 => array(-1, 25),
);

$program[STATE_END] = STATE_END;

while(($tape = TuentiLib::getLine()) !== false){
	$tape = str_split($tape, 1);
	foreach($tape as $pointer => $char){
		$tape[$pointer] = $charlist[$char];
	}
	$state =& $program[STATE_START];
	$statePointer = STATE_START;
	$pointer = 0;
	$character =& $tape[$pointer];
	while(true){
		if($statePointer === 22){ //Special case optimization
			while($tape[$pointer] !== 2){
				--$pointer;
				if($tape[$pointer] === 4){
					break;
				}
			}
			$character =& $tape[$pointer];
			$s = $program[22][$character];
			$character = $s & 0x07;
			$statePointer = $s >> 5;
			if(($m = $s >> 3 & 0x03) > 0){
				$pointer += ($m & 0x01) - ($m >> 1);
				$character =& $tape[$pointer];
			}
			if($statePointer === STATE_END){ //END
				break;
			}
			$state =& $program[$statePointer];
		}elseif($statePointer === 25){ //Special case optimization
			while($tape[$pointer] === 0){
				--$pointer;
			}
			$s = $program[25][$tape[$pointer]];
			$statePointer = $s >> 5;
			++$pointer;
			$character =& $tape[$pointer];
			$state =& $program[$statePointer];
		}elseif($statePointer === 29){ //Special case optimization
			while($tape[$pointer] === 0){
				$tape[$pointer] = 1;
				--$pointer;
			}
			$s = $program[29][1];
			$tape[$pointer] = 0;
			$statePointer = $s >> 5;
			--$pointer;
			$character =& $tape[$pointer];
			$state =& $program[$statePointer];
		}elseif($statePointer === 31){ //Special case optimization
			while($tape[$pointer] === 1){
				$tape[$pointer] = 0;
				--$pointer;
			}
			$s = $program[31][0];
			$tape[$pointer] = 1;
			$statePointer = $s >> 5;
			++$pointer;
			$character =& $tape[$pointer];
			$state =& $program[$statePointer];
		}elseif(isset($alias[$statePointer])){
			$pointer += $alias[$statePointer][0];
			if(!isset($tape[$pointer])){
				$tape[$pointer] = 5;
			}
			$character =& $tape[$pointer];
			$statePointer = $alias[$statePointer][1];
			$state =& $program[$statePointer];
		}elseif(isset($skip[$statePointer])){
			$p =& $skip[$statePointer];
			while($tape[$pointer] !== $p[1]){
				$pointer += $p[0];
				if(!isset($tape[$pointer])){
					$tape[$pointer] = 5;
				}
			}
			$character =& $tape[$pointer];
			$s = $program[$statePointer][$character];
			$character = $s & 0x07;
			$statePointer = $s >> 5;
			if(($m = $s >> 3 & 0x03) > 0){
				$pointer += ($m & 0x01) - ($m >> 1);
				if(!isset($tape[$pointer])){
					$tape[$pointer] = 5;
				}
				$character =& $tape[$pointer];
			}
			$state =& $program[$statePointer];
		}else{
			$instruction = $state[$character];
			$character = $instruction & 0x07;
			$statePointer = $instruction >> 5;
			if(($m = $instruction >> 3 & 0x03) > 0){
				$pointer += ($m & 0x01) - ($m >> 1);
				if(!isset($tape[$pointer])){
					$tape[$pointer] = 5;
				}
				$character =& $tape[$pointer];
			}
			$state =& $program[$statePointer];
		}
		++$states[$statePointer];
	}
	
	$result = "";
	foreach($tape as $num){
		$result .= $numlist[$num];
	}

	echo trim($result, "_").PHP_EOL;

}