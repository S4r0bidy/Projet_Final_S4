<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Détail du compte</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); margin-top:14px; }
    table{width:100%; border-collapse:collapse; margin-top:12px; }
    th,td{border-bottom:1px solid #eee; padding:10px; text-align:left; }
    .badge{display:inline-block; padding:4px 10px; border-radius:20px; font-size:12px; }
    .actif{background:#e8f5e9; color:#2e7d32; }
    .bloque{background:#ffebee; color:#c62828; }
    .value { font-size:28px; font-weight:700; }
  </style>
</head>
<body>
<nav>
  <a href="/admin/dashboard">Accueil</a>
  <a href="/admin/prefixes">Préfixes</a>
  <a href="/admin/operateurs">Opérateurs</a>
  <a href="/admin/baremes">Barèmes</a>
  <a href="/admin/comptes">Comptes</a>
  <a href="/admin/gains">Gains</a>
  <a href="/admin/logout" style="float:right; margin-right:0;">Déconnexion</a>
</nav>

<h1>Détail du compte</h1>

<div class="card">
  <p><strong>Numéro :</strong> <?= esc($compte['numero_telephone'] ?? '') ?></p>
  <p><strong>Statut :</strong>
    <?php if (($compte['statut'] ?? '') === 'ACTIF'): ?>
      <span class="badge actif">Actif</span>
    <?php else: ?>
      <span class="badge bloque">Bloqué</span>
    <?php endif; ?>
  </p>
  <p><strong>Date de création :</strong> <?= esc($compte['date_creation'] ?? '') ?></p>

  <div class="value"><?= number_format((float)($compte['solde'] ?? 0), 2, ',', ' ') ?> Ar</div>
  <p>Solde actuel</p>
</div>

<div class="card">
  <h3>Historique des opérations</h3>

  <?php if (empty($historique)): ?>
    <p>Aucune opération sur ce compte.</p>
  <?php else: ?>
    <table>
      <thead>
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
  <?php endif; ?>

  <br>
  <a href="/admin/comptes">Retour à la liste</a>
</div>
</body>
</html>

