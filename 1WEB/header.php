<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
  <li><a href="/admin/dashboard.php">Admin</a></li>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Association Archéo</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="chantiers.php">Chantiers</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
        <?php if (isset($_SESSION['user'])): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
        <?php endif; ?>
      </ul>
      <?php if (isset($_SESSION['user'])): ?>
        <span class="navbar-text text-white">Bienvenue, <?= htmlspecialchars($_SESSION['user']) ?></span>
      <?php endif; ?>
    </div>
  </div>
</nav>
