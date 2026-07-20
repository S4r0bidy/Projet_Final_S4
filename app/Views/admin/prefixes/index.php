<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Préfixes</title>
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

  <h1 class="mb-4">Préfixes valables</h1>

  <div class="card shadow-sm p-3">
    <?php if (session('message')): ?>
      <div class="alert alert-success"><?= esc(session('message')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <h3>Ajouter un préfixe</h3>
    <form method="post" action="/admin/prefixes/create" class="row g-3 mt-1 mb-4">
      <div class="col-auto">
        <label class="form-label">Préfixe</label>
        <input name="prefixe" class="form-control" required placeholder="033, 037" style="width:200px;">
      </div>
      <div class="col-auto">
        <label class="form-label">Opérateur</label>
        <select name="operateur_telecom_id" class="form-select" required>
          <option value="">Sélectionner...</option>
          <?php foreach (($operateurs ?? []) as $op): ?>
            <option value="<?= (int)$op['id'] ?>"><?= esc($op['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Créer</button>
      </div>
    </form>

    <h3>Liste</h3>
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-light">
          <tr><th>Préfixe</th><th>Opérateur</th><th>Statut</th><th>Action</th></tr>
        </thead>
        <tbody>
        <?php foreach (($prefixes ?? []) as $p): ?>
          <tr>
            <td><?= esc($p['prefixe']) ?></td>
            <td><?= esc($p['operateur_nom'] ?? '') ?></td>
            <td>
              <?php if ((int)$p['actif'] === 1): ?>
                <span class="badge bg-success">Actif</span>
              <?php else: ?>
                <span class="badge bg-secondary">Inactif</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="/admin/prefixes/<?= (int)$p['id'] ?>/edit" class="btn btn-warning btn-sm">Modifier</a>
              <form method="post" action="/admin/prefixes/<?= (int)$p['id'] ?>/toggle" style="display:inline;">
                <button type="submit" class="btn btn-outline-primary btn-sm">Basculer</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>

