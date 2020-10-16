<?php

require 'include/db.php';

if (!isset($_GET['from'])) exit;
if (!isset($_GET['to'])) exit;

$from = $_GET['from'];
$froma = $from - 1;
$to = $_GET['to'];
$diff = $from-$to;

$res = R::getAll('SELECT * FROM postdate ORDER BY date DESC LIMIT '.strval($froma).', '.strval($to).'');

?>  