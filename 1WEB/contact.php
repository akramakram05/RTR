<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
include 'db.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $sujet = $_POST['sujet'];
  $message = $_POST['message'];

  $stmt = $pdo->prepare("INSERT INTO contacts(nom,prenom,email,sujet,message) VALUES(?,?,?,?,?)");
  $stmt->execute([$_POST['nom'],$_POST['prenom'],$_POST['email'],$_POST['sujet'],$_POST['message']]);
  $confirmation = 'Merci! Votre message a été envoyé.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'header.php'; ?>
  <div class="container mt-5">
    <h1>Contactez-nous</h1>
    <?php if(!empty($confirmation)) echo "<div class='alert alert-success'>$confirmation</div>"; ?>
    <form method="POST">
      <div class="row mb-3">
       <div class="col"><input name="nom" placeholder="Nom" required class="form-control"></div>
       <div class="col"><input name="prenom" placeholder="Prénom" required class="form-control"></div>
      </div>
      <div class="mb-3"><input name="email" type="email" placeholder="Email" required class="form-control"></div>
      <div class="mb-3">
      <select name="sujet" class="form-select" required>
        <option value="">---- Sujet ----</option>
        <option>Demande d'infos</option>
        <option>Demande de Rendez-vous</option>
        <option>autre</option>
      </select>
      </div>
      <div class="mb-3"><textarea name="message" rows="5" placeholder="Votre message" class="form-control" required></textarea></div>
      <button class="btn btn-primary">Envoyer</button>
    </form>
  </div>
</body>
<footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; <?= date('Y') ?> Association Archéo - Tous droits réservés
</footer>
</html>
