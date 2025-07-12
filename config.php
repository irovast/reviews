<?php
define('DB_HOST','localhost');
define('DB_USER','irovimiy_review');
define('DB_PASS','irovimiy_review');
define('DB_NAME','irovimiy_review');

function db(){
  static $pdo = null;
  if (!$pdo){
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS,[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
  }
  return $pdo;
}
