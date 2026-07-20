<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barèmes de frais</title>
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

<h1>Barèmes de frais</h1>

<?php if (session('message')): ?>
  <div class="message"><?= esc(session('message')) ?></div>
<?php endif; ?>
<?php if (session('error')): ?>
  <div class="error"><?= esc(session('error')) ?></div>
<?php endif; ?>

<div class="card">
  <h3>Ajouter une tranche de frais</h3>
  <form method="post" action="/admin/baremes/create" style="margin-top:10px;">
    <label>Type d'opération</label><br>
    <select name="type_operation_id" required>
      <?php foreach (($types ?? []) as $type): ?>
        <option value="<?= (int)$type['id'] ?>"><?= esc($type['libelle']) ?> (<?= esc($type['code']) ?>)</option>
      <?php endforeach; ?>
    </select>

    <br><br>
    <label>Montant min (Ar)</label><br>
    <input type="number" step="0.01" name="montant_min" required placeholder="100" style="width:150px;">

    <br><br>
    <label>Montant max (Ar) — laisser vide si pas de limite</label><br>
    <input type="number" step="0.01" name="montant_max" placeholder="5000" style="width:150px;">

    <br><br>
    <label>Frais (Ar)</label><br>
    <input type="number" step="0.01" name="frais" required placeholder="100" style="width:150px;">

    <br><br>
    <button type="submit">Ajouter</button>
  </form>
</div>

<?php foreach (($baremesParType ?? []) as $data): ?>
  <div class="card">
    <h3><?= esc($data['type']['libelle']) ?> (<?= esc($data['type']['code']) ?>)</h3>

    <?php if (empty($data['baremes'])): ?>
      <p>Aucun barème défini pour ce type d'opération.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Montant min</th>
            <th>Montant max</th>
            <th>Frais</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($data['baremes'] as $b): ?>
          <tr>
            <td><?= number_format((float)$b['montant_min'], 2, ',', ' ') ?> Ar</td>
            <td><?= $b['montant_max'] !== null ? number_format((float)$b['montant_max'], 2, ',', ' ') . ' Ar' : 'Illimité' ?></td>
            <td><?= number_format((float)$b['frais'], 2, ',', ' ') ?> Ar</td>
            <td>
              <a href="/admin/baremes/<?= (int)$b['id'] ?>/edit">
                <button class="btn btn-edit" type="button">Modifier</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
</body>
</html>

