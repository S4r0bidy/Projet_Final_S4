<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Client</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .wrap { max-width:900px; margin:0 auto; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); margin-top:14px; }
    .value { font-size:30px; font-weight:700; margin-top:6px; }
    ul { padding-left: 18px; }
    .grid { display:grid; grid-template-columns: repeat(4, 1fr); gap:12px; margin-top:14px; }
    .btnbox { display:block; background:#1e88e5; color:#fff; text-decoration:none; padding:12px; border-radius:10px; text-align:center; }
  </style>
</head>
<body>
<div class="wrap">
  <nav>
    <a href="/client/dashboard">Dashboard</a>
    <a href="/client/depot">Dépôt</a>
    <a href="/client/retrait">Retrait</a>
    <a href="/client/transfert">Transfert</a>
    <a href="/client/historique">Historique</a>
    <a href="/client/logout" style="float:right; margin-right:0;">Déconnexion</a>
  </nav>


  <div class="card">
    <h1>Bonjour</h1>
    <div>Numéro : <b><?= esc($compte['numero_telephone'] ?? '') ?></b></div>
    <div>Statut : <b><?= esc($compte['statut'] ?? '') ?></b></div>


    <div class="value"><?= number_format((float)($compte['solde'] ?? 0), 2, ',', ' ') ?></div>
    <div>Solde disponible</div>
  </div>


  <div class="grid">
    <a class="btnbox" href="/client/depot">Dépôt</a>
    <a class="btnbox" href="/client/retrait">Retrait</a>
    <a class="btnbox" href="/client/transfert">Transfert</a>
    <a class="btnbox" href="/client/historique">Historique</a>
  </div>
</div>
</body>
</html>
