<?php
$projectDir = './demo/';

require_once($projectDir . 'board.php');
require_once($projectDir . 'game.php');
require_once($projectDir . 'page.php');
require_once($projectDir . 'piece.php');
require_once($projectDir . 'webDisplay.php');

$page = new page();
$page->execute();
