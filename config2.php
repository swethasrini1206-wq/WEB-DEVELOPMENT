<?php
$servername = "localhost";
$usrname = "root"; 
$password = "root"; 
$dbname = "kuppai";

$conn = new mysqli($servername, $usrname, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
