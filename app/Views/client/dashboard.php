<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
</head>
<body>
    <h1>Bienvenue <?= esc($compte['numero_telephone']) ?></h1>
    <p>Solde actuel : <strong><?= number_format($compte['solde'], 0, ',', ' ') ?> Ar</strong></p>

    <a href="<?= site_url('client/logout') ?>">Déconnexion</a>
</body>
</html>