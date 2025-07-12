<?php
require 'config.php';

// Sanitize input
$name     = htmlspecialchars($_POST['name'] ?? '');
$phone    = htmlspecialchars($_POST['phone'] ?? '');
$rating   = intval($_POST['rating'] ?? 0);
$comment  = htmlspecialchars($_POST['comment'] ?? '');
$to       = filter_var($_POST['toEmail'] ?? '', FILTER_VALIDATE_EMAIL);
$ip       = $_POST['ip'] ?? $_SERVER['REMOTE_ADDR'];
$device   = $_POST['device'] ?? '';
$business_id  = filter_var($_POST['bid'] ?? '');
$location = $_POST['location'] ?? '';

// Validation
if (!$name || !$phone || !$rating || !$comment || !$to) {
  http_response_code(400);
  exit("Missing fields");
}

// Send Email
$subject = "New Website Review $name";
$message = "Name: $name\nPhone: $phone\nRating: $rating/5\n\n$comment\n\n"
         . "IP: $ip\nDevice: $device\nLocation: $location\n";
$headers = "From: no-reply@" . $_SERVER['SERVER_NAME'] . "\r\nContent-Type: text/plain; charset=utf-8\r\n";

$sent = mail($to, $subject, $message, $headers);

// Save to DB
try {


  if ($business_id) {
    $stmt = db()->prepare("INSERT INTO messages 
      (business_id, name, email, message, ip, device, location, created_at) 
      VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
      $business_id,
      $name,
      $phone,
      $comment,
      $ip,
      $device,
      $location
    ]);
  }
} catch (Exception $e) {
  // Optional: log $e->getMessage()
}

http_response_code($sent ? 200 : 500);
echo $sent ? 'ok' : 'fail';
