<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KUPP AI — Sustainable Future</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: url('https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1400&q=80') no-repeat center center/cover;
  height: 100vh;
  display: flex; justify-content: center; align-items: center;
  color: white; overflow: hidden;
}
.overlay {
  position: absolute; inset: 0;
  background: rgba(0,0,0,0.6);
}
.blur-card {
  position: relative;
  z-index: 10;
  backdrop-filter: blur(10px);
  background: rgba(255,255,255,0.1);
  border-radius: 20px;
  padding: 40px;
  box-shadow: 0 0 30px rgba(0,0,0,0.4);
  animation: floatUp 1.5s ease-in-out;
}
@keyframes floatUp {
  from {opacity: 0; transform: translateY(40px);}
  to {opacity: 1; transform: translateY(0);}
}
h1 {
  font-family: "Poppins", sans-serif;
  font-weight: 700;
  background: linear-gradient(90deg, #00e676, #1de9b6);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  margin-bottom: 20px;
}
input {
  background: rgba(255,255,255,0.15)!important;
  border: none!important;
  color: white!important;
}
input::placeholder { color: #ccc!important; }
button {
  transition: all 0.3s ease;
}
button:hover {
  transform: scale(1.05);
}
.quote {
  font-style: italic;
  font-size: 1.2rem;
  color: #c8e6c9;
}
.eye-icon {
  cursor: pointer;
  position: absolute; right: 15px; top: 10px; color: #ccc;
}
</style>
</head>
<body>
<div class="overlay"></div>
<div class="blur-card text-center">
  <h1>KUPP AI ♻️</h1>
  <p class="quote mb-4">“A green planet is a clean planet — manage waste wisely.”</p>

  <form action="login.php" method="post">
    <div class="mb-3 position-relative">
      <input type="text" name="username" class="form-control" placeholder="Username" required>
    </div>
    <div class="mb-3 position-relative">
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
      <span class="eye-icon" onclick="togglePassword()">👁️</span>
    </div>
    <button type="submit" class="btn btn-success w-100 mb-3 fw-bold">Sign In</button>
  </form>

  <form action="register.php" method="post">
    <input type="text" name="username" class="form-control mb-2" placeholder="Create Username" required>
    <input type="password" name="password" class="form-control mb-3" placeholder="Create Password" required>
    <button class="btn btn-warning w-100 fw-bold">Create Account</button>
  </form>
</div>

<script>
function togglePassword() {
  const pw = document.getElementById("password");
  pw.type = pw.type === "password" ? "text" : "password";
}
</script>
</body>
</html>


