<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Retrait</title>
  <style>
    body { font-family: Arial, sans-serif; background:#f6f6f6; margin:0; padding:20px; }
    nav a { margin-right:12px; text-decoration:none; color:#1e88e5; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); max-width:520px; margin:auto; }
    input { width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; margin-top:8px; }
    button { width:100%; padding:10px; background:#1e88e5; color:#fff; border:none; border-radius:6px; cursor:pointer; margin-top:14px; }
    .error{color:#c62828;} .message{color:#2e7d32;}
  </style>
</head>
<body>
<nav style="max-width:900px; margin:0 auto;">
  <a href="/client/dashboard">Dashboard</a>
  <a href="/client/depot">Dépôt</a>
  <a href="/client/retrait">Retrait</a>
  <a href="/client/transfert">Transfert</a>
  <a href="/client/historique">Historique</a>
  <a href="/client/logout" style="float:right;">Déconnexion</a>
</nav>


<div class="card">
  <h1>Faire un retrait</h1>


  <?php if (session('message')): ?><div class="message"><?= esc(session('message')) ?></div><?php endif; ?>
  <?php if (session('error')): ?><div class="error"><?= esc(session('error')) ?></div><?php endif; ?>


  <form method="post" action="/client/retrait">
    <label>Montant</label>
    <input type="number" step="0.01" name="montant" required>
    <button type="submit">Confirmer</button>
  </form>
</div>
</body>
</html>
