<?php
/*
Utility to compile the import file from a directory containing all the files
Call like /genTSV.php?d=dataset where "dataset" is the dataset id
*/
$dataset=$_GET['d'];
$i=-1;
$arr=scandir('data');
foreach($arr as $f){
if ($f=='.' || $f=='..') continue;
$bbox=calcBBOX('data/'.$f);
$i++;
echo $dataset."\t".$i."\t".$bbox."\tdata/".$f."\t"."inactive"."\t"."user"."\n";
}

function calcBBOX($file){
	$xml=simplexml_load_file($file);
	$nodes=$xml->xpath('node');
	$minlat=NULL;
	$minlon=NULL;
	$maxlat=NULL;
	$maxlon=NULL;
	$bbox='';
	foreach($nodes as $node){
		$lat=(float)$node->attributes()['lat'];
		$lon=(float)$node->attributes()['lon'];
		
		if($minlat===NULL){
		$maxlat=$lat;
		$maxlon=$lon;
		$minlat=$lat;
		$minlon=$lon;
		}
		else{
		if($lat>$maxlat) $maxlat=$lat;
		if($lon>$maxlon) $maxlon=$lon;
		if($lat<$minlat) $minlat=$lat;
		if($lon<$minlon) $minlon=$lon;
		}
	}
	
	$minlat=round($minlat,6);
	$minlon=round($minlon,6);
	$maxlat=round($maxlat,6);
	$maxlon=round($maxlon,6);
	$bbox="[[".$minlon.",".$maxlat."],[".$maxlon.",".$maxlat."],[".$maxlon.",".$minlat."],[".$minlon.",".$minlat."]]";
	return $bbox;
}
?>