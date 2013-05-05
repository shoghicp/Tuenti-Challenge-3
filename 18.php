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

//Challenge 18 - Energy will be infinities


define("CHALLENGE", "18");
include("TuentiLib.php");

ini_set("memory_limit", "-1");

class DirectedGraph{
	private $vertexes = array(), $edges = array();
	public function __construct($vertexCount, $edgeCount){
		$numbers = array();
		for($v = 0; $v < $vertexCount; ++$v){
			$this->vertexes[$v] = array($v => 100);
			$numbers[$v] = 0;
			$this->edges[$v] = array();
		}
		for($e = 0; $e < $edgeCount; ++$e){
			$edge = explode(" ", TuentiLib::getLine());
			$n1 = (int) $edge[0];
			$n2 = (int) $edge[1];
			++$numbers[$n1];
			++$numbers[$n2];
			$this->edges[$n1][$n2] = (100 + (int) $edge[2])/100;
		}
		foreach($numbers as $n => $c){
			if($c === 0){
				unset($this->vertexes[$n]);
				unset($this->edges[$n]);
			}
		}
	}
	
	public function findCycles(){
		$check = $this->vertexes;
		$check["reverse"] = array();
		$max = 75;
		while(($vertex = key($check)) !== null){
			if($vertex !== "reverse"){			
				$vPath = $check[$vertex];
				unset($check[$vertex]);
				if($this->searchTree($this->getConnected($vertex), $check, $vPath, $vPath[$vertex], 0, $max, true) === true){
					return true;
				}
			}else{
				foreach($check["reverse"] as $vertex => $vPath){
					unset($check["reverse"][$vertex]);
					if($this->searchTree($this->getConnected($vertex), $check, $vPath, $vPath[$vertex], 1, $max, false) === true){
						return true;
					}
				}
				break;
			}
		}
		return false;
	}
	
	private function searchTree($vertexes, &$check, $visited, $weight, $recursion, $max, $reverse = false){
		if(count($vertexes) === 0){
			return false;
		}elseif($recursion > $max){
			if($reverse === true){
				foreach($vertexes as $edgeVertex => $w){
					$visited[$edgeVertex] = $weight * $w;
					$check["reverse"][$edgeVertex] = $visited;
				}
			}
			return null;
		}
		foreach($vertexes as $edgeVertex => $w){
			$newWeight = $weight * $w;
			if(isset($visited[$edgeVertex])){
				if($visited[$edgeVertex] < $newWeight){
					return true;
				}elseif($reverse === true){
					$check["reverse"][$edgeVertex] = $visited;
				}				
				continue;
			}
			unset($check[$edgeVertex]);
			$visited[$edgeVertex] = $newWeight;
			$search = $this->searchTree($this->getConnected($edgeVertex), $check, $visited, $newWeight, $recursion + 1, $max, $reverse);
			if($search === true){
				return true;
			}elseif($search === null){
				return null;
			}
		}
		return false;
	}
	
	public function getConnected($vertex){
		return $this->edges[$vertex];
	}
}

$cases = (int) TuentiLib::getLine();


for($case = 0; $case < $cases; ++$case){
	$graph = new DirectedGraph((int) TuentiLib::getLine(), (int) TuentiLib::getLine());
	if($graph->findCycles() === true){
		echo "True".PHP_EOL;
	}else{
		echo "False".PHP_EOL;
	}
}