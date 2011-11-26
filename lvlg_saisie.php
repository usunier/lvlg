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
// mise à jour de Google Fusion Tables
// programmes de l'API PHP pour Google Fusion Tables
include('fusion-tables-client-php/clientlogin.php');
include('fusion-tables-client-php/sql.php');
include('fusion-tables-client-php/file.php');
// Table id
$tableid = 1997194;  // la dsrcid pour Annemasse_commerces
//Enter your username and password
$username = "usunier";
$password = "zuzu6161";
// Get auth token de Google
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
<?php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO commerces (co_lib, co_enseigne, co_metier, co_numéro, co_adresse1, co_adresse2, co_cp, co_ville, co_lat, co_lon, co_surface) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['lib'], "text"),
                       GetSQLValueString($_POST['enseigne'], "text"),
                       GetSQLValueString($_POST['choix_type'], "int"),
                       GetSQLValueString($_POST['num'], "int"),
                       GetSQLValueString($_POST['adresse1'], "text"),
                       GetSQLValueString($_POST['adresse2'], "text"),
                       GetSQLValueString($_POST['cp'], "int"),
                       GetSQLValueString($_POST['ville'], "text"),
                       GetSQLValueString($_POST['lat'], "double"),
                       GetSQLValueString($_POST['lon'], "double"),
                       GetSQLValueString($_POST['surface'], "int"));

  mysql_select_db($database_enseignes, $enseignes);
  $Result1 = mysql_query($insertSQL, $enseignes) or die(mysql_error());
}

mysql_select_db($database_enseignes, $enseignes);
$query_Recordset1 = "SELECT * FROM metiers ORDER BY me_lib ASC";
$Recordset1 = mysql_query($query_Recordset1, $enseignes) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=latin1" />
<title>Saisie des Commerces</title>
</head>
<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="760" border="1">
    <tr>
      <td>nom commerce</td>
      <td><label for="lib"></label>
      <input type="text" name="lib" id="lib" /></td>
    </tr>
    <tr>
      <td>enseigne</td>
      <td><input type="text" name="enseigne" id="enseigne" /></td>
    </tr>
    <tr>
      <td>type de commerce</td>
      <td><select name="choix_type" id="choix_type">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset1['me_id']?>"><?php echo $row_Recordset1['me_lib']?></option>
        <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>n&deg; dans la voie</td>
      <td><input type="text" name="num" id="num" /></td>
    </tr>
    <tr>
      <td>nom de la voie</td>
      <td><input type="text" name="adresse1" id="adresse1" /></td>
    </tr>
    <tr>
      <td>compl&eacute;ment d'adresse</td>
      <td><input type="text" name="adresse2" id="adresse2" /></td>
    </tr>
    <tr>
      <td>code postal</td>
      <td><input type="text" name="cp" id="cp" /></td>
    </tr>
    <tr>
      <td>ville</td>
      <td><input type="text" name="ville" id="ville" /></td>
    </tr>
    <tr>
      <td>latitude</td>
      <td><input type="text" name="lat" id="lat" /></td>
    </tr>
    <tr>
      <td>longitude</td>
      <td><input type="text" name="lon" id="lon" /></td>
    </tr>
    <tr>
      <td>surface</td>
      <td><input type="text" name="surface" id="surface" /></td>
    </tr>
  </table>
  <p>
    <input type="submit" name="entrer" id="entrer" value="enregistrer" />
  </p>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
