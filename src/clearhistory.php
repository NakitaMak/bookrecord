<?php
require_once __DIR__ . '/classes/history.php';
$history = new History();

$history->clearHistory();

header("Location:index.php");