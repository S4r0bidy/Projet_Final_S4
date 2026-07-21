<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gains - Frais</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css">
</head>
<body>
<div class="wrap p-3">
  <nav class="navbar navbar-expand navbar-light bg-light rounded mb-3 shadow-sm">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Admin</span>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Accueil</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/prefixes">Préfixes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/operateurs">Opérateurs</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/commissions">Commissions</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/baremes">Barèmes</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/comptes">Comptes</a></li>
          <li class="nav-item"><a class="nav-link active" href="/admin/gains">Gains</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/situation-operateurs">Situation</a></li>
          <li class="nav-item"><a class="nav-link" href="/admin/statistiques">Statistiques</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/admin/logout">Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <h1 class="mb-4">Situation des gains via les différents frais</h1>

  <?php if (session('message')): ?>
    <div class="alert alert-success"><?= esc(session('message')) ?></div>
  <?php endif; ?>
  <?php if (session('error')): ?>
    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
  <?php endif; ?>

  <div class="row">
    <!-- Gains sur les retraits -->
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Gains sur les retraits</h5>
        </div>
        <div class="card-body">
          <?php
          $totalRetraits = 0;
          foreach ($gainsRetraits as $g) { $totalRetraits += (float)$g['total_frais']; }
          ?>
          <div class="display-6 fw-bold text-primary mb-2">
            <?= number_format($totalRetraits, 2, ',', ' ') ?> Ar
          </div>
          <p class="text-muted mb-0">
            <?= array_sum(array_column($gainsRetraits, 'nb_operations')) ?> opération(s)
          </p>
        </div>
      </div>
    </div>

    <!-- Gains des transferts internes -->
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Gains transferts internes</h5>
        </div>
        <div class="card-body">
          <?php
          $totalInternes = 0;
          foreach ($gainsTransfertsInternes as $g) { $totalInternes += (float)$g['total_frais']; }
          ?>
          <div class="display-6 fw-bold text-success mb-2">
            <?= number_format($totalInternes, 2, ',', ' ') ?> Ar
          </div>
          <p class="text-muted mb-0">
            <?= array_sum(array_column($gainsTransfertsInternes, 'nb_operations')) ?> transfert(s) interne(s)
          </p>
        </div>
      </div>
    </div>

    <!-- Gains des transferts vers autres opérateurs -->
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-warning text-dark">
          <h5 class="mb-0">Gains transferts externes</h5>
        </div>
        <div class="card-body">
          <?php
          $totalExternes = 0;
          foreach ($gainsTransfertsExternes as $g) { $totalExternes += (float)$g['total_frais']; }
          ?>
          <div class="display-6 fw-bold text-warning mb-2">
            <?= number_format($totalExternes, 2, ',', ' ') ?> Ar
          </div>
          <p class="text-muted mb-0">
            <?= array_sum(array_column($gainsTransfertsExternes, 'nb_operations')) ?> transfert(s) externe(s)
          </p>
        </div>
      </div>
    </div>
  </div><!-- /.row -->

  <div class="card shadow-sm p-3 mt-3">
    <h3>Détail complet</h3>
    <?php if (empty($gainsDetail)): ?>
      <p class="text-muted">Aucune opération pour le moment.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-light">
            <tr>
              <th>Type d'opération</th>
              <th>Catégorie</th>
              <th>Nombre d'opérations</th>
              <th>Total des frais</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($gainsDetail as $g): ?>
            <tr>
              <td><?= esc($g['type_operation'] ?? '') ?></td>
              <td>
                <?php if ($g['categorie_operateur'] === 'MEME_OPERATEUR'): ?>
                  <span class="badge bg-success">Même opérateur</span>
                <?php elseif ($g['categorie_operateur'] === 'AUTRE_OPERATEUR'): ?>
                  <span class="badge bg-warning">Autre opérateur</span>
                <?php else: ?>
                  <span class="badge bg-secondary">-</span>
                <?php endif; ?>
              </td>
              <td><?= (int)($g['nb_operations'] ?? 0) ?></td>
              <td><?= number_format((float)($g['total_frais'] ?? 0), 2, ',', ' ') ?> Ar</td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="h3 text-success fw-bold mt-3">
        Total général : <?= number_format((float)($totalGains ?? 0), 2, ',', ' ') ?> Ar
      </div>
    <?php endif; ?>
  </div>
</div><!-- /.wrap -->
</body>
</html>
