<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Client</title>
    <link rel="stylesheet" href="/css/layout.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>

<div class="auth-card">
    <h1>Connexion</h1>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="message"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?php if (isset($errors)) : ?>
        <ul class="error-list">
            <?php foreach ($errors as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= site_url('client/login') ?>" method="post">
        <?= csrf_field() ?>

        <label for="numero_telephone">Numéro de téléphone</label>
        <input
            type="text"
            id="numero_telephone"
            name="numero_telephone"
            placeholder="ex: 0341234567"
            value="<?= old('numero_telephone') ?>"
            required
        >

        <p>Entrez votre numéro de téléphone pour accéder à votre compte.<br>
        
        <button type="submit">Se connecter</button>
    </form>

    <p style="text-align:center; margin-top:20px;">
        <a href="/admin/login" style="color:#6c757d; text-decoration:none;">Se connecter en tant qu'administrateur →</a>
    </p>
</div>

</body>
</html>
