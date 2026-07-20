<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Client</title>
</head>
<body>

    <h1>Connexion</h1>
    <p>Entrez votre numéro de téléphone pour accéder à votre compte.<br>
       (Aucune inscription nécessaire — votre compte sera créé automatiquement.)</p>

    <?php if (session()->getFlashdata('error')) : ?>
        <div style="color:red;"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <div style="color:green;"><?= esc(session()->getFlashdata('message')) ?></div>
    <?php endif; ?>

    <?php if (isset($errors)) : ?>
        <ul style="color:red;">
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

        <button type="submit">Se connecter</button>
    </form>

</body>
</html>