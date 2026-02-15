<?php
session_start();
require 'config2.php';

$username = $_POST['username'];
$metal = floatval($_POST['metal']);
$bio = floatval($_POST['bio']);

$metal_amt = $metal * 5;
$bio_amt = $bio * 2;
$total = $metal_amt + $bio_amt;

$conn->query("UPDATE users SET wallet_balance = wallet_balance + $total WHERE username='$username'");
$stmt = $conn->prepare("INSERT INTO claims (username, metal_kg, bio_kg, total_amount) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sddd", $username, $metal, $bio, $total);
$stmt->execute();

header("Location: home.php");
?>
