<?php
include('conf.php');

$db= new PDO('sqlite:'.$DBfile);
$res=$db->query('SELECT * FROM tasks WHERE dataset="'.$datasetName.'"');

//geojson
$geoj=["type"=>"FeatureCollection","features"=>[]];

$ft=[];
foreach ($res as $r){
$feat=["type"=>"Feature","properties"=>[],"geometry"=>["type"=>"Polygon", "coordinates"=>[]]];
$feat["geometry"]["coordinates"][]=json_decode($r['bbox']);
$feat["properties"]=["id"=>$r['id'],"path"=>$r['path'],"status"=>$r['status']];

$ft[]=$feat;
}

$geoj['features']=$ft;

echo json_encode($geoj);
?>