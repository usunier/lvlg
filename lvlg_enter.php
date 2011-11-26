<?php require_once('../Connections/enseignes.php');
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO commerces (co_lib, co_enseigne, co_metier, co_numéro, co_adresse1, co_adresse2, co_cp, co_ville, co_lat, co_lon, co_surface, co_tel, co_web, co_datcre) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nom'], "text"),
                       GetSQLValueString($_POST['enseigne'], "text"),
                       GetSQLValueString($_POST['metier'], "int"),
                       GetSQLValueString($_POST['numero'], "int"),
                       GetSQLValueString($_POST['voie'], "text"),
                       GetSQLValueString($_POST['complement'], "text"),
                       GetSQLValueString($_POST['cp'], "int"),
                       GetSQLValueString($_POST['ville'], "text"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lon'], "double"),
                       GetSQLValueString($_POST['surface'], "int"),
                       GetSQLValueString($_POST['tel'], "text"),
                       GetSQLValueString($_POST['web'], "text"),
                       GetSQLValueString($_POST['date'], "date"));

  mysql_select_db($database_enseignes, $enseignes);
  $Result1 = mysql_query($insertSQL, $enseignes) or die(mysql_error());

  $insertGoTo = "lvlg_enter.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
$ville = "'".$row_Recordset1['co_cp']." ".$row_Recordset1['co_ville']."'";
$adresse = "'".$row_Recordset1['co_numéro']." ".$row_Recordset1['co_adresse1']."'";
$gmark = $gmark."['".$row_Recordset1['co_enseigne']."', ".$metier.", ".$ville.", ".$adresse.", '".$row_Recordset1['co_web']."', '".$row_Recordset1['co_tel']."', '".$row_Recordset1['co_datcre']."', ".$row_Recordset1['co_surface'].", ".$row_Recordset1['co_lat'].", ".$row_Recordset1['co_lon'].", ".$row_Recordset1['co_id'].", ".$img."],\n";
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
$len1 = strlen($gmark) - 2 ; $len2 = -strlen($gmark); 
$gmark = substr($gmark, $len2, $len1);
$gmark = $gmark."\n]";

// chargement de la base des commerces
mysql_select_db($database_enseignes, $enseignes);
$query_Recordset2 = "SELECT * FROM commerces ORDER BY co_enseigne ASC";
$Recordset2 = mysql_query($query_Recordset2, $enseignes) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_enseignes, $enseignes);
$query_Recordset3 = "SELECT * FROM metiers ORDER BY me_lib ASC";
$Recordset3 = mysql_query($query_Recordset3, $enseignes) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);

mysql_select_db($database_enseignes, $enseignes);
$query_RQ04 = "SELECT * FROM enseignes ORDER BY ens_lib ASC";
$RQ04 = mysql_query($query_RQ04, $enseignes) or die(mysql_error());
$row_RQ04 = mysql_fetch_assoc($RQ04);
$totalRows_RQ04 = mysql_num_rows($RQ04);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Affichage des Commerces</title>
<style>
  #map_canvas { height: 480px; width:606px; }
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
    var myLatLng = new google.maps.LatLng(store[8], store[9]);
	var icon = 'images/'+store[11];
	var image = new google.maps.MarkerImage(icon,
      new google.maps.Size(32, 32),
      new google.maps.Point(0,0),
      new google.maps.Point(0, 32));
    var shape = {
      coord: [1, 1, 1, 30, 28, 30, 28 , 1],
      type: 'poly'
    };
	var contenu = '<table width="260" border="1" cellspacing="0" cellpadding="1" class="iw_table"><tr><td  class="firstheading" colspan="2">'+store[0]+'</td></tr><tr><td>activit&eacute;</td><td>'+store[1]+'</td></tr><tr><td>ville</td><td>'+store[2]+'</td></tr><tr><td>adresse</td><td>'+store[3]+'</td></tr><tr><td>site web</td><td>'+store[4]+'</td></tr><tr><td>t&eacute;l&eacute;phone</td><td>'+store[5]+'</td></tr><tr><td>date de cr&eacute;ation</td><td>'+store[5]+'</td></tr><tr><td>surface</td><td>'+store[7]+'</td></tr></table>';
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        shape: shape,
        title: store[0],
        zIndex: store[10],
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
document.write("<H1>id du dernier </H1> " + store[0]);
</script>
<link href="css/lvlg.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body {
	background-color: #FFF;
}
</style>
</head>
<body onLoad="initialize();">
<div class="container">
<div class="header"><a href="index.html"><img src="../images/lvlg_header02.png" width="960" height="132" alt="header"></a></div>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
<table width="960" border="1" cellspacing="0" cellpadding="1" class="tabsaisie">
  <tr>
    <td width="100"><input type="button" id="ACCUEIL" value="RETOUR ACCUEIL"  onclick="location.href='index.html'"/></td>
    <td colspan= "2" class="firstheading" >SAISIE DES COMMERCES</td>
  </tr>
  <tr>
    <td width="104">nom</td>
    <td width="250" class="saisie"><label for="nom"></label>
      <input name="nom" type="text" id="nom" size="35" /></td>
    <td width="606" rowspan="15"><div id="map_canvas"></div></td>
  </tr>
  <tr>
    <td>enseigne</td>
    <td class="saisie"><label for="enseigne"></label>
      <select name="enseigne" id="enseigne">
        <?php
do {  
?>
        <option value="<?php echo $row_RQ04['ens_lib']?>"><?php echo $row_RQ04['ens_lib']?></option>
        <?php
} while ($row_RQ04 = mysql_fetch_assoc($RQ04));
  $rows = mysql_num_rows($RQ04);
  if($rows > 0) {
      mysql_data_seek($RQ04, 0);
	  $row_RQ04 = mysql_fetch_assoc($RQ04);
  }
?>
      </select></td>
    </tr>
  <tr>
    <td>m&eacute;tier</td>
    <td class="saisie"><label for="metier"></label>
      <select name="metier" id="metier">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset3['me_id']?>"<?php if (!(strcmp($row_Recordset3['me_id'], "type de commerce"))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset3['me_lib']?></option>
        <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
      </select></td>
    </tr>
  <tr>
    <td>num&eacute;ro</td>
    <td class="saisie"><input name="numero" type="text" id="numero" size="35" /></td>
    </tr>
  <tr>
    <td>voie</td>
    <td class="saisie"><input name="voie" type="text" id="voie" size="35" maxlength="35" /></td>
    </tr>
  <tr>
    <td>compl&eacute;ment</td>
    <td class="saisie"><input name="complement" type="text" id="complement" size="35" maxlength="35" /></td>
    </tr>
  <tr>
    <td>code postal</td>
    <td class="saisie"><input name="cp" type="text" id="cp" size="5" maxlength="5" /></td>
    </tr>
  <tr>
    <td>ville</td>
    <td class="saisie"><input name="ville" type="text" id="ville" size="35" maxlength="35" /></td>
    </tr>
  <tr>
    <td>latitude</td>
    <td class="saisie"><input name="lat" type="text" id="lat" size="10" maxlength="10" /></td>
    </tr>
  <tr>
    <td>longitude</td>
    <td class="saisie"><input name="lon" type="text" id="lon" size="10" maxlength="10" /></td>
  </tr>
  <tr>
    <td>surface</td>
    <td class="saisie"><input name="surface" type="text" id="surface" size="8" maxlength="8" /></td>
  </tr>
  <tr>
    <td>t&eacute;l&eacute;phone</td>
    <td class="saisie"><input name="tel" type="text" id="tel" size="14" maxlength="14" /></td>
  </tr>
  <tr>
    <td>site web</td>
    <td class="saisie"><input name="web" type="text" id="web" size="35" maxlength="40" /></td>
  </tr>
  <tr>
    <td>creation</td>
    <td class="saisie"><input name="date" type="text" id="date" size="14" maxlength="14" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="entrer" id="entrer" value="valider la saisie" /></td>
    </tr>
</table>
<input type="hidden" name="MM_insert" value="form1" />
</form>
<div class="footer">
    <p>© XXIS 2011 - nous contacter : JF USUNIER 04 79 62 67 83 ou <a href="mailto:usunier@xxis.info">usunier@xxis.info</a></p>
<!-- end .footer --></div>
<!-- end .container --></div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
mysql_free_result($Recordset3);

mysql_free_result($RQ04);
?>
