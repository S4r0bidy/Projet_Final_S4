<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier le barème</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .card { max-width:500px; margin:30px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); }
    input, select { width:100%; padding:10px; margin:6px 0; border:1px solid #ddd; border-radius:6px; }
    button { padding:10px 14px; border:none; border-radius:6px; background:#1e88e5; color:#fff; cursor:pointer; }
    .error{color:#c62828;}
    .message{color:#2e7d32;}
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

<div class="card">
  <h2>Modifier le barème</h2>

  <?php if (session('message')): ?>
    <div class="message"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="error"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <form method="post" action="/admin/baremes/<?= (int)$bareme['id'] ?>/update">

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

