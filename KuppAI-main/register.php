<?php
require 'config2.php';

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
  header("Location: index2.php?msg=Account Created! Please Login");
} else {
  echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
