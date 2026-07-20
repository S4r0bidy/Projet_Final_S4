<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Commissions</title>
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
          <li class="nav-item"><a class="nav-link" href="/admin/commissions">Commissions</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/baremes">Barèmes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/comptes">Comptes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/gains">Gains</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/situation-operateurs">Situation</a></li>
          <li class="nav-item"><a class="nav-link active" href="/admin/statistiques">Statistiques</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/admin/logout">Déconnexion</a></li>
        </ul>
      </div>
  </nav>

  <h1 class="mb-4">Commissions inter-opérateurs</h1>

  <div class="card shadow-sm p-3">
    <?php if (session('message')): ?>
      <div class="alert alert-success"><?= esc(session('message')) ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
      <div class="alert alert-danger"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <h3>Ajouter une commission</h3>
    <form method="post" action="/admin/commissions/create" class="row g-3 mt-1 mb-4">
      <div class="col-auto">
        <label class="form-label">Opérateur destination</label>
        <select name="operateur_destination_id" class="form-select" required>
          <option value="">Sélectionner...</option>
          <?php foreach (($operateurs ?? []) as $op): ?>
            <option value="<?= (int)$op['id'] ?>"><?= esc($op['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-auto">
        <label class="form-label">Pourcentage (%)</label>
        <input type="number" step="0.01" min="0.01" name="pourcentage" class="form-control" required placeholder="2.5" style="width:120px;">
      </div>
      <div class="col-auto d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Ajouter</button>
      </div>
    </form>

    <h3>Liste des commissions</h3>
    <?php if (empty($commissions)): ?>
      <p class="text-muted">Aucune commission configurée.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr><th>Opérateur</th><th>Pourcentage</th><th>Statut</th><th>Date création</th><th>Actions</th></tr>
          </thead>
          <tbody>
          <?php foreach ($commissions as $c): ?>
            <tr>
              <td><?= esc($c['operateur_nom'] ?? '') ?></td>
              <td><?= number_format((float)$c['pourcentage'], 2, ',', ' ') ?> %</td>
              <td>
                <?php if ((int)$c['actif'] === 1): ?>
                  <span class="badge bg-success">Actif</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Inactif</span>
                <?php endif; ?>
              </td>
              <td><?= esc($c['created_at'] ?? '') ?></td>
              <td>
                <a href="/admin/commissions/<?= (int)$c['id'] ?>/edit" class="btn btn-warning btn-sm">Modifier</a>
                <form method="post" action="/admin/commissions/<?= (int)$c['id'] ?>/toggle" style="display:inline;">
                  <button type="submit" class="btn btn-outline-primary btn-sm">
                    <?= ((int)$c['actif'] === 1) ? 'Désactiver' : 'Activer' ?>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
