<?php
/* Database connection settings */
$sql_host = 'localhost';
$sql_user = 'root';
$sql_pass = 'password';
$sql_db = 'Users';
$mysqli = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db) or die($mysqli->error);

?>
