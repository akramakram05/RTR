<?php
/* session_start(); */
include '../db.php';

// Vérification accès admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login_admin.php');
    exit;
  }
  
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  try {
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, is_admin) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$nom, $prenom, $email, $password]);
    $message = "Admin ajouté avec succès.";
  } catch (Exception $e) {
    $message = "Erreur : " . $e->getMessage();
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'header_admin.php'; ?>
<div class="container mt-4">
  <h1>Créer un compte administrateur</h1>
  <?php if (!empty($message)) echo "<div class='alert alert-info'>$message</div>"; ?>
  <form method="post">
    <input name="nom" placeholder="Nom" required class="form-control mb-2">
    <input name="prenom" placeholder="Prénom" required class="form-control mb-2">
    <input name="email" type="email" placeholder="Email" required class="form-control mb-2">
    <input name="password" type="password" placeholder="Mot de passe" required class="form-control mb-2">
    <button class="btn btn-primary">Créer admin</button>
  </form>
</div>
</body>
</html>
