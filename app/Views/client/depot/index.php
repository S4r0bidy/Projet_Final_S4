<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dépôt</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
<div class="wrap p-3">
  <nav class="navbar navbar-expand navbar-light bg-light rounded mb-3 shadow-sm">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Mon Compte</span>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="/client/dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/depot">Dépôt</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/retrait">Retrait</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/transfert">Transfert</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/historique">Historique</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/client/logout">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm p-4">
        <h1 class="h3 mb-4">Faire un dépôt</h1>

        <?php if (session('message')): ?>
          <div class="alert alert-success"><?= esc(session('message')) ?></div>
        <?php endif; ?>
        <?php if (session('error')): ?>
          <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <form method="post" action="/client/depot">
          <div class="mb-3">
            <label class="form-label">Montant</label>
            <input type="number" step="0.01" name="montant" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Confirmer</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
