<?php

require_once(dirname(__DIR__) . '/interfaces/page.php');

class page implements pageInterface {
	/** @var gameInterface $game */
	private $game;

	/** @var webDisplay $view */
	private $view;

	private $secret;

	public function __construct() {
		$this->view = new webDisplay();
		$this->secret = $this->getSecret();
		$this->loadGame();
	}

	private function getSecret() {
		$secret = file_get_contents("/tmp/secret.txt");
		if ($secret == FALSE) {
			$arr = str_split('TUENTI');
			shuffle($arr);
			$arr = array_slice($arr, 0, 4);
			$secret = implode('', $arr);
			file_put_contents("/tmp/secret.txt", $secret);
		}
		return $secret;
	}

	public function execute() {
		$errorMessage = '';
		if(isset($_GET['new']) && $_GET['new'] == 1) {
			$this->game->newGame();
			$this->saveGame();
			header('Location: ?');
			exit;
		}

		try {
			if(isset($_GET['x']) && isset($_GET['y'])) {
				$x = (int)$_GET['x'];
				$y = (int)$_GET['y'];
				$this->game->placeNextPiece($x, $y);
				$this->saveGame();
				header('Location: ?');
				exit;
			}
		} catch (Exception $e) {
			$errorMessage = $e->getMessage();
		}

		echo $this->view->buildHeader('Oh no! Tic Tac Tuenti!');
		echo $this->view->buildGame($this->game, $errorMessage);
		echo $this->view->buildFooter();
		$this->saveGame();
	}

	public function loadGame() {
		if (isset($_COOKIE['game'])) {
			list($gamestate, $h) = explode("|", $_COOKIE['game']);
			$hh = md5($gamestate . $this->secret);
			if ($h != $hh) {
				exit;
			}
			$this->game = unserialize(base64_decode($_COOKIE['game']));
		} else {
			$this->game = new game();
		}
	}

	public function saveGame() {
		$gamestate = base64_encode(serialize($this->game));
		$h = md5($gamestate . $this->secret);
		setcookie('game', $gamestate . "|" . $h, time() + (86400 * 7));
	}
}