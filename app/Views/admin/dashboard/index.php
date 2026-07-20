<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
<div class="wrap">
  <nav>
    <a href="/admin/dashboard">Accueil</a>
    <a href="/admin/prefixes">Préfixes</a>
    <a href="/admin/operateurs">Opérateurs</a>
    <a href="/admin/baremes">Barèmes</a>
    <a href="/admin/comptes">Comptes</a>
    <a href="/admin/gains">Gains</a>
    <a href="/admin/logout" style="float:right; margin-right:0;">Déconnexion</a>
  </nav>

  <?php if (session('message')): ?>
    <div class="message"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="error"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <h1>Tableau de bord administrateur</h1>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-number"><?= (int)($nbClients ?? 0) ?></div>
      <div>Total clients</div>
    </div>
    <div class="stat-card stat-success">
      <div class="stat-number"><?= (int)($nbComptesActifs ?? 0) ?></div>
      <div>Comptes actifs</div>
    </div>
    <div class="stat-card stat-danger">
      <div class="stat-number"><?= (int)($nbComptesBloques ?? 0) ?></div>
      <div>Comptes bloqués</div>
    </div>
  </div>

  <div class="card">
    <h3>Dernières opérations</h3>
    <?php if (empty($dernieresOperations)): ?>
      <p>Aucune opération pour le moment.</p>
    <?php else: ?>
      <table>
        <thead>
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
    <?php endif; ?>
  </div>
</div>
</body>
</html>

