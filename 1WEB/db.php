<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

+ $host = 'MyDatabase'; $db = 'archeo'; $user = 'etu';  $pass = 'password';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Connexion DB impossible : ' . $e->getMessage());
}
?>

