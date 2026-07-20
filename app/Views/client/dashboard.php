<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Client</title>
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/client.css">
</head>
<body>
<div class="wrap">
  <nav>
    <a href="/client/dashboard">Dashboard</a>
    <a href="/client/depot">Dépôt</a>
    <a href="/client/retrait">Retrait</a>
    <a href="/client/transfert">Transfert</a>
    <a href="/client/historique">Historique</a>
    <a href="/admin/login" style="color:#6c757d;" title="Accéder au mode administrateur">🔧 Admin</a>
    <a href="/client/logout" style="float:right; margin-right:0;">Déconnexion</a>
  </nav>

  <div class="card">
    <h1>Bonjour</h1>
    <div>Numéro : <b><?= esc($compte['numero_telephone'] ?? '') ?></b></div>
    <div>Statut : <b><?= esc($compte['statut'] ?? '') ?></b></div>

    <div class="dashboard-value"><?= number_format((float)($compte['solde'] ?? 0), 2, ',', ' ') ?></div>
    <div>Solde disponible</div>
  </div>

  <div class="dashboard-grid">
    <a class="btnbox" href="/client/depot">Dépôt</a>
    <a class="btnbox" href="/client/retrait">Retrait</a>
    <a class="btnbox" href="/client/transfert">Transfert</a>
    <a class="btnbox" href="/client/historique">Historique</a>
  </div>
</div>
</body>
</html>
