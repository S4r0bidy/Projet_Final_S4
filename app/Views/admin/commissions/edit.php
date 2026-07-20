<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modifier commission</title>
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
          <li class="nav-item"><a class="nav-link" href="/admin/commissions">Commissions</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/baremes">Barèmes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/comptes">Comptes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/gains">Gains</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/situation-operateurs">Situation</a></li>
          <li class="nav-item"><a class="nav-link active" href="/admin/statistiques">Statistiques</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/admin/logout">Déconnexion</a></li>
        </ul>
      </div>
  </nav>

  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm p-4">
        <h2 class="mb-4">Modifier la commission</h2>

        <?php if (session('error')): ?>
          <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="/admin/commissions/<?= (int)$commission['id'] ?>/update">
          <div class="mb-3">
            <label class="form-label">Opérateur destination</label>
            <select name="operateur_destination_id" class="form-select" disabled>
              <?php foreach (($operateurs ?? []) as $op): ?>
                <option value="<?= (int)$op['id'] ?>" <?= ((int)$op['id'] === (int)$commission['operateur_destination_id']) ? 'selected' : '' ?>>
                  <?= esc($op['nom']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Pourcentage (%)</label>
            <input type="number" step="0.01" min="0.01" name="pourcentage" class="form-control" required
                   value="<?= number_format((float)$commission['pourcentage'], 2, '.', '') ?>">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/admin/commissions" class="btn btn-outline-secondary">Annuler</a>
          </div>
        </form>
      </div>
  </div>
</body>
</html>
