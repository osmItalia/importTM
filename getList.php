<?php
include('conf.php');
$db= new PDO('sqlite:'.$DBfile);
$res=$db->query('SELECT * FROM tasks WHERE dataset="'.$datasetName.'"');

echo json_encode($res->fetchAll());
?>
