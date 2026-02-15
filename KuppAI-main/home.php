<?php
session_start();
if (!isset($_SESSION['username'])) header("Location: index2.php");
$username = $_SESSION['username'];
require 'config2.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>KUPP AI Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #a8e063, #56ab2f);
  min-height: 100vh;
  color: #333;
  overflow-x: hidden;
  font-family: 'Poppins', sans-serif;
}
.card {
  background: rgba(255,255,255,0.9);
  border: none;
  border-radius: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  animation: slideIn 1s ease;
}
@keyframes slideIn {
  from {opacity: 0; transform: translateY(30px);}
  to {opacity: 1; transform: translateY(0);}
}
.nav-tabs .nav-link.active {
  background-color: #43a047 !important;
  color: white !important;
  border-radius: 10px 10px 0 0;
}
.nav-tabs .nav-link {
  color: #2e7d32;
  font-weight: 500;
}
.wallet-box {
  background: linear-gradient(135deg, #00c853, #b2ff59);
  padding: 20px;
  border-radius: 15px;
  color: white;
  box-shadow: 0 0 15px rgba(0,0,0,0.2);
  text-align: center;
}

</style>
</head>
<body class="p-4">
<div class="container">
  <h2 class="text-center mb-4 fw-bold text-light">Welcome, <?php echo htmlspecialchars($username); ?> 🌿</h2>

  <ul class="nav nav-tabs mb-4 justify-content-center">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#waste">♻️ Waste Reservoir</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#wallet">💰 Wallet</a></li>
    <li class="nav-item"><a class="nav-link" href="index2.php">🚪 Sign Out</a></li>
  </ul>

  <div class="tab-content">
    <div id="waste" class="tab-pane fade show active">
      <div class="card p-4">
        <h4 class="text-success mb-3">Enter Waste Details</h4>
        <form action="claim.php" method="post">
          <input type="hidden" name="username" value="<?php echo $username; ?>">
          <div class="mb-3">
            <label>Metal Waste (kg)</label>
            <input type="number" name="metal" class="form-control" placeholder="Enter metal waste in kg" required>
          </div>
          <div class="mb-3">
            <label>Biodegradable Waste (kg)</label>
            <input type="number" name="bio" class="form-control" placeholder="Enter biodegradable waste in kg" required>
          </div>
          <button class="btn btn-success w-100">Claim 💚</button>
        </form>
      </div>
    </div>

    <div id="wallet" class="tab-pane fade p-4">
      <div class="wallet-box">
        <?php
          $res = $conn->query("SELECT wallet_balance FROM users WHERE username='$username'");
          $bal = $res->fetch_assoc()['wallet_balance'];
          echo "<h3>Your Wallet Balance</h3><h1>₹ $bal</h1>";
        ?>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
