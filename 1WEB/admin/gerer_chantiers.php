<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>


<?php
// chantiers.php - Gestion admin des chantiers

include '../db.php';
include 'header_admin.php';

// Récupération des paramètres
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// Traitement formulaire POST (ajout / modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lieu = $_POST['lieu'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    // Upload image
    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $targetDir = '../uploads/';
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
    }

    if (!empty($_POST['id'])) {
        // Modification
        $stmt = $pdo->prepare("UPDATE chantiers SET lieu=?, description=?, date_debut=?, date_fin=?, image=? WHERE id=?");
        $stmt->execute([$lieu, $description, $date_debut, $date_fin, $image, $_POST['id']]);
    } else {
        // Ajout
        $stmt = $pdo->prepare("INSERT INTO chantiers (lieu, description, date_debut, date_fin, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$lieu, $description, $date_debut, $date_fin, $image]);
    }
    header("Location: chantiers.php");
    exit;
}

// Suppression
if ($action === 'delete' && $id) {
    $pdo->prepare("DELETE FROM chantiers WHERE id=?")->execute([$id]);
    header("Location: chantiers.php");
    exit;
}

// Récupérer tous les chantiers pour affichage
$chantiers = $pdo->query("SELECT * FROM chantiers ORDER BY date_debut DESC")->fetchAll();

?>

<!-- Sortie du PHP vers HTML -->

<h1>Gestion des chantiers</h1>

<?php if ($action === 'edit' && $id): 
    // Récupérer le chantier à modifier
    $chantier = $pdo->prepare("SELECT * FROM chantiers WHERE id=?");
    $chantier->execute([$id]);
    $chantier = $chantier->fetch();
    ?>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" name="id" value="<?= htmlspecialchars($chantier['id']) ?>">
        <div class="mb-3">
            <label>Lieu</label>
            <input name="lieu" class="form-control" required value="<?= htmlspecialchars($chantier['lieu']) ?>">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($chantier['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control" required value="<?= htmlspecialchars($chantier['date_debut']) ?>">
        </div>
        <div class="mb-3">
            <label>Date fin</label>
            <input type="date" name="date_fin" class="form-control" required value="<?= htmlspecialchars($chantier['date_fin']) ?>">
        </div>
        <div class="mb-3">
            <label>Image (laisser vide pour conserver)</label>
            <input type="file" name="image" class="form-control">
            <?php if ($chantier['image']): ?>
                <img src="../uploads/<?= htmlspecialchars($chantier['image']) ?>" style="max-height: 150px; margin-top: 10px;" alt="Image du chantier">
            <?php endif; ?>
        </div>
        <button class="btn btn-primary">Modifier</button>
        <a href="chantiers.php" class="btn btn-secondary">Annuler</a>
    </form>

<?php else: ?>

    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <h4>Ajouter un chantier</h4>
        <div class="mb-3">
            <label>Lieu</label>
            <input name="lieu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Date fin</label>
            <input type="date" name="date_fin" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-success">Ajouter</button>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Lieu</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chantiers as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['lieu']) ?></td>
                    <td><?= date('d/m/Y', strtotime($c['date_debut'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
                    <td>
                        <a href="?action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="?action=delete&id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce chantier ?')" class="btn btn-sm btn-danger">Supprimer</a>
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
