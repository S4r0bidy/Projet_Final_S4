<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Détail du compte</title>
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
    </div>
  </nav>

  <h1 class="mb-4">Détail du compte</h1>

  <div class="card shadow-sm p-3 mb-3">
    <div class="row">
      <div class="col-md-6">
        <p><strong>Numéro :</strong> <?= esc($compte['numero_telephone'] ?? '') ?></p>
        <p><strong>Statut :</strong>
          <?php if (($compte['statut'] ?? '') === 'ACTIF'): ?>
            <span class="badge bg-success">Actif</span>
          <?php else: ?>
            <span class="badge bg-danger">Bloqué</span>
          <?php endif; ?>
        </p>
        <p><strong>Date de création :</strong> <?= esc($compte['date_creation'] ?? '') ?></p>
      </div>
      <div class="col-md-6 text-md-end">
        <div class="display-6 fw-bold text-primary"><?= number_format((float)($compte['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
        <p class="text-muted">Solde actuel</p>
      </div>
    </div>
  </div>

  <div class="card shadow-sm p-3">
    <h3>Historique des opérations</h3>

    <?php if (empty($historique)): ?>
      <p class="text-muted">Aucune opération sur ce compte.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>Référence</th>
              <th>Type</th>
              <th>Montant</th>
              <th>Frais</th>
              <th>Statut</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($historique as $op): ?>
            <tr>
              <td><?= esc($op['reference'] ?? '') ?></td>
              <td><?= esc($op['type_operation'] ?? '') ?></td>
              <td><?= number_format((float)($op['montant'] ?? 0), 2, ',', ' ') ?> Ar</td>
              <td><?= number_format((float)($op['frais'] ?? 0), 2, ',', ' ') ?> Ar</td>
              <td><?= esc($op['statut'] ?? '') ?></td>
              <td><?= esc($op['date_operation'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <div class="mt-3">
      <a href="/admin/comptes" class="btn btn-outline-secondary">&larr; Retour à la liste</a>
    </div>
  </div>
</div>
</body>
</html>

