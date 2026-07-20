<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier le barème</title>
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

<div class="card" style="max-width:500px; margin:30px auto;">
  <h2>Modifier le barème</h2>

  <?php if (session('message')): ?>
    <div class="message"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="error"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <form class="admin-form" method="post" action="/admin/baremes/<?= (int)$bareme['id'] ?>/update">

    <label>Type d'opération</label>
    <select name="type_operation_id" required>
      <?php foreach (($types ?? []) as $type): ?>
        <option value="<?= (int)$type['id'] ?>" <?= ((int)$bareme['type_operation_id'] === (int)$type['id']) ? 'selected' : '' ?>>
          <?= esc($type['libelle']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Montant min (Ar)</label>
    <input type="number" step="0.01" name="montant_min" required value="<?= (float)$bareme['montant_min'] ?>">

    <label>Montant max (Ar) — laisser vide si illimité</label>
    <input type="number" step="0.01" name="montant_max" value="<?= $bareme['montant_max'] !== null ? (float)$bareme['montant_max'] : '' ?>">

    <label>Frais (Ar)</label>
    <input type="number" step="0.01" name="frais" required value="<?= (float)$bareme['frais'] ?>">

    <br><br>
    <button type="submit">Enregistrer</button>
    <a href="/admin/baremes">Annuler</a>
  </form>
</div>
</body>
</html>

