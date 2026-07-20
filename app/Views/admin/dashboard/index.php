<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
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

  <?php if (session('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <h1 class="mb-4">Tableau de bord administrateur</h1>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card text-white bg-primary p-3 text-center">
        <div class="display-6 fw-bold"><?= (int)($nbClients ?? 0) ?></div>
        <div>Total clients</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-success p-3 text-center">
        <div class="display-6 fw-bold"><?= (int)($nbComptesActifs ?? 0) ?></div>
        <div>Comptes actifs</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-danger p-3 text-center">
        <div class="display-6 fw-bold"><?= (int)($nbComptesBloques ?? 0) ?></div>
        <div>Comptes bloqués</div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm p-3">
    <h3>Dernières opérations</h3>
    <?php if (empty($dernieresOperations)): ?>
      <p class="text-muted">Aucune opération pour le moment.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr><th>Réf.</th><th>Type</th><th>Montant</th><th>Frais</th><th>Date</th></tr>
          </thead>
          <tbody>
          <?php foreach ($dernieresOperations as $op): ?>
            <tr>
              <td><?= esc($op['reference'] ?? '') ?></td>
              <td><?= esc($op['type_operation_id'] ?? '') ?></td>
              <td><?= number_format((float)($op['montant'] ?? 0), 2, ',', ' ') ?></td>
              <td><?= number_format((float)($op['frais'] ?? 0), 2, ',', ' ') ?></td>
              <td><?= esc($op['date_operation'] ?? '') ?></td>
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

