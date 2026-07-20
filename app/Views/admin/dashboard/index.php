<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .wrap { max-width:1000px; margin:0 auto; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); margin-top:14px; }
    .grid { display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-top:14px; }
    .stat { background:#1e88e5; color:#fff; padding:20px; border-radius:10px; text-align:center; }
    .stat .nb { font-size:32px; font-weight:700; }
    table{width:100%; border-collapse:collapse; margin-top:10px; }
    th,td{border-bottom:1px solid #eee; padding:10px; text-align:left; }
    .message{color:#2e7d32;}
    .error{color:#c62828;}
  </style>
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

  <div class="grid">
    <div class="stat">
      <div class="nb"><?= (int)($nbClients ?? 0) ?></div>
      <div>Total clients</div>
    </div>
    <div class="stat" style="background:#2e7d32;">
      <div class="nb"><?= (int)($nbComptesActifs ?? 0) ?></div>
      <div>Comptes actifs</div>
    </div>
    <div class="stat" style="background:#c62828;">
      <div class="nb"><?= (int)($nbComptesBloques ?? 0) ?></div>
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

