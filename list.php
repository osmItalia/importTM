<?php 
include('conf.php'); 
session_start();

if(!isset($_SESSION['secret'])) {
	//manda a login.php
	header('Location: login.php');
	die();
	}
?>
<html>
<head>
<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<style>
#map{
	position:fixed;
	top:0;
	right:0;
	width:75%;
	bottom:0;
	overflow-y:scroll;
}
#panel{
	position:fixed;
	top:0;
	left:0;
	width:25%;
	bottom:0;
}

.list{width:300px}
.list li{height:15px; padding:5px;}
.list li a{vertical-align: middle;}
.statusinactive{background-color:rgba(255,0,0,0.5);}
.statusworking{background-color:rgba(255,255,0,0.5);}
.statusdone{background-color:rgba(0,255,0,0.5);}
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
<br/>
<hr>
<br/>
<p>User: <?php echo $_SESSION['osm_user']; ?>
<br/>
<a href="logout.php">Logout</a>
</p>
</div>
<div id="map"></div>
<script>


$.getJSON('getList.php',function(data){

var div=$('#map');
var list=$('<ul class="list"></ul>');
$.each(data,function(i,j){
list.append('<li class="status'+j['status']+'"><a href="#" onclick="setIdPathStatus(\''+j['id']+'\',\''+j['path']+'\',\''+j['status']+'\');working(this);">'+j['path']+'</a></li>');
});
div.append(list);
});

var workingId=0;
function working(id){
var par=$(id).parent()
par.removeClass();
par.addClass('statusworking');
workingId=id;
}

function release(){
var par=$(workingId).parent()
par.removeClass();
par.addClass('statusinactive');
}

function complete(){
var par=$(workingId).parent();
par.removeClass();
par.addClass('statusdone');
}

function setIdPathStatus(id,path,status){
		$("#message").text("Hai selezionato il file con codice "+id+" ("+path+")");
		osm_user = "<?php echo $_SESSION['osm_user']; ?>";
		
		if(status=="done"){
			alert("Gia' fatto");
			//return;
		}
		$('#btnDownload').off('click');
		$('#btnDownload').on('click',function(){
			execDownload(id,path,osm_user);
		});
		$('#btnRelease').on('click',function(){
			$.get('updateStatus.php',{'id':id,'status':"inactive",'user':''});
			$("#message").text("Seleziona un elemento");
			release();
		});
}

function execDownload(id,path,user){
		$.get('http://localhost:8111/import',{url:"<?php echo $urlApp; ?>"+path},function(res){
			console.log(res.trim());
			if(res.trim() === 'OK'){
				//update db
				$.get('updateStatus.php',{'id':id,'status':"working",'user':user});
				$('#btnComplete').on('click',function(){
					$.get('updateStatus.php',{'id':id,'status':"done",'user':user});
					$("#message").text("Seleziona un elemento");
					complete();
				});
			}
		});
}
</script>
</body>
</html>
