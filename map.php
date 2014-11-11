<?php include('conf.php'); ?>
<html>
<head>
<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css" />
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<style>
#map{
	position:fixed;
	top:0;
	right:0;
	width:75%;
	bottom:0;
}
#panel{
	position:fixed;
	top:0;
	left:0;
	width:25%;
	bottom:0;
}
</style>
</head>
<body>
<div id="panel">
<h1>Import dati</h1>
<p id="message">Seleziona un elemento</p>
<button id="btnDownload">Download file in JOSM</button>
<br/>
<button id="btnComplete">Completato</button>
<button id="btnRelease">Rilascia</button>

</div>
<div id="map"></div>
<script>
var lat=40.097,
	lon=9.124,
	zoom=8;
	var osm = new L.TileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'});
	var map = new L.Map('map', {
	    center: new L.LatLng(lat, lon),
	    zoom: zoom,
	    layers: [osm]
	});
var layerDict=[];
	
$.getJSON('getData.php',function(data){
	var geojson = L.geoJson(data,{
	onEachFeature: eachFeature,
		style: function(feature) {
			switch (feature.properties.status) {
				case 'inactive': return {weight: 1, color: "black", fillColor: "#FF0000"};
				case 'working':  return {weight: 1, color: "black", fillColor: "#FFFF00"};
				case 'done':  return {weight: 1, color: "black", fillColor: "#00FF00"};
			}}
	});
	geojson.addTo(map);
	map.fitBounds(geojson.getBounds(),{ padding:[50,50]});
});

function eachFeature(feature, layer) {
	layerDict[feature.properties.id]=layer;
    layer.on('click', function (e) {
		setIdPathStatus(feature.properties.id,feature.properties.path,feature.properties.status);
		layer.setStyle({fillColor:"#FFFF00"});
        //alert(feature.properties.path);
    });
}

function setIdPathStatus(id,path,status){
		$("#message").text("Hai selezionato il riquadro con codice "+id);
		if(status=="done"){
		alert("Già fatto");
		//return;
		}
		$('#btnDownload').on('click',function(){
		execDownload(id,path);
		});
		$('#btnRelease').on('click',function(){
		$.get('updateStatus.php',{'id':id,'status':"inactive"});
		$("#message").text("Seleziona un elemento");
		layerDict[id].setStyle({fillColor:"#FF0000"});
		});
}

function execDownload(id,path){
		$.get('http://localhost:8111/import',{url:"<?php echo $urlApp; ?>"+path},function(res){
		console.log(res.trim());
		if(res.trim() === 'OK'){
		//update su db
		$.get('updateStatus.php',{'id':id,'status':"working"});
		$('#btnComplete').on('click',function(){
		$.get('updateStatus.php',{'id':id,'status':"done"});
		$("#message").text("Seleziona un elemento");
		layerDict[id].setStyle({fillColor:"#00FF00"});
		});

		}
		});
}
</script>
</body>
</html>