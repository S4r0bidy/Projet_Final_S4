<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barèmes de frais</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
<div class="wrap p-3">
  <nav class="navbar navbar-expand navbar-light bg-light rounded mb-3 shadow-sm">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Admin</span>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/prefixes">Préfixes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/operateurs">Opérateurs</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/baremes">Barèmes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/comptes">Comptes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/gains">Gains</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/admin/logout">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <h1 class="mb-4">Barèmes de frais</h1>

  <?php if (session('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm p-3 mb-3">
    <h3>Ajouter une tranche de frais</h3>
    <form method="post" action="/admin/baremes/create" class="row g-3 mt-1">
      <div class="col-md-3">
        <label class="form-label">Type d'opération</label>
        <select name="type_operation_id" class="form-select" required>
          <option value="">Sélectionner...</option>
          <?php foreach (($types ?? []) as $type): ?>
            <option value="<?= (int)$type['id'] ?>"><?= esc($type['libelle']) ?> (<?= esc($type['code']) ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Montant min (Ar)</label>
        <input type="number" step="0.01" name="montant_min" class="form-control" required placeholder="100">
      </div>
      <div class="col-md-3">
        <label class="form-label">Montant max (Ar)</label>
        <input type="number" step="0.01" name="montant_max" class="form-control" placeholder="5000 (vide = illimité)">
      </div>
      <div class="col-md-3">
        <label class="form-label">Frais (Ar)</label>
        <input type="number" step="0.01" name="frais" class="form-control" required placeholder="100">
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-primary">Ajouter</button>
      </div>
    </form>
  </div>

  <?php foreach (($baremesParType ?? []) as $data): ?>
    <div class="card shadow-sm p-3 mb-3">
      <h3><?= esc($data['type']['libelle']) ?> (<?= esc($data['type']['code']) ?>)</h3>

      <?php if (empty($data['baremes'])): ?>
        <p class="text-muted">Aucun barème défini pour ce type d'opération.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-light">
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
                  <a href="/admin/baremes/<?= (int)$b['id'] ?>/edit" class="btn btn-warning btn-sm">Modifier</a>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>

