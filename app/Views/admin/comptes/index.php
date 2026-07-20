<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Situation des comptes</title>
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

  <h1 class="mb-4">Situation des comptes clients</h1>

  <?php if (session('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm p-3">
    <?php if (empty($comptes)): ?>
      <p class="text-muted">Aucun compte client pour le moment.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>Numéro</th>
              <th>Opérateur</th>
              <th>Solde</th>
              <th>Statut</th>
              <th>Nb Opérations</th>
              <th>Date création</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($comptes as $c): ?>
            <tr>
              <td>
                <a href="/admin/comptes/<?= (int)$c['id'] ?>"><?= esc($c['numero_telephone']) ?></a>
              </td>
              <td><?= esc($c['operateur_telecom'] ?? '-') ?></td>
              <td><?= number_format((float)($c['solde'] ?? 0), 2, ',', ' ') ?> Ar</td>
              <td>
                <?php if (($c['statut'] ?? '') === 'ACTIF'): ?>
                  <span class="badge bg-success">Actif</span>
                <?php else: ?>
                  <span class="badge bg-danger">Bloqué</span>
                <?php endif; ?>
              </td>
              <td><?= (int)($c['nb_operations'] ?? 0) ?></td>
              <td><?= esc($c['date_creation'] ?? '') ?></td>
              <td>
                <?php if (($c['statut'] ?? '') === 'ACTIF'): ?>
                  <form method="post" action="/admin/comptes/<?= (int)$c['id'] ?>/bloquer" style="display:inline;">
                    <button class="btn btn-danger btn-sm" type="submit">Bloquer</button>
                  </form>
                <?php else: ?>
                  <form method="post" action="/admin/comptes/<?= (int)$c['id'] ?>/debloquer" style="display:inline;">
                    <button class="btn btn-success btn-sm" type="submit">Débloquer</button>
                  </form>
                <?php endif; ?>
              </td>
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

