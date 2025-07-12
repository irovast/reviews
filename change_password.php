<?php
session_start();
require 'config.php';

if ($_SESSION['role'] !== 'admin') exit('Forbidden');

$newpass = $_POST['newpass'] ?? '';
if (strlen($newpass) < 4) exit('Password too short');

$stmt = db()->prepare("UPDATE users SET password=SHA2(?, 256) WHERE username=?");
$stmt->execute([$newpass, $_SESSION['user']]);
header("Location: admin.php");
