<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transfert</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css">
  <style>
    .destinataire-row { background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 8px; }
    .destinataire-row .btn-remove { cursor: pointer; }
    .info-badge { font-size: 0.85rem; padding: 4px 10px; border-radius: 20px; }
    .badge-interne { background: #d1e7dd; color: #0f5132; }
    .badge-externe { background: #fff3cd; color: #664d03; }
  </style>
</head>
<body>
<div class="wrap p-3">
  <nav class="navbar navbar-expand navbar-light bg-light rounded mb-3 shadow-sm">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">Mon Compte</span>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="/client/dashboard">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/depot">Dépôt</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/retrait">Retrait</a></li>
          <li class="nav-item"><a class="nav-link active" href="/client/transfert">Transfert</a></li>
          <li class="nav-item"><a class="nav-link" href="/client/historique">Historique</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-danger" href="/client/logout">Déconnexion</a></li>
        </ul>
      </div>
  </nav>


  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
      <div class="card shadow-sm p-4">
        <h1 class="h3 mb-4">Faire un transfert</h1>


        <?php if (session('message')): ?>
          <div class="alert alert-success"><?= esc(session('message')) ?></div>
        <?php endif; ?>
        <?php if (session('error')): ?>
          <div class="alert alert-danger"><?= esc(session('error')) ?></div>
        <?php endif; ?>


        <!-- Message d'information opérateur -->
        <div id="operateur-info" class="alert alert-info d-none">
          <span id="operateur-nom"></span>
        </div>


        <!-- Navigation entre mode simple et multiple -->
        <ul class="nav nav-tabs mb-4" id="transfertTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="simple-tab" data-bs-toggle="tab" data-bs-target="#simple" type="button" role="tab">
              Transfert simple
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="multiple-tab" data-bs-toggle="tab" data-bs-target="#multiple" type="button" role="tab">
              Transfert multiple
            </button>
          </li>
        </ul>


        <div class="tab-content">
          <!-- === TRANSFERT SIMPLE === -->
          <div class="tab-pane fade show active" id="simple" role="tabpanel">
            <form method="post" action="/client/transfert" id="form-simple">
              <div class="mb-3">
                <label class="form-label">Numéro destination (ex: 0331234567)</label>
                <input type="text" name="numero_destination" class="form-control" required
                       oninput="verifierOperateur(this.value, 'simple')">
              </div>


              <div class="mb-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" min="100" name="montant" class="form-control" required>
              </div>


              <div class="mb-3 form-check">
                <input type="checkbox" name="inclure_frais" class="form-check-input" id="inclure_frais_simple" value="1">
                <label class="form-check-label" for="inclure_frais_simple">
                  Inclure les frais de retrait
                  <small class="text-muted d-block">(Le destinataire reçoit exactement le montant saisi)</small>
                </label>
              </div>


              <button type="submit" class="btn btn-primary w-100">Confirmer le transfert</button>
            </form>
          </div>


          <!-- === TRANSFERT MULTIPLE === -->
          <div class="tab-pane fade" id="multiple" role="tabpanel">
            <form method="post" action="/client/transfert/multiple" id="form-multiple">
              <div class="mb-3">
                <label class="form-label">Montant total à répartir</label>
                <input type="number" step="0.01" min="100" name="montant_total" class="form-control" required
                       id="montant_total" oninput="calculerRepartition()">
              </div>


              <div class="mb-3">
                <label class="form-label d-flex justify-content-between">
                  <span>Destinataires</span>
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="ajouterDestinataire()">
                    + Ajouter un destinataire
                  </button>
                </label>
                <div id="destinataires-container">
                  <!-- Les destinataires sont ajoutés via JavaScript -->
                  <div class="destinataire-row" data-index="0">
                    <div class="row g-2 align-items-center">
                      <div class="col-9">
                        <input type="text" name="numeros[]" class="form-control" placeholder="Numéro (ex: 0331234567)" required
                               oninput="verifierOperateur(this.value, 'multiple')">
                      </div>
                      <div class="col-3">
                        <span class="badge info-badge" id="montant-indiv-0">
                          ~ <span id="montant-preview-0">0</span> Ar chacun
                        </span>
                      </div>
                  </div>
                <small class="text-muted">Le montant total est automatiquement réparti entre les destinataires.</small>
              </div>


              <div class="mb-3 form-check">
                <input type="checkbox" name="inclure_frais" class="form-check-input" id="inclure_frais_multiple" value="1">
                <label class="form-check-label" for="inclure_frais_multiple">
                  ☐ Inclure les frais de retrait
                  <small class="text-muted d-block">(Chaque destinataire reçoit exactement sa part)</small>
                </label>
              </div>


              <div id="operateur-multiple-info" class="alert alert-warning d-none">
                ⚠️ Tous les destinataires doivent appartenir au même opérateur pour le transfert multiple.
              </div>


              <button type="submit" class="btn btn-primary w-100">Confirmer les transferts</button>
            </form>
          </div>
      </div>
  </div>


<script src="/js/bootstrap.bundle.min.js"></script>
<script>
let compteurDestinataires = 1;


function ajouterDestinataire() {
  const container = document.getElementById('destinataires-container');
  const idx = compteurDestinataires++;
  const div = document.createElement('div');
  div.className = 'destinataire-row';
  div.dataset.index = idx;
  div.innerHTML = `
    <div class="row g-2 align-items-center">
      <div class="col-8">
        <input type="text" name="numeros[]" class="form-control" placeholder="Numéro (ex: 0331234567)" required
               oninput="verifierOperateur(this.value, 'multiple')">
      </div>
      <div class="col-2">
        <span class="badge info-badge" id="montant-indiv-${idx}">
          ~ <span id="montant-preview-${idx}">0</span> Ar
        </span>
      </div>
      <div class="col-2 text-end">
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove" onclick="supprimerDestinataire(this)">
          ✕
        </button>
      </div>
  `;
  container.appendChild(div);
  calculerRepartition();
}


function supprimerDestinataire(btn) {
  const row = btn.closest('.destinataire-row');
  if (document.querySelectorAll('.destinataire-row').length <= 1) {
    alert('Vous devez avoir au moins un destinataire.');
    return;
  }
  row.remove();
  calculerRepartition();
}


function calculerRepartition() {
  const montantTotal = parseFloat(document.getElementById('montant_total').value) || 0;
  const rows = document.querySelectorAll('.destinataire-row');
  const nb = rows.length;


  if (nb === 0 || montantTotal <= 0) {
    rows.forEach((row, i) => {
      document.getElementById('montant-preview-' + row.dataset.index).textContent = '0';
    });
    return;
  }


  const parPersonne = Math.floor((montantTotal * 100) / nb) / 100;
  let reste = Math.round((montantTotal - (parPersonne * nb)) * 100) / 100;


  rows.forEach((row, i) => {
    let montant = parPersonne;
    if (i === 0) {
      montant = Math.round((parPersonne + reste) * 100) / 100;
    }
    document.getElementById('montant-preview-' + row.dataset.index).textContent = montant.toFixed(2);
  });
}


// Appelée lors de la saisie d'un numéro
let operateursEnregistres = {};


function verifierOperateur(numero, mode) {
  if (numero.length < 3) return;


  fetch('/client/verifier-operateur?numero=' + encodeURIComponent(numero))
    .then(r => r.json())
    .then(data => {
      if (data.operateur) {
        operateursEnregistres[numero] = data.operateur;


        // Vérifier que tous les destinataires ont le même opérateur (mode multiple)
        if (mode === 'multiple') {
          verifierOperateurMultiple();
        }


        // Afficher l'opérateur pour le mode simple
        if (mode === 'simple') {
          const info = document.getElementById('operateur-info');
          const nom = document.getElementById('operateur-nom');
          info.classList.remove('d-none', 'alert-info', 'alert-warning');
          if (data.meme_operateur) {
            info.classList.add('alert-info');
            nom.innerHTML = '📱 Opérateur : <strong>' + data.operateur + '</strong> (même opérateur)';
          } else {
            info.classList.add('alert-warning');
            nom.innerHTML = '📱 Opérateur : <strong>' + data.operateur + '</strong> (opérateur différent)';
          }
        }
      } else {
        if (mode === 'simple') {
          const info = document.getElementById('operateur-info');
          info.classList.remove('d-none');
          info.classList.add('alert-danger');
          document.getElementById('operateur-nom').textContent = '⚠️ Opérateur non reconnu ou inactif.';
        }
      }
    })
    .catch(() => {});
}


function verifierOperateurMultiple() {
  const inputs = document.querySelectorAll('#destinataires-container input[name="numeros[]"]');
  const operateurs = new Set();


  inputs.forEach(input => {
    const num = input.value.trim();
    if (num && operateursEnregistres[num]) {
      operateurs.add(operateursEnregistres[num]);
    }
  });


  const info = document.getElementById('operateur-multiple-info');
  if (operateurs.size > 1) {
    info.classList.remove('d-none');
    info.textContent = '⚠️ Tous les destinataires doivent appartenir au même opérateur.';
    info.classList.add('alert-danger');
    info.classList.remove('alert-warning');
  } else if (operateurs.size === 1) {
    info.classList.remove('d-none');
    info.textContent = '✅ Tous les destinataires appartiennent à ' + Array.from(operateurs)[0];
    info.classList.add('alert-success');
    info.classList.remove('alert-danger', 'alert-warning');
  } else {
    info.classList.add('d-none');
  }
}
</script>
</body>
</html>
