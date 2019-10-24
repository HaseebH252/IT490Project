<?php
/* Database connection settings */
$sql_host = 'localhost';
$sql_user = 'test';
$sql_pass = 'Today123$';
$sql_db = 'Users';
$mysqli = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db) or die($mysqli->error);

?>