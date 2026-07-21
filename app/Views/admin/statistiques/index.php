<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Statistiques - Transferts</title>
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
            <li class="nav-item"><a class="nav-link" href="/admin/prefixes">Prefixes</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/operateurs">Operateurs</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/commissions">Commissions</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/baremes">Baremes</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/comptes">Comptes</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/gains">Gains</a></li>
            <li class="nav-item"><a class="nav-link" href="/admin/situation-operateurs">Situation</a></li>
            <li class="nav-item"><a class="nav-link active" href="/admin/statistiques">Statistiques</a></li>
          </ul>
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link text-danger" href="/admin/logout">Deconnexion</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <h1 class="mb-4">Statistiques des transferts</h1>

    <!-- Cartes resume -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card border-success h-100">
          <div class="card-header bg-success text-white">
            <h5 class="mb-0">Transferts internes (meme operateur)</h5>
          </div>
          <div class="card-body">
            <div class="row text-center">
              <div class="col-4">
                <div class="display-6 fw-bold text-success"><?= (int) ($internes['nb_transferts'] ?? 0) ?></div>
                <small>Transferts</small>
              </div>
              <div class="col-4">
                <div class="display-6 fw-bold text-primary">
                  <?= number_format((float) ($internes['montant_total'] ?? 0), 0, ',', ' ') ?>
                </div>
                <small>Montant total</small>
              </div>
              <div class="col-4">
                <div class="display-6 fw-bold text-warning">
                  <?= number_format((float) ($internes['total_frais'] ?? 0), 0, ',', ' ') ?>
                </div>
                <small>Frais percus</small>
              </div>
              <hr>
              <div class="text-muted small">
                Expediteurs: <strong><?= (int) ($internes['nb_expediteurs'] ?? 0) ?></strong> |
                Destinataires: <strong><?= (int) ($internes['nb_destinataires'] ?? 0) ?></strong>
              </div>
            </div>
          </div>
        </div>
        </div>

        <div class="col-md-6">
          <div class="card border-warning h-100">
            <div class="card-header bg-warning text-dark">
              <h5 class="mb-0">Transferts externes (autre operateur)</h5>
            </div>
            <div class="card-body">
              <div class="row text-center">
                <div class="col-4">
                  <div class="display-6 fw-bold text-success"><?= (int) ($externes['nb_transferts'] ?? 0) ?></div>
                  <small>Transferts</small>
                </div>
                <div class="col-4">
                  <div class="display-6 fw-bold text-primary">
                    <?= number_format((float) ($externes['montant_total'] ?? 0), 0, ',', ' ') ?>
                  </div>
                  <small>Montant total</small>
                </div>
                <div class="col-4">
                  <div class="display-6 fw-bold text-warning">
                    <?= number_format((float) ($externes['total_frais'] ?? 0), 0, ',', ' ') ?>
                  </div>
                  <small>Frais percus</small>
                </div>
                <hr>
                <div class="text-muted small">
                  Destinataires: <strong><?= (int) ($externes['nb_destinataires'] ?? 0) ?></strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques par operateur -->
        <div class="card shadow-sm p-3 mb-4">
          <h3>Statistiques par operateur</h3>
          <?php if (empty($statsParOperateur)): ?>
            <p class="text-muted">Aucune operation pour le moment.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Operateur</th>
                    <th>Nb transferts</th>
                    <th>Montant total</th>
                    <th>Frais percus</th>
                    <th>Nb expediteurs</th>
                    <th>Nb destinataires</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($statsParOperateur as $s): ?>
                    <tr>
                      <td><strong><?= esc($s['operateur']) ?></strong></td>
                      <td><?= (int) $s['nb_transferts'] ?></td>
                      <td><?= number_format((float) $s['montant_total'], 2, ',', ' ') ?> Ar</td>
                      <td><?= number_format((float) $s['total_frais'], 2, ',', ' ') ?> Ar</td>
                      <td><?= (int) $s['nb_expediteurs'] ?></td>
                      <td><?= (int) $s['nb_destinataires'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <!-- Commissions supplementaires -->
        <div class="card shadow-sm p-3">
          <h3>Commissions supplementaires percues</h3>
          <?php if (empty($totalCommissions)): ?>
            <p class="text-muted">Aucune commission percue pour le moment.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Operateur</th>
                    <th>Total commissions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $grandTotal = 0; ?>
                  <?php foreach ($totalCommissions as $c): ?>
                    <?php $grandTotal += (float) $c['total_commissions']; ?>
                    <tr>
                      <td><?= esc($c['operateur']) ?></td>
                      <td><?= number_format((float) $c['total_commissions'], 2, ',', ' ') ?> Ar</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                  <tr>
                    <td>Total general</td>
                    <td><?= number_format($grandTotal, 2, ',', ' ') ?> Ar</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div><!-- /.wrap -->
</body>

</html>