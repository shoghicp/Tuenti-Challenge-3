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

//Challenge 11 - The escape from Pixel Island

define("CHALLENGE", "11");
include("TuentiLib.php");

$cases = (int) TuentiLib::getLine();

//p => partir
// 2 1
// 3 4

class TuentiWeirdBitmap{
	private $str = "";
	private $changes = 0;
	public $bitmap = array("x","x","x","x");
	public function __construct($str = false){
		$this->str = $str;
		$i = 1;
		$this->bitmap = $this->loop($i, $this->bitmap, true);
	}
	
	private function loop(&$i, $pointer, $loop = false){
		$sq = 0;
		while($sq < 4){
			if(is_array($pointer[$sq])){				
				$pointer[$sq] = $this->loop($i, $pointer[$sq], false);
				++$sq;
				continue;
			}elseif($pointer[$sq] !== "x"){
				++$sq;
				continue;
			}
			if(($c = $this->str{$i}) === ""){
				return $pointer;
			}
			if($c === "p"){
				++$this->changes;
				$pointer[$sq] = array("x", "x", "x", "x");
			}else{
				++$this->changes;
				$pointer[$sq] = $c;
			}
			++$sq;
			++$i;
		}
		
		if($loop === true){
			do{
				$this->changes = 0;
				for($sq = 0; $sq < 4; ++$sq){
					if(is_array($pointer[$sq])){
						$pointer[$sq] = $this->loop($i, $pointer[$sq], false);
					}
				}
			}while($this->changes > 0);
		}
		
		return $pointer;
	}
	
	public function join(TuentiWeirdBitmap $map){
		$bitmap = new TuentiWeirdBitmap();
		$bitmap->bitmap = $this->joinLoop($this->bitmap, $map->bitmap);
		return $bitmap;
	}
	
	private function joinLoop($pointer1, $pointer2){
		$newPointer = array();
		for($j = 0; $j < 4; ++$j){
			if(is_array($pointer1[$j]) and is_array($pointer2[$j])){
				$newPointer[$j] = $this->joinLoop($pointer1[$j], $pointer2[$j]);
			}elseif($pointer1[$j] === "b" or $pointer2[$j] === "b"){
				$newPointer[$j] = "b";
			}elseif($pointer1[$j] === "w" and is_array($pointer2[$j])){
				$newPointer[$j] = $this->joinLoop(array("w","w","w","w"), $pointer2[$j]);
			}elseif($pointer2[$j] === "w" and is_array($pointer1[$j])){
				$newPointer[$j] = $this->joinLoop(array("w","w","w","w"), $pointer1[$j]);
			}elseif($pointer1[$j] === "w" and $pointer2[$j] === "w"){
				$newPointer[$j] = "w";
			}
		}
		return $newPointer;
	}
	
	public function toPNG($png, $width = 640, $height = 640){
		$img = imagecreatetruecolor($width, $height);
		$this->paintLoop($img, $this->bitmap, 0, $width, 0, $height);
		imagepng($img, $png, 9);
	}
	
	private function paintLoop($img, $pointer, $startX, $endX, $startY, $endY){
		if(is_array($pointer)){
			$halfX = (int) (($startX + $endX) / 2);
			$halfY = (int) (($startY + $endY) / 2);
			
			$this->paintLoop($img, $pointer[1], $startX, $halfX, $startY, $halfY);
			$this->paintLoop($img, $pointer[0], $halfX, $endX, $startY, $halfY);
			$this->paintLoop($img, $pointer[2], $startX, $halfX, $halfY, $endY);
			$this->paintLoop($img, $pointer[3], $halfX, $endX, $halfY, $endY);
		}else{
			imagefilledrectangle($img, $startX, $startY, $endX, $endY, ($pointer === "w" ? imagecolorallocate($img, 255, 255, 255) : imagecolorallocate($img, 0, 0, 0))); 
		}
	}
	
}


for($case = 1; $case <= $cases; ++$case){
	$convert = explode(" ", TuentiLib::getLine());
	$images = array();
	foreach($convert as $i => $str){
		$images[$i] = new TuentiWeirdBitmap($str);
		$images[$i]->toPNG("debug/11_$case-$i.png");
	}
	$all = array_shift($images);
	foreach($images as $im){
		$all = $all->join($im);
	}
	$all->toPNG("debug/11_$case.png");
}

/*
echo "OLA K ASE PROGRAMA O K ASE".PHP_EOL;
echo "Tengo contest tengo island tengo una sabrosura".PHP_EOL;
echo "Death is Not Defeat".PHP_EOL;
echo "If you cannot stay young at least stay immature".PHP_EOL;
echo "The enemys gate is down".PHP_EOL;
die();
*/
echo "You should not be reading this :)".PHP_EOL;
die();

//Catch the output so it is not submitted
while(true){
sleep(1);
}