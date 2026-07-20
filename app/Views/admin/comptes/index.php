<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Situation des comptes</title>
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
              <span class="badge badge-actif">Actif</span>
            <?php else: ?>
              <span class="badge badge-bloque">Bloqué</span>
            <?php endif; ?>
          </td>
          <td><?= (int)($c['nb_operations'] ?? 0) ?></td>
          <td><?= esc($c['date_creation'] ?? '') ?></td>
          <td>
            <?php if (($c['statut'] ?? '') === 'ACTIF'): ?>
              <form method="post" action="/admin/comptes/<?= (int)$c['id'] ?>/bloquer" style="display:inline;">
                <button class="btn btn-bloquer" type="submit">Bloquer</button>
              </form>
            <?php else: ?>
              <form method="post" action="/admin/comptes/<?= (int)$c['id'] ?>/debloquer" style="display:inline;">
                <button class="btn btn-debloquer" type="submit">Débloquer</button>
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

