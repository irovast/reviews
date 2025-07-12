<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login'); exit;
}
require 'config.php';

$id = $_GET['id'] ?? '';
$row = ['slug'=>'','name'=>'','logo_url'=>'','google_link'=>'','email'=>''];
$storedPassword = '';

$currentUser = db()->prepare("SELECT id, role FROM users WHERE username=?");
$currentUser->execute([$_SESSION['user']]);
$current = $currentUser->fetch();
$isAdmin = ($current['role'] === 'admin');

if ($id) {
  $stmt = db()->prepare("SELECT * FROM businesses WHERE id=?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();

  // get password
  $userStmt = db()->prepare("SELECT plain_password FROM users WHERE username=?");
  $userStmt->execute([$row['slug']]);
  $storedPassword = $userStmt->fetchColumn() ?: '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $slug = $_POST['slug'];
  $name = $_POST['name'];
  $google_link = $_POST['google_link'];
  $email = $_POST['email'];
  $logo_url = $_POST['existing_logo'];
  $password = $_POST['password'] ?? '';

  if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
    $newName = 'logos/' . uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['logo']['tmp_name'], $newName);
    $logo_url = $newName;
  }

  if ($id) {
    $stmt = db()->prepare("UPDATE businesses SET slug=?, name=?, logo_url=?, google_link=?, email=? WHERE id=?");
    $stmt->execute([$slug, $name, $logo_url, $google_link, $email, $id]);

    if (!empty($password)) {
      $stmt = db()->prepare("UPDATE users SET password=SHA2(? , 256), plain_password=? WHERE username=?");
      $stmt->execute([$password, $password, $slug]);
    }
  } else {
    $uid = null;
    $stmt = db()->prepare("SELECT id FROM users WHERE username=?");
    $stmt->execute([$slug]);
    $uid = $stmt->fetchColumn();

    if (!$uid && $isAdmin && !empty($password)) {
      $stmt = db()->prepare("INSERT INTO users (username, password, role, plain_password) VALUES (?, SHA2(?, 256), 'user', ?)");
      $stmt->execute([$slug, $password, $password]);
      $uid = db()->lastInsertId();
    }

    if (!$uid) {
      die("Cannot create brand without user. Provide password or use existing slug.");
    }

    $stmt = db()->prepare("INSERT INTO businesses (slug, name, logo_url, google_link, email, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$slug, $name, $logo_url, $google_link, $email, $uid]);
  }

  header("Location: admin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Edit Brand' : 'Add New Brand' ?></title>
<style>
  body { background:#111; color:#fff; font-family:sans-serif; margin:0; padding:20px; }
  h2 { text-align:center; margin-bottom:20px; }
  form {
    max-width:460px; margin:auto; background:#1c1c1c; padding:20px;
    border-radius:10px;
  }
  label { display:block; margin-top:14px; font-size:14px; }
  input[type="text"], input[type="email"], input[type="file"], input[type="password"] {
    width:95%; padding:12px; background:#222; color:#fff;
    border:none; border-radius:6px; font-size:14px;
  }
  button {
    width:100%; margin-top:20px; padding:14px; background:#4285f4;
    border:none; color:#fff; font-size:16px; border-radius:6px;
  }
  img { max-width:140px; margin-top:10px; border-radius:6px; }
  .info-link { font-size:12px; color:#ccc; text-decoration:underline; }
  .eye-toggle {
    position: absolute;
    right: 25px;
    top: 9px;
    cursor: pointer;
    color: #aaa;
  }
  .pass-wrapper {
    position: relative;
  }
</style>
</head>
<body>

<h2><?= $id ? 'Edit Brand' : 'Add New Brand' ?></h2>

<form method="post" enctype="multipart/form-data">
  <label>Slug (used as login)</label>
  <?php if ($id && !$isAdmin): ?>
    <input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" readonly>
  <?php else: ?>
    <input type="text" name="slug" value="<?= htmlspecialchars($row['slug']) ?>" required>
  <?php endif; ?>

  <label>Name</label>
  <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>

  <label>Logo Image</label>
  <input type="file" name="logo" accept="image/*">
  <?php if (!empty($row['logo_url'])): ?>
    <img src="<?= htmlspecialchars($row['logo_url']) ?>" alt="Logo">
    <input type="hidden" name="existing_logo" value="<?= htmlspecialchars($row['logo_url']) ?>">
  <?php else: ?>
    <input type="hidden" name="existing_logo" value="">
  <?php endif; ?>

  <label>Google Place ID</label>
  <input type="text" name="google_link" value="<?= htmlspecialchars($row['google_link']) ?>" required>
  <a class="info-link" href="https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder" target="_blank">Find Place ID</a>

  <label>Email (for notifications)</label>
  <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>

  <label>Password</label>
  <div class="pass-wrapper">
    <input type="password" name="password" id="password" value="<?= htmlspecialchars($storedPassword) ?>">
    <span class="eye-toggle" onclick="togglePassword()">👁</span>
  </div>

  <button type="submit">Save</button>
</form>

<script>
function togglePassword() {
  const field = document.getElementById("password");
  field.type = field.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
