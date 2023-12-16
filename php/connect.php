<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'madnance';

$connect = new mysqli($servername, $username, $password, $database, 3306);

if($connect->connect_error){
    die("Connection failed".$connect->connect_error);
}
?>