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

$idme= "0";
if (isset($_GET['me'])) {
  $idme = $_GET['me'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_choice"])) && ($_POST["MM_choice"] == "form1")) {
  $choiceGoTo = "lvlg_display.php?me=".$_POST['type'];
  header(sprintf("Location: %s", $choiceGoTo));
}

// chargement de la base des commerces pour affichage dans un tableau
if ( $idme < 1) {
// tous types de commerce confondus
$titre = "TOUS TYPES DE COMMERCE sur l'agglomération d'ANNEMASSE";
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
// ajout pour faire apparaître le local disponible
$ici = "['LOCAL DISPONIBLE', 'libre à la location', '74100 Ville la Grand', '24 rue des Buchillons', 'xxis.info', '04 79 62 67 83', '2011-05-31', 515, 46.2016, 6.27335, 999, 'ici.png' ],\n";
$gmark = $gmark.$ici ;
// fin de l'ajout
$len1 = strlen($gmark) - 2 ; $len2 = -strlen($gmark); 
$gmark = substr($gmark, $len2, $len1);
$gmark = $gmark."\n]";
}
// avec un type de commerce sélectionné
else {
mysql_select_db($database_enseignes, $enseignes);
$query_Recordset1 = "SELECT * FROM commerces WHERE co_metier = '$idme' ORDER BY co_enseigne ASC";
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
$metier = "'".$row_Recordset3['me_lib']."'" ; $titre = "les commerces de ".$row_Recordset3['me_lib']." sur l'Agglomération d'ANNEMASSE" ;
$img = "'".$row_Recordset3['me_img']."'" ;
$ville = "'".$row_Recordset1['co_cp']." ".$row_Recordset1['co_ville']."'";
$adresse = "'".$row_Recordset1['co_numéro']." ".$row_Recordset1['co_adresse1']."'";
$gmark = $gmark."['".$row_Recordset1['co_enseigne']."', ".$metier.", ".$ville.", ".$adresse.", '".$row_Recordset1['co_web']."', '".$row_Recordset1['co_tel']."', '".$row_Recordset1['co_datcre']."', ".$row_Recordset1['co_surface'].", ".$row_Recordset1['co_lat'].", ".$row_Recordset1['co_lon'].", ".$row_Recordset1['co_id'].", ".$img."],\n";
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
// ajout pour faire apparaître le local disponible
$ici = "['LOCAL DISPONIBLE', 'libre à la location', '74100 Ville la Grand', '24 rue des Buchillons', 'xxis.info', '04 79 62 67 83', '2011-05-31', 515, 46.2016, 6.27335, 999, 'ici.png' ],\n";
$gmark = $gmark.$ici ;
// fin de l'ajout
$len1 = strlen($gmark) - 2 ; $len2 = -strlen($gmark); 
$gmark = substr($gmark, $len2, $len1);
$gmark = $gmark."\n]";	
}
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
$query_Recordset4 = "SELECT * FROM enseignes WHERE ens_metier = $idme ORDER BY ens_lib ASC";
$Recordset4 = mysql_query($query_Recordset4, $enseignes) or die(mysql_error());
$row_Recordset4 = mysql_fetch_assoc($Recordset4);
$totalRows_Recordset4 = mysql_num_rows($Recordset4);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Affichage des Commerces</title>
<style>
  #map_canvas { height: 480px; width:956px; }
</style>
<!-- Import Google Maps API JavaScript -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
function initialize() {
var myOptions = {
	zoom: 14,
	center: new google.maps.LatLng(46.19528,6.251478),
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
	var contenu = '<table width="260" border="1" cellspacing="0" cellpadding="1" class="iw_table"><tr><td  class="firstheading" colspan="2">'+store[0]+'</td></tr><tr><td>activit&eacute;</td><td>'+store[1]+'</td></tr><tr><td>ville</td><td>'+store[2]+'</td></tr><tr><td>adresse</td><td>'+store[3]+'</td></tr><tr><td>site web</td><td>'+store[4]+'</td></tr><tr><td>t&eacute;l&eacute;phone</td><td>'+store[5]+'</td></tr><tr><td>date de cr&eacute;ation</td><td>'+store[6]+'</td></tr><tr><td>surface</td><td>'+store[7]+'</td></tr></table>';
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
<table width="960" border="1" cellspacing="0" cellpadding="1" class="tabsaisie">
  <tr>
    <td class="firstheading" align="center"><?php echo $titre; ?></td>
  </tr>
  <tr>
    <td class="firstheading" align="center"><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <label for="type">choisir ici le type de commerce à afficher </label>
      <select name="type" id="type">
		<?php
        do {  
        ?>
        <option value="<?php echo $row_Recordset3['me_id']?>"><?php echo $row_Recordset3['me_lib']?></option>
        <?php
		} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
		  $rows = mysql_num_rows($Recordset3);
		  if($rows > 0) {
			  mysql_data_seek($Recordset3, 0);
			  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
		  }
		?>
      </select>
      <input type="hidden" name="MM_choice" value="form1" />
      <input type="submit" name="valider" id="valider" value="Afficher" />
    </form></td>
  </tr>
  <tr>
    <td><div id="map_canvas"></div></td>
  </tr>
</table>
<table width="960" border="1" cellspacing="0" cellpadding="1" class="tabsaisie">
  <tr class="firstheading">
    <td>enseigne</td>
    <td>présence</td>
    <td>adresse</td>
  </tr>
  <?php do { ?>
  <tr>    
      <td><?php $search = $row_Recordset4['ens_lib'] ;  echo $search ; ?></td>
      <td><?php 
	  		mysql_select_db($database_enseignes, $enseignes);
			$query_R5 = "SELECT * FROM commerces WHERE co_enseigne = '$search'";
			$R5 = mysql_query($query_R5, $enseignes) or die('Erreur dans R5 en ligne 225 '.mysql_error());
			$row_R5= mysql_fetch_assoc($R5);
			$totalRows_R5= mysql_num_rows($R5);
			$adr = "" ;
			if ($totalRows_R5> 0) {
			$test = mysql_result($R5, 0, 'co_lib');
			$adr = mysql_result($R5, 0, 'co_numéro')." ".mysql_result($R5, 0, 'co_adresse1')." ".mysql_result($R5, 0, 'co_cp')." ".mysql_result($R5, 0, 'co_ville') ;
			echo $test ; }
			else { echo "cette enseigne n'est pas présente" ; }
			 ?>
	  </td>
      <td><?php echo $adr ; ?></td>
  </tr>
  <?php } while ($row_Recordset4 = mysql_fetch_assoc($Recordset4)); ?>  
</table>
<?php 
// voir le fichier csv transmis à Mpas
// echo $gmark."<br><br><br><br>" ; 
?>

</body>
</html>
<?php
mysql_free_result($Recordset1);
mysql_free_result($Recordset2);
mysql_free_result($Recordset3);
mysql_free_result($Recordset4);
mysql_free_result($R5);
?>
