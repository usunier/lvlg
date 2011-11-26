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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	// test pour savoir si l'activité a bien été choisie
	if ($_POST['metier'] == 0) { $test = "err" ; }
	else {	
	// test pour savoir si l'enseigne existe déjà
			mysql_select_db($database_enseignes, $enseignes);
			$search = $_POST['nom'] ;
			$query_RQ03 = "SELECT * FROM enseignes WHERE ens_lib = '$search'";
			$RQ03 = mysql_query($query_RQ03, $enseignes) or die(mysql_error());
			$row_RQ03= mysql_fetch_assoc($RQ03);
			$totalRows_RQ03= mysql_num_rows($RQ03);
			if ($totalRows_RQ03> 0) {
			$test = mysql_result($RQ03, 0, 'ens_lib');
			$test = "non" ; }
			else {  
	// insertion de la nouvelle enseigne
	$insertSQL = sprintf("INSERT INTO enseignes (ens_lib, ens_metier) VALUES (%s, %s)",
                       GetSQLValueString($_POST['nom'], "text"),
                       GetSQLValueString($_POST['metier'], "int"));

	mysql_select_db($database_enseignes, $enseignes);
	$Result1 = mysql_query($insertSQL, $enseignes) or die(mysql_error());
	$test = "ok" ;
	} }
	$insertGoTo = "lvlg_enseignes.php?ide=".$_POST['nom']."&mes=".$test;
	header(sprintf("Location: %s", $insertGoTo));
	}
mysql_select_db($database_enseignes, $enseignes);
$query_RQ01 = "SELECT * FROM enseignes ORDER BY ens_lib ASC";
$RQ01 = mysql_query($query_RQ01, $enseignes) or die(mysql_error());
$row_RQ01 = mysql_fetch_assoc($RQ01);
$totalRows_RQ01 = mysql_num_rows($RQ01);

mysql_select_db($database_enseignes, $enseignes);
$query_RQ02 = "SELECT * FROM metiers ORDER BY me_lib ASC";
$RQ02 = mysql_query($query_RQ02, $enseignes) or die(mysql_error());
$row_RQ02 = mysql_fetch_assoc($RQ02);
$totalRows_RQ02 = mysql_num_rows($RQ02);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1">
<title>Saisie des Enseignes</title>

<link href="css/lvlg.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div class="container">
  <div class="header"><a href="#"></a> 
    <!-- end .header --><a href="index.html"><img src="../images/lvlg_header02.png" width="960" height="132" alt="header"></a></div>
  <div class="sidebar1">
    <ul class="nav">
      <li><a href="lvlg_display.php" title="Carte01">Enseignes de la ZoneCommerciale d'Annemasse</a></li>
      <li><a href="lvlg_enter.php">Saisie des commerces</a></li>
      <li><a href="lvlg_enseignes.php">Saisie des enseignes</a></li>
      <li><a href="#">Lien quatre</a></li>
    </ul>
    <p> La SCI dispose d'un b&acirc;timent de 1460 m&sup2; au 24 rue des Buchillons &agrave; 74100 Ville La Grand.<br>
    Hyperburo occupe 936 m&sup2; et un local de 515 m&sup2; est actuellement disponible.<!-- end .sidebar1 --></p>
</div>
  <div class="content">
  <?php 
  $message = "veuillez saisir une nouvelle enseigne ci-dessous" ;
  if (isset($_GET['mes'])) {
	  if($mes == 'non') {
	  $message = "l'enseigne ".$ide." était déjà dans la base !" ;
	  }
	  else {
		  if($mes == 'err') { 
		  $message = "il faut saisir le type de commerce !" ; 
		  }
		  else {
	  	  $message = "l'enseigne ".$ide." a bien été entrée dans la base !" ; 
		  }
	  }
  }
  ?>
  <form name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="1" cellpadding="1" cellspacing="0" class="tabsaisie02">
  <tr align="center" class="secondheading">
  	<td colspan="3"><?php echo $message; ?></td>
  </tr>
  <tr align="center">
    <td>nom de l'enseigne</td>
    <td>type de commerce</td>
    <td>validation</td>
  </tr>
  <tr align="center">
    <td><label for="nom"></label>
      <input name="nom" type="text" id="nom" size="30" maxlength="30"></td>
    <td><label for="metier"></label>
      <select name="metier" id="metier">
        <?php
do {  
?>
        <option value="<?php echo $row_RQ02['me_id']?>"><?php echo $row_RQ02['me_lib']?></option>
        <?php
} while ($row_RQ02 = mysql_fetch_assoc($RQ02));
  $rows = mysql_num_rows($RQ02);
  if($rows > 0) {
      mysql_data_seek($RQ02, 0);
	  $row_RQ02 = mysql_fetch_assoc($RQ02);
  }
?>
      </select></td>
      <td><input type="submit" name="VALIDER" id="VALIDER" value="VALIDER"></td>
  </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  </form>
  </div>
  <div class="footer">
  <p>© XXIS 2011 - nous contacter : JF USUNIER 04 79 62 67 83 ou <a href="mailto:usunier@xxis.info">usunier@xxis.info</a></p>
  <!-- end .footer --></div>
<!-- end .container --></div>
</body>
</html>
<?php
mysql_free_result($RQ01);

mysql_free_result($RQ02);
?>
