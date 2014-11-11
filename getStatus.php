<?php
include('conf.php');
$id=$_GET['id'];

$db= new PDO('sqlite:'.$DBfile);
$res=$db->query('SELECT status from tasks WHERE dataset="'.$datasetName.'" AND id="'.$id.'"');

json_encode($res->fetch());
?>
