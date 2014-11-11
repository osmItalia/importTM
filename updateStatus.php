<?php
include('conf.php');
$id=$_GET['id'];
$status=$_GET['status'];
$user=$_GET['user'];

$db= new PDO('sqlite:'.$DBfile);
$res=$db->exec('UPDATE tasks SET status ="'.$status.'", user ="'.$user.'" WHERE dataset="'.$datasetName.'" AND id="'.$id.'"');
//var_dump($res);

?>
