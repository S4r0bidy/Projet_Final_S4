<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f6f6f6; padding:20px; }
        .card { max-width:420px; margin:60px auto; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 12px rgba(0,0,0,.08); }
        input { width:100%; padding:10px; margin:8px 0; border:1px solid #ddd; border-radius:6px; }
        button { width:100%; padding:10px; background:#1e88e5; color:#fff; border:none; border-radius:6px; cursor:pointer; }
        .error { color:#c62828; margin-top:10px; }
        .message { color:#2e7d32; margin-top:10px; }
    </style>
</head>
<body>
<div class="card">
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
    <a href="/" style="display:block; text-align:center; color:#1e88e5; text-decoration:none;">&larr; Retour à l'accueil</a>
</div>
</body>
</html>

