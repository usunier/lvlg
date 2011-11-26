<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_enseignes = "localhost";
$database_enseignes = "enseignes";
$username_enseignes = "admin";
$password_enseignes = "zuzu6161";
$enseignes = mysql_pconnect($hostname_enseignes, $username_enseignes, $password_enseignes) or trigger_error(mysql_error(),E_USER_ERROR); 
?>