<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Opérateurs</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); }
    input, select { padding:10px; border:1px solid #ddd; border-radius:6px; }
    button { padding:10px 14px; border:none; border-radius:6px; background:#1e88e5; color:#fff; cursor:pointer; }
    .error{color:#c62828;}
    .message{color:#2e7d32;}
    table{width:100%; border-collapse:collapse; margin-top:12px;}
    th,td{border-bottom:1px solid #eee; padding:10px; text-align:left;}
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

<h1>Opérateurs télécom</h1>

<div class="card">
  <?php if (session('message')): ?>
    <div class="message"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="error"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <h3>Ajouter un opérateur</h3>
  <form method="post" action="/admin/operateurs/create" style="margin-top:10px;">
    <label>Nom de l'opérateur</label><br>
    <input name="nom" required placeholder="Orange, Airtel, Yas..." style="width:300px;">
    <br><br>
    <button type="submit">Créer</button>
  </form>

  <h3>Liste des opérateurs</h3>
  <?php if (empty($operateurs)): ?>
    <p>Aucun opérateur enregistré.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr><th>ID</th><th>Nom</th><th>Date de création</th></tr>
      </thead>
      <tbody>
      <?php foreach ($operateurs as $op): ?>
        <tr>
          <td><?= (int)$op['id'] ?></td>
          <td><?= esc($op['nom']) ?></td>
          <td><?= esc($op['created_at'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>

