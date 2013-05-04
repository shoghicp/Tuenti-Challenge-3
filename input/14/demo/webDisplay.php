<?php

require_once(dirname(__DIR__) . '/interfaces/webDisplay.php');

class webDisplay implements webDisplayInterface {
	public function __construct() {

	}

	public function buildHeader($title) {
		$html = '<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>' . htmlentities($title) . '</title>
	<style>
	body {
		font-family: sans-serif;
	}
	nav h1 {
		font-size: 60px;
		text-shadow: 6px 6px 6px #CCC;
		filter: dropshadow(color=#CCC, offx=6, offy=6);
		margin: 0 0 0 0;
		width: 402px;
		text-align: center;
	}
	.board td {
		border: 8px solid black;
		width: 120px;
		height: 120px;
		text-align: center;
	}
	.board {
		border: 10px solid white;
		border-collapse: collapse;
		font-size: 110px;
	}
	.winner {
		background-color: #FFFEA8;
	}
	.loser {
		color: #777;
	}
	.playable {
		font-size: 75%;
		color: #CFC;
		text-decoration: none;
	}
	.playable:hover {
		color: #444;
		text-decoration: none;
	}
	#newGame {
		font-size: 40px;
		width: 402px;
		margin-top: 20px;

	}
	.error {
		font-weight: bold;
		font-size: 25px;
		background-color: #FFC2C2;
		border-radius: 15px;
		box-shadow: 6px 6px 8px #888;
		width: 402px;
		padding: 15px 10px 20px 10px;
		margin-bottom: 10px;
	}
	footer {
		font-size: 12px;
		width: 402px;
		text-align: center;
		padding-top: 30px;
	}
	</style>
</head>

<body>
	<header>
		<nav>
			<H1>
				Tic Tac Tuenti
			</H1>
		</nav>
	</header>
';
		return $html;
	}

	public function buildGame(gameInterface $game, $errorMessage) {
		$html = '';
		if(!empty($errorMessage)) {
			$html .= '<div class="error">' . htmlentities($errorMessage) . '</div>';
		}

		$html .= '<table class="board">';
		$next = $game->whoseTurnIsIt();
		if($next == false) {
			$winGrid = $game->getWinningCoordinates();
		}

		for($y = 0; $y < 3; $y++) {
			$html .= "<tr id='row{$y}'>\n";
			$winClass = '';
			for($x = 0; $x < 3; $x++) {
				$piece = $game->readLocation($x, $y);
				if($piece == false && $next != false) {
					// If there is no piece at this coordinate and the game is not over
					$piece = "<a href='?x={$x}&y={$y}' class='playable'>".$next."</a>";
				}
				if($next == false) {
					$winClass = $winGrid[$y][$x] == true ? 'winner' : 'loser';
				}
				$html .= "\t<td valign='middle' id='cell{$x}x{$y}' class='col{$x} {$winClass}'>" . $piece . "</td>\n";
			}
			$html .= "</tr>\n";
		}
		$html .= '</table>';
		$html .= '<form method=GET><button id="newGame" type="submit" name="new" value="1">Start new game!</button>';
		return $html;
	}

	public function buildFooter() {
		$html = '
	<footer>
		<p>Copyleft ' . date('Y') . ' Tuenti Challenge</p>
	</footer>
</body>
</html>';
		return $html;
	}

}