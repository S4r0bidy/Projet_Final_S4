<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Situation des opérateurs</title>
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

  <h1 class="mb-4">Situation des montants à envoyer aux opérateurs</h1>

  <?php if (session('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm p-3">
    <?php if (empty($situations)): ?>
      <p class="text-muted">Aucun transfert effectué pour le moment.</p>
    <?php else: ?>
      <div class="row g-3 mb-4">
        <?php 
        $totalGeneral = 0;
        foreach ($situations as $s): 
          $totalGeneral += (float)$s['montant_total'];
        ?>
          <div class="col-md-3">
            <div class="card text-white bg-info p-3 text-center">
              <div class="h5"><?= esc($s['operateur_nom'] ?? '') ?></div>
              <div class="display-6 fw-bold"><?= number_format((float)$s['montant_total'], 2, ',', ' ') ?> Ar</div>
              <div><?= (int)$s['nb_transferts'] ?> transfert(s)</div>
              <small>Dernier : <?= esc($s['derniere_operation'] ?? '') ?></small>
            </div>
        <?php endforeach; ?>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>Opérateur</th>
              <th>Nombre de transferts</th>
              <th>Montant total</th>
              <th>Dernière opération</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($situations as $s): ?>
            <tr>
              <td><strong><?= esc($s['operateur_nom'] ?? '') ?></strong></td>
              <td><?= (int)$s['nb_transferts'] ?></td>
              <td><?= number_format((float)$s['montant_total'], 2, ',', ' ') ?> Ar</td>
              <td><?= esc($s['derniere_operation'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="h3 text-info fw-bold mt-3">
        Total à envoyer : <?= number_format($totalGeneral, 2, ',', ' ') ?> Ar
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
