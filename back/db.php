<?php
/* Database connection settings */
$sql_host = '192.168.1.101';
$sql_user = 'test';
$sql_pass = 'Today123$';
$sql_db = 'Users';
$mysqli = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db) or die($mysqli->error);

?>
