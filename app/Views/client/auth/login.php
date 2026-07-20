<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Client</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm p-4">
                <h1 class="card-title text-center mb-4">Connexion</h1>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
                <?php endif; ?>

                <?php if (isset($errors)) : ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('client/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="numero_telephone" class="form-label">Numéro de téléphone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="numero_telephone"
                            name="numero_telephone"
                            placeholder="ex: 0341234567"
                            value="<?= old('numero_telephone') ?>"
                            required
                        >
                    </div>

                    <p class="text-muted small">Entrez votre numéro de téléphone pour accéder à votre compte.</p>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    <a href="/admin/login" class="text-muted">Se connecter en tant qu'administrateur →</a>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
