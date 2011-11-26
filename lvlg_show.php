<?php require_once('../Connections/enseignes.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// chargement de la base des commerces pour affichage dans un tableau
mysql_select_db($database_enseignes, $enseignes);
$query_Recordset1 = "SELECT * FROM commerces ORDER BY co_enseigne ASC";
$Recordset1 = mysql_query($query_Recordset1, $enseignes) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
$gmark = "[\n";
do { 
$meid = $row_Recordset1['co_metier'];
mysql_select_db($database_enseignes, $enseignes);
$query_Recordset3 = "SELECT * FROM metiers WHERE me_id = '$meid' ";
$Recordset3 = mysql_query($query_Recordset3, $enseignes) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$metier = "'".$row_Recordset3['me_lib']."'" ;
$img = "'".$row_Recordset3['me_img']."'" ;
$gmark = $gmark."['".$row_Recordset1['co_enseigne']."', ".$row_Recordset1['co_lat'].", ".$row_Recordset1['co_lon'].", ".$row_Recordset1['co_id'].", ".$row_Recordset1['co_metier'].", ".$metier.", ".$img."],\n";
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
$len1 = strlen($gmark) - 2 ; $len2 = -strlen($gmark); 
$gmark = substr($gmark, $len2, $len1);
$gmark = $gmark."\n]";

// chargement de la base des commerces pour affichage dans un tableau
mysql_select_db($database_enseignes, $enseignes);
$query_Recordset2 = "SELECT * FROM commerces ORDER BY co_enseigne ASC";
$Recordset2 = mysql_query($query_Recordset2, $enseignes) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>Affichage des Commerces</title>
<style>
  #map_canvas { height: 480px; width:640px; }
</style>
<!-- Import Google Maps API JavaScript -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize() {
var myOptions = {
	zoom: 14,
	center: new google.maps.LatLng(46.2012,6.2733),
	mapTypeId: google.maps.MapTypeId.ROADMAP
}
var map = new google.maps.Map(document.getElementById("map_canvas"),
                                myOptions);

setMarkers(map, stores);
}
var stores = <?php echo $gmark ; ?> ;
function setMarkers(map, locations) {

for (var i = 0; i < locations.length; i++) {
    var store = locations[i];
    var myLatLng = new google.maps.LatLng(store[1], store[2]);
	var icon = 'images/'+store[6];
	var image = new google.maps.MarkerImage(icon,
      new google.maps.Size(20, 32),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 32));
    var shape = {
      coord: [1, 1, 1, 20, 18, 20, 18 , 1],
      type: 'poly'
    };
	var contenu = '<table width="260" border="1" cellspacing="0" cellpadding="1" class="iw_table"><tr><td  class="firstheading" colspan="2">'+store[0]+'</td></tr><tr><td>activit&eacute;</td><td>'+store[1]+'</td></tr><tr><td>ville</td><td>'+store[2]+'</td></tr><tr><td>adresse</td><td>'+store[3]+'</td></tr><tr><td>site web</td><td>'+store[4]+'</td></tr><tr><td>t&eacute;l&eacute;phone</td><td>'+store[4]+'</td></tr><tr><td>date de cr&eacute;ation</td><td>'+store[4]+'</td></tr><tr><td>surface</td><td>'+store[4]+'</td></tr></table>';
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        shape: shape,
        title: store[0],
        zIndex: store[3],
		html: contenu
    	});
		var infowindow = new google.maps.InfoWindow({
		});
		google.maps.event.addListener(marker, 'click', function() {
		infowindow.setContent(this.html);
		infowindow.open(map,this);
		});
   }
}
</script>
<link href="css/lvlg.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="initialize();">
<div class="container">
<div class="header"><a href="index.html"><img src="../images/lvlg_header02.png" width="960" height="132" alt="header"></a></div>

<table width="960" border="1">
  <tr>
    <td width="100">nom</td>
    <td width="220">&nbsp;</td>
    <td width="640" rowspan="14"><div id="map_canvas"></div></td>
  </tr>
  <tr>
    <td>enseigne</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>m&eacute;tier</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>num&eacute;ro</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>voie</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>compl&eacute;ment</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>code postal</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>ville</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>latitude</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>longitude</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>surface</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>t&eacute;l&eacute;phone</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>site web</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>date de cr&eacute;ation</td>
    <td>&nbsp;</td>
    </tr>
</table>

<table border="1" cellspacing="0" cellpadding="1" class="tableau">
  <tr>
    <td>enseigne</td>
    <td>m&eacute;tier</td>
    <td>adresse</td>
    <td>latitude</td>
    <td>longitude</td>
  </tr>
  <?php do { ?>
  <tr>
    <td><?php echo $row_Recordset2['co_enseigne']; ?></td>
    <td><?php echo $row_Recordset2['co_metier']; ?></td>
    <td><?php echo $row_Recordset2['co_numéro']." ".$row_Recordset2['co_adresse1']."<br>".$row_Recordset2['co_cp']." ".$row_Recordset2['co_ville']; ?></td>
    <td><?php echo $row_Recordset2['co_lat']; ?></td>
    <td><?php echo $row_Recordset2['co_lon']; ?></td>
  </tr>
  <?php } while ($row_Recordset2 = mysql_fetch_assoc($Recordset2)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
?>
