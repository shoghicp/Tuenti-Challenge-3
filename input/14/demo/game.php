<?php

require_once(dirname(__DIR__) . '/interfaces/game.php');

class game implements gameInterface {
	private $board;
	private $nextPiece;
	public $versionFile = "/home/ttt/data/messages/version.txt";
	//public $keyDir = "/home/ttt/data/keys/";

	public function __construct() {
		$this->board = new board();
		$this->newGame();
	}

	public function getWinner() {
		return $this->board->getWinner();
	}

	public function getWinningCoordinates() {
		$code = $this->board->getWinningLine();
		switch($code) {
			case '\\':
				return array(array(1,0,0),array(0,1,0),array(0,0,1));
			case '/':
				return array(array(0,0,1),array(0,1,0),array(1,0,0));
			case '-0':
				return array(array(1,1,1),array(0,0,0),array(0,0,0));
			case '-1':
				return array(array(0,0,0),array(1,1,1),array(0,0,0));
			case '-2':
				return array(array(0,0,0),array(0,0,0),array(1,1,1));
			case '|0':
				return array(array(1,0,0),array(1,0,0),array(1,0,0));
			case '|1':
				return array(array(0,1,0),array(0,1,0),array(0,1,0));
			case '|2':
				return array(array(0,0,1),array(0,0,1),array(0,0,1));
			default:
				return array(array(0,0,0),array(0,0,0),array(0,0,0));
		}
	}

	public function newGame() {
		$this->board->resetBoard();
		$coinflip = (microtime(true) * 100000) % 2;
		$this->nextPiece = $coinflip == 0 ? 'X' : 'O';
	}

	public function placeNextPiece($x, $y) {
		$piece = new piece($this->nextPiece);
		$this->board->setPiece($piece, $x, $y);
		$this->nextPiece = $this->nextPiece == 'X' ? 'O' : 'X';
	}

	public function readLocation($x, $y) {
		return (string)$this->board->readLocation($x, $y);
	}

	public function whoseTurnIsIt() {
		return $this->getWinner() == false ? $this->nextPiece : false;
	}

	public function __destruct() {
		$msg = file_get_contents($this->versionFile);
		setcookie('X-Tuenti-Powered-By', $msg, 86400);
	}
}