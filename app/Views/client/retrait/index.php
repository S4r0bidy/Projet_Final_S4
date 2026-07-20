<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Retrait</title>
  <link rel="stylesheet" href="/css/layout.css">
  <link rel="stylesheet" href="/css/client.css">
</head>
<body>
<nav class="client-nav">
  <a href="/client/dashboard">Dashboard</a>
  <a href="/client/depot">Dépôt</a>
  <a href="/client/retrait">Retrait</a>
  <a href="/client/transfert">Transfert</a>
  <a href="/client/historique">Historique</a>
  <a href="/client/logout" style="float:right;">Déconnexion</a>
</nav>

<div class="card transaction-card">
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
