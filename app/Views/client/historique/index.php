<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Historique</title>
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/client.css">
</head>
<body>
<nav class="client-nav">
  <a href="/client/dashboard">Dashboard</a>
  <a href="/client/depot">Dépôt</a>
  <a href="/client/retrait">Retrait</a>
  <a href="/client/transfert">Transfert</a>
  <a href="/client/historique">Historique</a>
  <a href="/client/logout" style="float:right;">Déconnexion</a>
</nav>

<div class="card historique-card">
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
