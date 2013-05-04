<?php

require_once(dirname(__DIR__) . '/interfaces/piece.php');

class piece implements pieceInterface {
	private $x;
	private $y;
	private $type;

	public function __construct($type) {
		if($type !== 'X' && $type !== 'O') {
			throw new Exception ('Invalid piece type.  Please only play X or O.  Tried to place ' . $type);
		}
		$this->type = $type;
	}

	public function __toString() {
		return $this->getPieceType();
	}

	public function getLocationX() {
		return $this->x;
	}

	public function getLocationY() {
		return $this->y;
	}

	public function getPieceType() {
		return $this->type;
	}

	public function setLocation($x, $y) {
		$error = '';
		$also = '';
		if($x > 3 || $x < 0) {
			$error .= 'X coordinate is out of bounds! ';
			$also = 'also ';
		}

		if($y > 3 || $y < 0) {
			$error .= 'Y coordinate is ' . $also . 'out of bounds! ';
		}

		if(!empty($error)) {
			throw new Exception($error);
		}
		$this->x = $x;
		$this->y = $y;
	}
}