<?php

//include the files from the PHP FT client library
include('fusion-tables-client-php/clientlogin.php');
include('fusion-tables-client-php/sql.php');
include('fusion-tables-client-php/file.php');

// Table id
$tableid = 1893916;  // la dsrcid de Test01

//Enter your username and password
$username = "usunier";
$password = "zuzu6161";

// Get auth token - it would be better to save the token in a secure database
// rather than requesting it with every page load.
$token = ClientLogin::getAuthToken($username, $password);
$ftclient = new FTClientLogin($token);

// If the request is a post, insert the data into the table
if($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Insert form data into table
  $insertresults = $ftclient->query(SQLBuilder::insert($tableid, 
    array('enseigne'=> $_POST['retail_name'],
    'code' => $_POST['code'],
    'location' => $_POST['location'],
    'date' => $_POST['datcre'])));
  $insertresults = explode("\n", $insertresults);
  $rowid1 = $insertresults[1];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Saisie des Commerces</title>
<style>
  body { font-family: Arial, sans-serif; }
  #map_canvas { height: 400px; width:700px; }
</style>

<!-- Import Google Maps API JavaScript -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript">

var map;
var marker;

// Simple form checking.
function check_form() {
  if(document.getElementById('retail_name').value == '' ||
    document.getElementById('code').value == '' ||
	document.getElementById('location').value == '' ||
    document.getElementById('datcre').value == '') {
      
      alert('Enseigne, code, latitude_longitude et date sont obligatoires.');
      return false;
  } 
  return true;
}

function initialize() {
  // Initialize the Google Map
  map = new google.maps.Map(document.getElementById('map_canvas'), {
    center: new google.maps.LatLng(46.2012,6.2733),
    zoom: 14,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  // Insérer les points représentant les enseigne déjà saisies
  layer = new google.maps.FusionTablesLayer({
	  query: {
			select: 'location',
			from: '1893916'
		  }
	});
  layer.setMap(map);

  
  //Add a click listener to listen for clicks on the map
  google.maps.event.addListener(map, 'click', function(e) {
    alert('Vous avez sélectionné lat,lng: ' + e.latLng.lat() + ',' + e.latLng.lng());
    // The following line sets the value of the hidden location input
    // This field will be submitted along with the other form inputs
    if(marker == null) { 
      marker = new google.maps.Marker({
        map: map
      });
    }
    marker.setPosition(e.latLng);
    document.getElementById('location').value = e.latLng.lat() + ',' + e.latLng.lng();
  });
}

</script>
</head>

<body onLoad="initialize();">

<h1>SAISIE DES COMMERCES</h1>

<h2>Insérer un nouveau commerce</h2>

<p>&nbsp;</p>
<form method="post" action="form_enseigne.php" onSubmit="return check_form();"><br /><br /><br />
  <table width="980" border="0">
  <caption>
    Saisie
  </caption>
  <tr>
    <td>Enseigne:</td>
    <td><input type="text" name="retail_name" id="retail_name" /></td>
    <td rowspan="3"><div id="map_canvas"></div></td>
  </tr>
  <tr>
    <td>Code:</td>
    <td><input type="text" name="code" id="code" /></td>
  </tr>
  <tr>
    <td>Date:</td>
    <td><input type="date" name="datcre" id="datcre" /></td>
  </tr>
</table>
  <!-- Create the map here --><!-- Hidden input field for location selected on map -->
  <input type="hidden" name="location" id="location" />
  <input type="submit" value="Ajouter" />
</form>

<h2>Liste des commerces déjà insérés</h2>
<p>
<?php
// Show the data from table
$table_data = $ftclient->query(SQLBuilder::select($tableid));
$table_data = explode("\n", $table_data);
for($i = 0; $i < count($table_data); $i++) {
  echo $table_data[$i] . '<br />';
} 
?>
</p>
</body>
</html>
