<?php

require_once(dirname(__DIR__) . '/interfaces/board.php');

class board implements boardInterface {
	private $board;
	private $winner;
	private $winLine;

	public function __construct() {
		$this->resetBoard();
	}

	public function getWinner() {
		return $this->winner;
	}

	public function getWinningLine() {
		return $this->winLine;
	}

	public function isGameOver() {
		return !empty($this->winner);
	}

	public function readLocation($x, $y) {
		if(!$this->board[$y][$x]) {
			return false;
		}
		$piece = new piece($this->board[$y][$x]);
		$piece->setLocation($x, $y);
		return $piece;
	}

	public function resetBoard() {
		$this->board = array(array(false,false,false),array(false,false,false),array(false,false,false));
		$this->winner = false;
		$this->winLine = '';
	}

	public function setPiece(pieceInterface $piece, $x, $y) {
		if($this->winner != false) {
			throw new Exception('Cannot place piece.  Game is already over.');
		}

		if($this->board[$y][$x] instanceof pieceInterface) {
			throw new Exception('Cannot overwrite existing piece!');
		}
		$piece->setLocation($x, $y);
		$this->board[$y][$x] = (string)$piece;
		$this->testBoard();
	}

	private function testBoard() {
		// Test left diagonal
		if($this->board[0][0] != false && $this->board[0][0] == $this->board[1][1] && $this->board[0][0] == $this->board[2][2]) {
			$this->winner = $this->board[1][1];
			$this->winLine = '\\';
			return;
		}

		// Test right diagonal
		if($this->board[0][2] != false && $this->board[0][2] == $this->board[1][1] && $this->board[2][0] == $this->board[0][2]) {
			$this->winner = $this->board[1][1];
			$this->winLine = '/';
			return;
		}

		for($i = 0; $i < 3; $i++) {
			// Test row
			if($this->board[0][$i] != false && $this->board[0][$i] == $this->board[1][$i] && $this->board[0][$i] == $this->board[2][$i]) {
				$this->winner = $this->board[0][$i];
				$this->winLine = "|{$i}";
				return;
			}

			// Test col
			if($this->board[$i][0] != false && $this->board[$i][0] == $this->board[$i][1] && $this->board[$i][0] == $this->board[$i][2]) {
				$this->winner = $this->board[$i][0];
				$this->winLine = "-{$i}";
				return;
			}
		}
	}
}