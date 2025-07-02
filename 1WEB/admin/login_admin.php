<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 
include '../db.php';

$error = '';

// Rediriger un admin déjà connecté
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pwd = $_POST['password'];  

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pwd, $user['password'])) {
        // Vérifie si c’est bien un admin
        if ($user['role'] === 'admin') {
            // Corriger si les champs sont inversés (patch temporaire)
            if (filter_var($user['prenom'], FILTER_VALIDATE_EMAIL) && !filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                $tmp = $user['email'];
                $user['email'] = $user['prenom'];
                $user['prenom'] = $tmp;
            }

            $_SESSION['user'] = $user;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Accès réservé à l\'administration.';
        }
    } else {
        $error = 'Email ou mot de passe invalide';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion:Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Admin — Connexion</h1>
    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3"><input name="email" type="email" placeholder="Email" required class="form-control"></div>
        <div class="mb-3"><input name="password" type="password" placeholder="Mot de passe" required class="form-control"></div>
        <button class="btn btn-primary">Connexion</button>
    </form>
    <p class="mt-3">Pas encore de compte ? <a href="inscription_admin.php">Inscrivez-vous ici</a>.</p>
</div>
<footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; <?= date('Y') ?> Association Archéo - Tous droits réservés
</footer>
</body>
</html>

  