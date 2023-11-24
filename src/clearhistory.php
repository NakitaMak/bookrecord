<?php
require_once __DIR__ . '/classes/history.php';
$history = new History();

$history->clearHistory();

// ホームページを再読み込み
header("Location:index.php");

?>