<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
<div class="auth-card">
    <h2>Connexion opérateur</h2>

    <?php if (session('message')): ?>
        <div class="message"><?= esc($this->session->getFlashdata('message') ?? session('message')) ?></div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div class="error"><?= esc(session('error')) ?></div>
    <?php endif; ?>

    <?php if (isset($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $fieldErrors): ?>
                <?php foreach ((array)$fieldErrors as $err): ?>
                    <div><?= esc($err) ?></div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/admin/login">
        <label>Nom d'utilisateur</label>
        <input type="text" name="username" value="<?= esc(old('username') ?? '') ?>" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>

    <br>
    <a href="/" class="back-link">&larr; Retour à l'accueil</a>
</div>
</body>
</html>

