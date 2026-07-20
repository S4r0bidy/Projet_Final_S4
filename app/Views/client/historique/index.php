<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Historique</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
<div class="wrap p-3">
  <nav class="navbar navbar-expand navbar-light bg-light rounded mb-3 shadow-sm">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Mon Compte</span>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="/client/dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/depot">Depot</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/retrait">Retrait</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/transfert">Transfert</a></li>
          <li class="nav-item"><a class="nav-link active" href="/client/historique">Historique</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/client/logout">Deconnexion</a></li>
        </ul>
      </div>
  </nav>


  <div class="card shadow-sm p-3">
    <h1 class="h3 mb-3">Historique des operations</h1>


    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Reference</th>
            <th>Type</th>
            <th>Source</th>
            <th>Destination</th>
            <th>Operateur dst.</th>
            <th>Type transfert</th>
            <th>Montant</th>
            <th>Frais</th>
            <th>Total</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach (($historique ?? []) as $o): ?>
            <tr>
              <td><?= esc($o['date_operation'] ?? '') ?></td>
              <td><?= esc($o['reference'] ?? '') ?></td>
              <td><?= esc($o['type_operation'] ?? '') ?></td>
              <td><?= esc($o['numero_source'] ?? '') ?></td>
              <td><?= esc($o['numero_destination'] ?? '') ?></td>
              <td>
                <?php if (!empty($o['operateur_destination'])): ?>
                  <span class="badge bg-info"><?= esc($o['operateur_destination']) ?></span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($o['type_transfert'] ?? '' === 'INTERNE'): ?>
                  <span class="badge bg-success">Interne</span>
                <?php elseif ($o['type_transfert'] ?? '' === 'EXTERNE'): ?>
                  <span class="badge bg-warning text-dark">Externe</span>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td><?= number_format((float)($o['montant'] ?? 0), 2, ',', ' ') ?></td>
              <td><?= number_format((float)($o['frais'] ?? 0), 2, ',', ' ') ?></td>
              <td><?= number_format((float)($o['montant_total'] ?? 0), 2, ',', ' ') ?></td>
              <td>
                <?php if (($o['statut'] ?? '') === 'REUSSI'): ?>
                  <span class="badge bg-success">Reussi</span>
                <?php else: ?>
                  <span class="badge bg-danger">Echec</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
</div>
</body>
</html>
