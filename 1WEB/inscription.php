<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
session_start();
include 'db.php';

$errors = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email  = $_POST['email'];
    $mode   = $_POST['mode'];

    // Génération du mot de passe
    $pwd  = trim(shell_exec("python3 generate_password.py $mode"));
    $hash = password_hash($pwd, PASSWORD_DEFAULT);

    try {
        // Insertion en base
        $stmt = $pdo->prepare("INSERT INTO users(nom,prenom,email,password) VALUES (?,?,?,?)");
        $stmt->execute([$nom, $prenom, $email, $hash]);

        // Envoi du mail
        $subject = 'Mot de passe – Association Archéo';
        $message = "Bonjour $prenom,\n\nVotre mot de passe : $pwd\n\nConnectez-vous ici : http://archeoit.com/index.php";
        $headers = "From: no-reply@archeoit.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            header('Location: index.php?msg=mail-envoye');
            exit;
        } else {
            $errors = 'Impossible d’envoyer l’email.';
            header('Location: index.php?msg=mail-envoye');
        }

    } catch (Exception $e) {
        $errors = 'Erreur ou email déjà utilisé';
        header('Location: index.php?msg=mail-envoye');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- <?php include 'header.php'; ?> -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Association Archéo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</nav>
 <div class="container mt-5">
    <h1>Inscription</h1>
    <?php if ($errors): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($errors) ?></div>
    <?php endif; ?>

    <form method="post">
       <div class="row mb-3">
         <div class="col"><input name="nom" placeholder="Nom" required class="form-control"></div>
         <div class="col"><input name="prenom" placeholder="Prénom" required class="form-control"></div>
       </div>
       <div class="mb-3">
         <input name="email" type="email" placeholder="Email" required class="form-control">
       </div>
       <div class="mb-3">
         <select name="mode" class="form-select" required>
            <option value="">-- Type de mot de passe --</option>
            <option value="alpha">Alphabetique</option>
            <option value="alnum">Alphanumérique</option>
            <option value="special">Alphanumérique + spéciaux</option>
         </select>
       </div>
       <button class="btn btn-primary">S’inscrire</button>
    </form>
 </div>
</body>
<footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; <?= date('Y') ?> Association Archéo - Tous droits réservés
</footer>
</html>
