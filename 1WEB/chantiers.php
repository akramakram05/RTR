<?php
// Affiche toutes les erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'db.php';

// Récupération des chantiers
try {
  $chantiers = $pdo->query("SELECT * FROM chantiers ORDER BY date_debut DESC")->fetchAll();
} catch (PDOException $e) {
  die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Chantiers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link href="style.css" rel="stylesheet"> -->
 
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
  <h1 class="mb-4">Nos chantiers</h1>
  <div class="row">
      <?php foreach($chantiers as $c): ?>
        <div class="col-md-4">
          <div class="card mb-4">
            <img src="uploads/<?= htmlspecialchars($c['image']) ?>" class="card-img-top" alt="Image chantier">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($c['lieu']) ?></h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($c['description'])) ?></p>
              <p><small>Du <?= date('d/m/Y', strtotime($c['date_debut'])) ?> au <?= date('d/m/Y', strtotime($c['date_fin'])) ?></small></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
  &copy; <?= date('Y') ?> Association Archéo - Tous droits réservés
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
