<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login'); exit;
}
require 'config.php';

$stmt = db()->prepare("SELECT id, role FROM users WHERE username=?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) { die("Invalid session user"); }

$isAdmin = ($user['role'] === 'admin');
$uid = $user['id'];

if (isset($_GET['delete']) && $isAdmin) {
  $id = intval($_GET['delete']);

  // Get user ID of business
  $stmt = db()->prepare("SELECT user_id FROM businesses WHERE id=?");
  $stmt->execute([$id]);
  $userId = $stmt->fetchColumn();

  // Delete stats and messages
  db()->prepare("DELETE FROM messages WHERE business_id=?")->execute([$id]);
  db()->prepare("DELETE FROM stats WHERE business_id=?")->execute([$id]);

  // Delete business
  db()->prepare("DELETE FROM businesses WHERE id=?")->execute([$id]);

  // Delete user (if not admin)
  if ($userId) {
    $stmt = db()->prepare("SELECT role FROM users WHERE id=?");
    $stmt->execute([$userId]);
    if ($stmt->fetchColumn() !== 'admin') {
      db()->prepare("DELETE FROM users WHERE id=?")->execute([$userId]);
    }
  }

  header("Location: admin.php");
  exit;
}

if ($isAdmin) {
  $stmt = db()->query("SELECT * FROM businesses ORDER BY id DESC");
} else {
  $stmt = db()->prepare("SELECT * FROM businesses WHERE user_id=? ORDER BY id DESC");
  $stmt->execute([$uid]);
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <style>
    body { background: #111; color: #fff; font-family: sans-serif; padding: 20px; margin: 0; }
    h2 { text-align: center; margin-bottom: 20px; }
    .top-links { text-align: center; margin-bottom: 20px; }
    .top-links a {
      padding: 10px 16px; border-radius: 6px;
      text-decoration: none; color: white; font-weight: bold; margin: 0 8px; display: inline-block;
    }
    .brand {
      background: #1c1c1c; padding: 16px; border-radius: 10px;
      margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
    }
    .brand img { max-width: 50px; max-height: 50px; border-radius: 8px; }
    .brand-name { flex: 1; font-size: 16px; font-weight: bold; }
    .brand-actions { display: flex; flex-wrap: wrap; gap: 8px; }
    .brand-actions a {
       color: #fff; padding: 6px 12px; border-radius: 6px;
      text-decoration: none; font-size: 14px;
    }
    .edit { background: #444; }
    .link { background: #4285f4; }
    .delete { background: #e53935; }
    .pdf { background: #00c853; }

    /* Popup */
    .modal {
      display: none; position: fixed; z-index: 99; left: 0; top: 0;
      width: 100%; height: 100%; background: rgba(0,0,0,0.5);
      justify-content: center; align-items: center;
    }
    .modal-content {
      background: #222; padding: 20px; border-radius: 10px;
      max-width: 300px; width: 90%; color: #fff;
    }
    .modal-content input {
      width: 100%; margin: 10px 0; padding: 10px; background: #333; color: #fff;
      border: none; border-radius: 6px;
    }
    .modal-content button {
      background: #4285f4; color: #fff; border: none;
      padding: 10px 16px; border-radius: 6px; cursor: pointer;
    }
  </style>
</head>
<body>

<h2><?= $isAdmin ? 'All Businesses' : 'My Business' ?></h2>

<div class="top-links">
  <?php if ($isAdmin): ?>
    <a href="edit.php"class="pdf">+ Add New</a>
    <a href="#" onclick="document.getElementById('modal').style.display='flex'" class="edit" >Change Password</a>
  <?php endif; ?>
  <a href="dashboard.php" class="edit">Dashboard</a>
  <a href="logout.php" class="delete">Logout</a>
</div>

<?php foreach($rows as $r): ?>
  <div class="brand">
    <div class="brand-name"><?= htmlspecialchars($r['name']) ?></div>
    <img src="<?= htmlspecialchars($r['logo_url']) ?>" alt="Logo">
    <div class="brand-actions">
      <a class="link" href="https://review.card-v.com/index.html?b=<?= urlencode($r['slug']) ?>" target="_blank">Link</a>
      <a href="edit.php?id=<?= $r['id'] ?>" class="edit">Edit</a>
      <?php if ($isAdmin): ?>
        <a href="admin.php?delete=<?= $r['id'] ?>" class="delete" onclick="return confirm('Delete this brand?')">Delete</a>
      <?php endif; ?>
      <a href="generate_pdf.php?id=<?= $r['id'] ?>" class="pdf" target="_blank">PDF</a>
    </div>
  </div>
<?php endforeach; ?>

<!-- Password Modal -->
<div class="modal" id="modal">
  <div class="modal-content">
    <form action="change_password.php" method="POST" onsubmit="return confirm('Confirm password change?')">
      <h3>Change Admin Password</h3>
      <input type="password" name="newpass" placeholder="New Password" required>
      <button type="submit">Update</button>
      <button type="button" onclick="document.getElementById('modal').style.display='none'">Cancel</button>
    </form>
  </div>
</div>

</body>
</html>
