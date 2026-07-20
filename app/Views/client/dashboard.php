<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Client</title>
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
          <li class="nav-item"><a class="nav-link text-secondary" href="/admin/login">Admin</a></li>
          <li class="nav-item"><a class="nav-link text-danger" href="/client/logout">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="card shadow-sm p-4 mb-4 text-center">
    <h1 class="h4">Bonjour</h1>
    <p class="mb-1">Numéro : <strong><?= esc($compte['numero_telephone'] ?? '') ?></strong></p>
    <p class="mb-3">Statut : <strong><?= esc($compte['statut'] ?? '') ?></strong></p>

    <div class="display-5 fw-bold text-primary"><?= number_format((float)($compte['solde'] ?? 0), 2, ',', ' ') ?></div>
    <p class="text-muted">Solde disponible</p>
  </div>

  <div class="row g-3">
    <div class="col-6 col-md-3">
      <a href="/client/depot" class="btn btn-primary btn-lg w-100 py-4">Dépôt</a>
    </div>
    <div class="col-6 col-md-3">
      <a href="/client/retrait" class="btn btn-primary btn-lg w-100 py-4">Retrait</a>
    </div>
    <div class="col-6 col-md-3">
      <a href="/client/transfert" class="btn btn-primary btn-lg w-100 py-4">Transfert</a>
    </div>
    <div class="col-6 col-md-3">
      <a href="/client/historique" class="btn btn-primary btn-lg w-100 py-4">Historique</a>
    </div>
  </div>
</div>
</body>
</html>
