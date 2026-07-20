<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gains - Frais</title>
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/admin.css">
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

<h1>Situation des gains (frais perçus)</h1>

<?php if (session('message')): ?>
  <div class="message"><?= esc(session('message')) ?></div>
<?php endif; ?>
<?php if (session('error')): ?>
  <div class="error"><?= esc(session('error')) ?></div>
<?php endif; ?>

<div class="card">
  <?php if (empty($gains)): ?>
    <p>Aucune opération pour le moment. Les frais perçus apparaîtront ici.</p>
  <?php else: ?>
    <table>
      <thead>
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

    <div class="total-gains">
      Total : <?= number_format((float)($totalGains ?? 0), 2, ',', ' ') ?> Ar
    </div>
  <?php endif; ?>
</div>
</body>
</html>

