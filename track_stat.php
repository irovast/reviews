<?php
require 'config.php';

$slug = $_POST['slug'] ?? '';
$type = $_POST['type'] ?? '';

if (!$slug || !in_array($type, ['visit', 'happy', 'angry'])) exit;

$stmt = db()->prepare("SELECT id FROM businesses WHERE slug = ?");
$stmt->execute([$slug]);
$business_id = $stmt->fetchColumn();

if (!$business_id) exit;

// Update live stats
$col = ($type === 'visit') ? 'views' : ($type === 'happy' ? 'happy_clicks' : 'angry_clicks');
db()->prepare("UPDATE stats SET $col = $col + 1 WHERE business_id = ?")->execute([$business_id]);

// Insert into stats_log
$col_values = [
  'views' => 0,
  'happy_clicks' => 0,
  'angry_clicks' => 0
];
$col_values[$col] = 1;

$stmt = db()->prepare("INSERT INTO stats_log (business_id, views, happy_clicks, angry_clicks, created_at)
  VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([
  $business_id,
  $col_values['views'],
  $col_values['happy_clicks'],
  $col_values['angry_clicks']
]);
