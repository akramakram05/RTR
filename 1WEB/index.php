<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

try {
    // Exécution de la requête pour récupérer les 3 dernières actualités
    $stmt = $pdo->query("SELECT * FROM actualites ORDER BY date DESC LIMIT 3");
    $actus = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (PDOException $e) {
    // En cas d'erreur SQL, affichage du message et initialisation à tableau vide
    die("Erreur SQL : " . htmlspecialchars($e->getMessage()));
    $actus = [];
}

// Si $actus n'est pas défini ou n’est pas un tableau, on lraine en tableau vide
if (!isset($actus) || !is_iterable($actus)) {
    $actus = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link href="style.css" rel="stylesheet"> -->
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-4">
  <h1>Actualités récentes</h1>
  <div class="row">
    <?php if (count($actus) > 0): ?>
      <?php foreach ($actus as $actu): ?>
        <div class="col-md-4">
          <div class="card mb-4">
            <?php if (!empty($actu['image']) && file_exists(__DIR__ . '/uploads/' . $actu['image'])): ?>
              <img src="uploads/<?= htmlspecialchars($actu['image']) ?>" class="card-img-top" alt="Image de <?= htmlspecialchars($actu['titre']) ?>">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($actu['titre']) ?></h5>
              <p class="card-text"><?= htmlspecialchars(substr(strip_tags($actu['contenu']), 0, 100)) ?>…</p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info">Aucune actualité disponible pour le moment.</div>
      </div>
    <?php endif; ?>
  </div>
  <?php if (!isset($_SESSION['user'])): ?>
      <div class="alert alert-info text-center">
        <a href="login.php" class="alert-link stretched-link">
        Connectez-vous pour voir toutes les actualités de l'association.
        </a>
      </div>
    <?php endif; ?>
</div>
<!-- Pied de page -->
<footer class="bg-dark text-white text-center py-3 mt-5">
    &copy; <?= date('Y') ?> Association Archéo - Tous droits réservés
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>