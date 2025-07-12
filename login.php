<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = db()->prepare("SELECT * FROM users WHERE username = ? AND password = SHA2(?, 256)");
  $stmt->execute([$username, $password]);
  $user = $stmt->fetch();

  if ($user) {
    $_SESSION['user'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    header('Location: admin');
    exit;
  } else {
    $error = "Invalid login credentials.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <style>
    body {
      background: #111;
      color: #fff;
      font-family: sans-serif;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .login-box {
      width: 100%;
      max-width: 360px;
      background: #1c1c1c;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.5);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-size: 22px;
    }
    .error {
      color: #f44336;
      background: #222;
      padding: 10px;
      margin-bottom: 12px;
      text-align: center;
      border-radius: 8px;
      font-size: 14px;
    }
    input {
      width: 90%;
      padding: 14px;
      margin: 10px 0;
      border: none;
      border-radius: 8px;
      background: #222;
      color: #fff;
      font-size: 15px;
    }
    button {
      width: 100%;
      padding: 14px;
      background: #4285f4;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      color: #fff;
      font-size: 16px;
      margin-top: 10px;
    }
    @media (max-width: 400px) {
      .login-box { padding: 20px 16px; }
      h2 { font-size: 20px; }
    }
  </style>
</head>
<body>
<div class="login-box">
  <h2>Login</h2>
  <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
  <form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>
