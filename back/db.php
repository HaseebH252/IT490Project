<?php
/* Database connection settings */
$sql_host = '192.168.1.101';
$sql_user = 'test';
$sql_pass = 'Today123$';
$sql_db = 'Users';

$backup_host = '192.168.1.110';


$mysqli = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db) or die($mysqli->error);

?>
