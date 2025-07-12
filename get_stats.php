<?php
require 'config.php';

$bid = intval($_GET['bid'] ?? 0);
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

if (!$bid || !$from || !$to) {
  http_response_code(400);
  echo json_encode(['error' => 'Missing parameters']);
  exit;
}

$labels = [];
$views = [];
$happy = [];
$angry = [];
$messages = [];

try {
  // Build date range array
  $start = new DateTime($from);
  $end = new DateTime($to);
  $end->modify('+1 day'); // include end date
  $interval = new DateInterval('P1D');
  $period = new DatePeriod($start, $interval, $end);

  // Init blank arrays
  foreach ($period as $dt) {
    $d = $dt->format("Y-m-d");
    $labels[] = $d;
    $views[$d] = 0;
    $happy[$d] = 0;
    $angry[$d] = 0;
    $messages[$d] = 0;
  }

  // Aggregate stats
  $stmt = db()->prepare("SELECT DATE(created_at) as d, COUNT(*) as c FROM messages WHERE business_id=? AND created_at BETWEEN ? AND ? GROUP BY d");
  $stmt->execute([$bid, $from, $to . ' 23:59:59']);
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $messages[$r['d']] = (int)$r['c'];
  }

  $stmt = db()->prepare("SELECT DATE(created_at) as d, SUM(views) as v, SUM(happy_clicks) as h, SUM(angry_clicks) as a FROM stats_log WHERE business_id=? AND created_at BETWEEN ? AND ? GROUP BY d");
  $stmt->execute([$bid, $from, $to . ' 23:59:59']);
  foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $views[$r['d']] = (int)$r['v'];
    $happy[$r['d']] = (int)$r['h'];
    $angry[$r['d']] = (int)$r['a'];
  }

  echo json_encode([
    'labels' => $labels,
    'views' => array_values($views),
    'happy_clicks' => array_values($happy),
    'angry_clicks' => array_values($angry),
    'messages' => array_values($messages)
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'DB error']);
}
