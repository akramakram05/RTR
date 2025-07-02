<?php
/* session_start(); */
include '../db.php';
include 'header_admin.php';

// Vérification accès admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header('Location: login_admin.php');
  exit;
}

// Traitement ajout/modif
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $role = $_POST['role'];

  if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  }

  // Gestion de l'avatar
  $avatarPath = null;
  if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed)) {
      $avatarPath = 'uploads/' . uniqid() . '.' . $ext;
      move_uploaded_file($_FILES['avatar']['tmp_name'], '../' . $avatarPath);
    }
  }

  if (!empty($_POST['id'])) {
    // Modification
    $sql = "UPDATE users SET nom=?, prenom=?, email=?, role=?";
    $params = [$nom, $prenom, $email, $role];

    if (!empty($password)) {
      $sql .= ", password=?";
      $params[] = $password;
    }
    if ($avatarPath) {
      $sql .= ", avatar=?";
      $params[] = $avatarPath;
    }
    $sql .= " WHERE id=?";
    $params[] = $_POST['id'];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
  } else {
    // Ajout
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role, avatar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom, $email, $password, $role, $avatarPath]);
  }
  header("Location: gerer_users.php");
  exit;
}

// Suppression
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete') {
  if ($_GET['id'] != $_SESSION['user']['id']) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
  }
  header("Location: gerer_users.php");
  exit;
}

// Récupération
$search = $_GET['search'] ?? '';
if ($search) {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE nom LIKE ? OR email LIKE ? ORDER BY id DESC");
  $stmt->execute(["%$search%", "%$search%"]);
  $users = $stmt->fetchAll();
} else {
  $users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
}

$editUser = null;
if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit') {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([$_GET['id']]);
  $editUser = $stmt->fetch();
}
?>

<h1>Gestion des comptes utilisateurs</h1>

<form method="GET" class="mb-4 d-flex">
  <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom ou email" value="<?= htmlspecialchars($search) ?>">
  <button type="submit" class="btn btn-primary">Rechercher</button>
  <?php if ($search): ?>
    <a href="gerer_users.php" class="btn btn-secondary ms-2">Réinitialiser</a>
  <?php endif; ?>
</form>

<form method="POST" enctype="multipart/form-data" class="mb-4">
  <input type="hidden" name="id" value="<?= $editUser['id'] ?? '' ?>">
  <div class="mb-3">
    <label>Nom</label>
    <input type="text" name="nom" class="form-control" required value="<?= $editUser['nom'] ?? '' ?>">
  </div>
  <div class="mb-3">
    <label>Prénom</label>
    <input type="text" name="prenom" class="form-control" required value="<?= $editUser['prenom'] ?? '' ?>">
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control" required value="<?= $editUser['email'] ?? '' ?>">
  </div>
  <div class="mb-3">
    <label>Mot de passe <?= $editUser ? "(laisser vide pour ne pas modifier)" : '' ?></label>
    <input type="password" name="password" class="form-control" <?= $editUser ? '' : 'required' ?>>
  </div>
  <div class="mb-3">
    <label>Rôle</label>
    <select name="role" class="form-control">
      <option value="utilisateur" <?= isset($editUser) && $editUser['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
      <option value="admin" <?= isset($editUser) && $editUser['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>
  </div>
  <div class="mb-3">
    <label>Avatar (optionnel)</label>
    <input type="file" name="avatar" class="form-control">
    <?php if (!empty($editUser['avatar'])): ?>
      <div class="mt-2"><img src="../<?= $editUser['avatar'] ?>" height="60"></div>
    <?php endif; ?>
  </div>
  <button class="btn btn-success"><?= $editUser ? 'Modifier' : 'Ajouter' ?></button>
  <?php if ($editUser): ?>
    <a href="gerer_users.php" class="btn btn-secondary">Annuler</a>
  <?php endif; ?>
</form>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Avatar</th>
      <th>Nom</th>
      <th>Prenom</th>
      <th>Email</th>
      <th>Rôle</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($users)): ?>
      <tr><td colspan="5">Aucun utilisateur trouvé.</td></tr>
    <?php else: ?>
      <?php foreach ($users as $u): ?>
        <!-- <?php var_dump($u); ?> -->
        <tr>
          <td>
            <?php if (!empty($u['avatar'])): ?>
              <img src="../<?= htmlspecialchars($u['avatar']) ?>" height="50">
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($u['nom']) ?></td>
          <td><?= htmlspecialchars($u['prenom']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= $u['role'] ?></td>
          <td>
            <a href="?action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
            <?php if ($u['id'] != $_SESSION['user']['id']): ?>
            <a href="?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
