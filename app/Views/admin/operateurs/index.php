<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Opérateurs</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
<div class="wrap p-3">
  <nav class="navbar navbar-expand navbar-light bg-light rounded mb-3 shadow-sm">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Admin</span>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/prefixes">Préfixes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/operateurs">Opérateurs</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/baremes">Barèmes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/comptes">Comptes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/gains">Gains</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/admin/logout">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <h1 class="mb-4">Opérateurs télécom</h1>

  <div class="card shadow-sm p-3">
    <?php if (session('message')): ?>
      <div class="alert alert-success"><?= esc(session('message')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <h3>Ajouter un opérateur</h3>
    <form method="post" action="/admin/operateurs/create" class="row g-3 mt-1 mb-4">
      <div class="col-auto">
        <label class="form-label">Nom de l'opérateur</label>
        <input name="nom" class="form-control" required placeholder="Orange, Airtel, Yas...">
      </div>
      <div class="col-auto d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Créer</button>
      </div>
    </form>

    <h3>Liste des opérateurs</h3>
    <?php if (empty($operateurs)): ?>
      <p class="text-muted">Aucun opérateur enregistré.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr><th>ID</th><th>Nom</th><th>Date de création</th></tr>
          </thead>
          <tbody>
          <?php foreach ($operateurs as $op): ?>
            <tr>
              <td><?= (int)$op['id'] ?></td>
              <td><?= esc($op['nom']) ?></td>
              <td><?= esc($op['created_at'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>

