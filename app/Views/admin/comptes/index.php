<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Situation des comptes</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); margin-top:14px; }
    table{width:100%; border-collapse:collapse; margin-top:12px; }
    th,td{border-bottom:1px solid #eee; padding:10px; text-align:left; }
    .badge{display:inline-block; padding:4px 10px; border-radius:20px; font-size:12px; }
    .actif{background:#e8f5e9; color:#2e7d32; }
    .bloque{background:#ffebee; color:#c62828; }
    button { padding:6px 12px; border:none; border-radius:6px; background:#1e88e5; color:#fff; cursor:pointer; }
    .btn-bloquer { background:#c62828; }
    .btn-debloquer { background:#2e7d32; }
    .message{color:#2e7d32;}
    .error{color:#c62828;}
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

<h1>Situation des comptes clients</h1>

<?php if (session('message')): ?>
  <div class="message"><?= esc(session('message')) ?></div>
<?php endif; ?>
<?php if (session('error')): ?>
  <div class="error"><?= esc(session('error')) ?></div>
<?php endif; ?>

<div class="card">
  <?php if (empty($comptes)): ?>
    <p>Aucun compte client pour le moment.</p>
  <?php else: ?>
    <table>
      <thead>
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
              <span class="badge actif">Actif</span>
            <?php else: ?>
              <span class="badge bloque">Bloqué</span>
            <?php endif; ?>
          </td>
          <td><?= (int)($c['nb_operations'] ?? 0) ?></td>
          <td><?= esc($c['date_creation'] ?? '') ?></td>
          <td>
            <?php if (($c['statut'] ?? '') === 'ACTIF'): ?>
              <form method="post" action="/admin/comptes/<?= (int)$c['id'] ?>/bloquer" style="display:inline;">
                <button class="btn-bloquer" type="submit">Bloquer</button>
              </form>
            <?php else: ?>
              <form method="post" action="/admin/comptes/<?= (int)$c['id'] ?>/debloquer" style="display:inline;">
                <button class="btn-debloquer" type="submit">Débloquer</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>

