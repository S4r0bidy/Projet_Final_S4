<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier le préfixe</title>
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

<h1>Modifier le préfixe</h1>

<div class="card">
  <?php if (session('error')): ?>
    <div class="error"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <form method="post" action="/admin/prefixes/<?= (int)$prefixe['id'] ?>/update" style="margin-top:10px;">
    <label>Préfixe</label><br>
    <input name="prefixe" required value="<?= esc($prefixe['prefixe']) ?>" placeholder="033, 037" style="width:200px;">

    <br><br>
    <label>Opérateur</label><br>
    <select name="operateur_telecom_id" required>
      <?php foreach (($operateurs ?? []) as $op): ?>
        <option value="<?= (int)$op['id'] ?>" <?= ((int)$op['id'] === (int)$prefixe['operateur_telecom_id']) ? 'selected' : '' ?>>
          <?= esc($op['nom']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <br><br>
    <button type="submit">Enregistrer</button>
    <a href="/admin/prefixes" class="btn-cancel" style="margin-left:10px;">Annuler</a>
  </form>
</div>
</body>
</html>

