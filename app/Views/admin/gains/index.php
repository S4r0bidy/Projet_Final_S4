<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gains - Frais</title>
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

  <h1 class="mb-4">Situation des gains (frais perçus)</h1>

  <?php if (session('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm p-3">
    <?php if (empty($gains)): ?>
      <p class="text-muted">Aucune opération pour le moment. Les frais perçus apparaîtront ici.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>Type d'opération</th>
              <th>Nombre d'opérations</th>
              <th>Total des frais</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($gains as $g): ?>
            <tr>
              <td><?= esc($g['type_operation'] ?? '') ?></td>
              <td><?= (int)($g['nb_operations'] ?? 0) ?></td>
              <td><?= number_format((float)($g['total_frais'] ?? 0), 2, ',', ' ') ?> Ar</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="h3 text-success fw-bold mt-3">
        Total : <?= number_format((float)($totalGains ?? 0), 2, ',', ' ') ?> Ar
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>

