<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier le barème</title>
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

  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm p-4">
        <h2 class="mb-4">Modifier le barème</h2>

        <?php if (session('message')): ?>
          <div class="alert alert-success"><?= esc(session('message')) ?></div>
        <?php endif; ?>
        <?php if (session('error')): ?>
          <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="/admin/baremes/<?= (int)$bareme['id'] ?>/update">
          <div class="mb-3">
            <label class="form-label">Type d'opération</label>
            <select name="type_operation_id" class="form-select" required>
              <?php foreach (($types ?? []) as $type): ?>
                <option value="<?= (int)$type['id'] ?>" <?= ((int)$bareme['type_operation_id'] === (int)$type['id']) ? 'selected' : '' ?>>
                  <?= esc($type['libelle']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Montant min (Ar)</label>
            <input type="number" step="0.01" name="montant_min" class="form-control" required value="<?= (float)$bareme['montant_min'] ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Montant max (Ar) — laisser vide si illimité</label>
            <input type="number" step="0.01" name="montant_max" class="form-control" value="<?= $bareme['montant_max'] !== null ? (float)$bareme['montant_max'] : '' ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Frais (Ar)</label>
            <input type="number" step="0.01" name="frais" class="form-control" required value="<?= (float)$bareme['frais'] ?>">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/admin/baremes" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>

