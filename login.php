<?php
$req_url = 'http://www.openstreetmap.org/oauth/request_token';     // OSM Request Token URL
$authurl = 'http://www.openstreetmap.org/oauth/authorize';         // OSM Authorize URL
$acc_url = 'http://www.openstreetmap.org/oauth/access_token';      // OSM Access Token URL
$api_url = 'http://api.openstreetmap.org/api/0.6/';                // OSM API URL

$conskey = 'nEdqWBliX3Lczpucs3Mc5am94jTpN8KREs6THT6q';
$conssec = 'rbZ00EJBsy9Tla0eYd1WvJarerDpbzKUTRyj9bLG';

session_start();

if(isset($_GET['oauth_token']) && isset($_SESSION['secret'])) {
	try {
       $oauth = new OAuth($conskey, $conssec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
      // $oauth->enableDebug();

       $oauth->setToken($_GET['oauth_token'], $_SESSION['secret']);
       $access_token_info = $oauth->getAccessToken($acc_url);

       $_SESSION['token'] = strval($access_token_info['oauth_token']);
       $_SESSION['secret'] = strval($access_token_info['oauth_token_secret']);

       $oauth->setToken($_SESSION['token'], $_SESSION['secret']);

       
       $oauth->fetch($api_url."user/details");
       $user_details = $oauth->getLastResponse();
       
       //echo str_replace("\n", "<br/>", htmlentities($oauth->getLastResponse()))."<br/><br/>";

       $xml = simplexml_load_string($user_details);       
//       $_SESSION['osm_id'] = strval ($xml->user['id']);
	echo "<h3> Benvenuto ";
       $_SESSION['osm_user'] = strval($xml->user['display_name']);

//       echo $_SESSION['osm_id']."<br/>";
       echo $_SESSION['osm_user']."<br/>";
//       echo $_SESSION['token']."<br/>";
//       echo $_SESSION['secret'];
	echo "</h3>";
       echo "<br/><br/>";
	echo "<a href=\"list.php\">Torna all'elenco dei task</a><br/>\n";
	echo "<a href=\"logout.php\">Logout</a>\n";

	} catch(OAuthException $E) {
		echo "<h3>EXCEPTION:</h3>\n";
       		print_r($E);
		}
	}

else {
	try {
	     $oauth = new OAuth($conskey,$conssec,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
	     $request_token_info = $oauth->getRequestToken($req_url);


	     $_SESSION['secret'] = $request_token_info['oauth_token_secret'];
	?>
	<h1>Autorizzazione accesso</h1>
	<h3>Se sei gia' iscritto a OpenStreetMap puoi accedere col link sottostante</h3>
	<?php
	     echo "<a href=\"".$authurl."?oauth_token=".$request_token_info['oauth_token']."\">Autorizza accesso</a>";
	?>

	<h3>Oppure registrati come nuovo utente</h3>
	<a href="https://www.openstreetmap.org/user/new">Nuovo utente</a>

	<?php
	} catch(OAuthException $E) {
	     print_r($E);
		}
	}
?>

