<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

try{
$host = 'localhost'; $db = 'archeo'; $user = 'root'; $pass = 'root';
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}
?>
