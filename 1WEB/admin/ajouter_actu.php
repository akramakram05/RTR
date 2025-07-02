<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
include '../db.php';
include 'header_admin.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titre = $_POST['titre'];
  $contenu = $_POST['contenu'];

  // Upload image
  $image = '';
  if (!empty($_FILES['image']['name'])) {
    $targetDir = '../uploads/';
    $image = basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
  }

  if ($_POST['id']) {
    // Modif
    $stmt = $pdo->prepare("UPDATE actualites SET titre=?, contenu=?, image=? WHERE id=?");
    $stmt->execute([$titre, $contenu, $image, $_POST['id']]);
  } else {
    // Ajout
    $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image) VALUES (?, ?, ?)");
    $stmt->execute([$titre, $contenu, $image]);
  }
  header("Location: ../index.php");
  exit;
}

if ($action === 'delete' && $id) {
  $pdo->prepare("DELETE FROM actualites WHERE id=?")->execute([$id]);
  header("Location: ../index.php");
  exit;
}

$actualites = $pdo->query("SELECT * FROM actualites ORDER BY date DESC")->fetchAll();
?>

<h1>Gestion des actualités</h1>
<?php if ($action === 'edit' && $id):
  $actu = $pdo->prepare("SELECT * FROM actualites WHERE id=?");
  $actu->execute([$id]);
  $actu = $actu->fetch();
  ?>
  <form method="POST" enctype="multipart/form-data" class="mb-4">
    <input type="hidden" name="id" value="<?= $actu['id'] ?>">
    <div class="mb-3">
      <label>Titre</label>
      <input name="titre" class="form-control" required value="<?= htmlspecialchars($actu['titre']) ?>">
    </div>
    <div class="mb-3">
      <label>Contenu</label>
      <textarea name="contenu" class="form-control" rows="5" required><?= htmlspecialchars($actu['contenu']) ?></textarea>
    </div>
    <div class="mb-3">
      <label>Image (laisser vide pour conserver)</label>
      <input type="file" name="image" class="form-control">
      <?php if ($actu['image']): ?>
        <img src="../uploads/<?= htmlspecialchars($actu['image']) ?>" style="max-height: 150px; margin-top: 10px;">
      <?php endif; ?>
    </div>
    <button class="btn btn-primary">Modifier</button>
    <a href="actualites.php" class="btn btn-secondary">Annuler</a>
  </form>

<?php else: ?>

  <form method="POST" enctype="multipart/form-data" class="mb-4">
    <h4>Ajouter une actualité</h4>
    <div class="mb-3">
      <label>Titre</label>
      <input name="titre" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Contenu</label>
      <textarea name="contenu" class="form-control" rows="5" required></textarea>
    </div>
    <div class="mb-3">
      <label>Image</label>
      <input type="file" name="image" class="form-control">
    </div>
    <button class="btn btn-success">Ajouter</button>
  </form>

  <table class="table table-striped">
    <thead>
      <tr><th>Titre</th><th>Date</th><th>Actions</th></tr>
    </thead>
    <tbody>
      <?php foreach ($actualites as $a): ?>
        <tr>
          <td><?= htmlspecialchars($a['titre']) ?></td>
          <td><?= date('d/m/Y', strtotime($a['date'])) ?></td>
          <td>
            <a href="?action=edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
            <a href="?action=delete&id=<?= $a['id'] ?>" onclick="return confirm('Supprimer ?')" class="btn btn-sm btn-danger">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<?php endif; ?>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
