<?php

require 'include/db.php';

if (!isset($_GET['from'])) exit;
if (!isset($_GET['to'])) exit;

$from = $_GET['from'];
$froma = $from - 1;
$to = $_GET['to'];
$diff = $from-$to;

$all = R::getAll('SELECT * FROM postdate ORDER BY date DESC LIMIT '.strval($froma).', '.strval($to).'');
$arr = array();
for($i = -1; $i <= count($all); $i++){
    if(isset($all[$i])){
        array_push($arr, $all[$i]['date']);
    }
}

echo json_encode($arr);

?>  