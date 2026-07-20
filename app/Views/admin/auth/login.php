<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-sm p-4">
                <h2 class="card-title text-center mb-4">Connexion opérateur</h2>

                <?php if (session('message')): ?>
                    <div class="alert alert-success"><?= esc($this->session->getFlashdata('message') ?? session('message')) ?></div>
                <?php endif; ?>

                <?php if (session('error')): ?>
                    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                <?php endif; ?>

                <?php if (isset($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $fieldErrors): ?>
                            <?php foreach ((array)$fieldErrors as $err): ?>
                                <div><?= esc($err) ?></div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="/admin/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= esc(old('username') ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <div class="text-center mt-3">
                    <a href="/" class="back-link">&larr; Retour à l'accueil</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

