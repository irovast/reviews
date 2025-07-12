<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require 'config.php';

$slug = strtolower($_GET['b'] ?? '');
$stmt = db()->prepare('SELECT id,name,logo_url,google_link,email FROM businesses WHERE slug=?');
$stmt->execute([$slug]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($data ?: ['error'=>'not found']);
