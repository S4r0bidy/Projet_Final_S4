<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Préfixes</title>
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
    .badge{display:inline-block; padding:4px 10px; border-radius:20px; font-size:12px;}
    .actif{background:#e8f5e9; color:#2e7d32;}
    .inactif{background:#ffebee; color:#c62828;}
  </style>
</head>
<body>
<nav>
  <a href="/admin/dashboard">Accueil</a>
  <a href="/admin/prefixes">Préfixes</a>
  <a href="/admin/baremes">Barèmes</a>
  <a href="/admin/comptes">Comptes</a>
  <a href="/admin/gains">Gains</a>
  <a href="/admin/logout" style="float:right; margin-right:0;">Déconnexion</a>
</nav>

<h1>Préfixes valables</h1>

<div class="card">
  <?php if (session('message')): ?>
    <div class="message"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="error"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <h3>Ajouter un préfixe</h3>
  <form method="post" action="/admin/prefixes/create" style="margin-top:10px;">
    <label>Préfixe</label><br>
    <input name="prefixe" required placeholder="033, 037" style="width:200px;">

    <br><br>
    <label>Opérateur</label><br>
    <select name="operateur_telecom_id" required>
      <?php foreach (($operateurs ?? []) as $op): ?>
        <option value="<?= (int)$op['id'] ?>"><?= esc($op['nom']) ?></option>
      <?php endforeach; ?>
    </select>

    <br><br>
    <button type="submit">Créer</button>
  </form>

  <h3>Liste</h3>
  <table>
    <thead>
      <tr><th>Préfixe</th><th>Opérateur</th><th>Statut</th><th>Action</th></tr>
    </thead>
    <tbody>
    <?php foreach (($prefixes ?? []) as $p): ?>
      <tr>
        <td><?= esc($p['prefixe']) ?></td>
        <td><?= esc($p['operateur_nom'] ?? '') ?></td>
        <td>
          <?php if ((int)$p['actif'] === 1): ?>
            <span class="badge actif">Actif</span>
          <?php else: ?>
            <span class="badge inactif">Inactif</span>
          <?php endif; ?>
        </td>
        <td>
          <form method="post" action="/admin/prefixes/<?= (int)$p['id'] ?>/toggle">
            <button type="submit">Basculer</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>

