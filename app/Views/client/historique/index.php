<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Historique</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); }
    table{width:100%; border-collapse:collapse; margin-top:12px;}
    th,td{border-bottom:1px solid #eee; padding:10px; text-align:left; font-size:14px;}
  </style>
</head>
<body>
<nav style="max-width:900px; margin:0 auto;">
  <a href="/client/dashboard">Dashboard</a>
  <a href="/client/depot">Dépôt</a>
  <a href="/client/retrait">Retrait</a>
  <a href="/client/transfert">Transfert</a>
  <a href="/client/historique">Historique</a>
  <a href="/client/logout" style="float:right;">Déconnexion</a>
</nav>


<div class="card" style="max-width:1100px; margin:14px auto;">
  <h1>Historique des opérations</h1>


  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Référence</th>
        <th>Type</th>
        <th>Source</th>
        <th>Destination</th>
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
          <td><?= number_format((float)($o['montant'] ?? 0), 2, ',', ' ') ?></td>
          <td><?= number_format((float)($o['frais'] ?? 0), 2, ',', ' ') ?></td>
          <td><?= number_format((float)($o['montant_total'] ?? 0), 2, ',', ' ') ?></td>
          <td><?= esc($o['statut'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
