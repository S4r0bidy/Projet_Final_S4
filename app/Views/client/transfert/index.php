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
                        oninput="verifierOperateur(this.value, 'simple'); calculerDetailsSimple();">
                </div>




                <div class="mb-3">
                  <label class="form-label">Montant</label>
                  <input type="number" step="0.01" min="100" name="montant" class="form-control" required
                        oninput="calculerDetailsSimple()">
                </div>




                <div class="mb-3 form-check">
                  <input type="checkbox" name="inclure_frais" class="form-check-input" id="inclure_frais_simple" value="1"
                        onchange="calculerDetailsSimple()">
                  <label class="form-check-label" for="inclure_frais_simple">
                    Inclure les frais de retrait
                    <small class="text-muted d-block">(Le destinataire reçoit exactement le montant saisi)</small>
                  </label>
                </div>




                <!-- Détail des montants - Transfert simple -->
                <div id="detail-simple" class="d-none">
                  <hr>
                  <h5 class="mb-3">Détail des montants</h5>
                  <table class="table table-sm table-bordered">
                    <tbody>
                      <tr>
                        <td>Montant saisi</td>
                        <td class="text-end" id="s-montant">0 Ar</td>
                      </tr>
                      <tr id="s-frais-row">
                        <td>Frais de retrait</td>
                        <td class="text-end" id="s-frais">0 Ar</td>
                      </tr>
                      <tr id="s-bareme-row" class="table-light">
                        <td><small class="text-muted">Barème appliqué</small></td>
                        <td class="text-end"><small class="text-muted" id="s-bareme-info">-</small></td>
                      </tr>
                      <tr id="s-commission-row" class="d-none table-warning">
                        <td>Commission opérateur</td>
                        <td class="text-end">
                          <span id="s-commission-label">0%</span><br>
                          <strong id="s-commission">0 Ar</strong>
                        </td>
                      </tr>
                      <tr class="table-info">
                        <th>Total à débiter</th>
                        <th class="text-end" id="s-total-debiter">0 Ar</th>
                      </tr>
                      <tr class="table-success">
                        <td><strong>Montant reçu par le destinataire</strong></td>
                        <td class="text-end"><strong id="s-montant-recu">0 Ar</strong></td>
                      </tr>
                    </tbody>
                  </table>
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
                  <input type="checkbox" name="inclure_frais" class="form-check-input" id="inclure_frais_multiple" value="1"
                        onchange="calculerDetailsMultiple()">
                  <label class="form-check-label" for="inclure_frais_multiple">
                    Inclure les frais de retrait
                    <small class="text-muted d-block">(Chaque destinataire reçoit exactement sa part)</small>
                  </label>
                </div>




                <!-- Détail des montants - Transfert multiple -->
                <div id="detail-multiple" class="d-none">
                  <hr>
                  <h5 class="mb-3">Détail des montants</h5>
                  <div id="detail-multiple-content"></div>
                  <table class="table table-sm table-bordered mt-2">
                    <tbody>
                      <tr id="m-frais-row">
                        <td>Total des frais de retrait</td>
                        <td class="text-end" id="m-total-frais">0 Ar</td>
                      </tr>
                      <tr class="table-info">
                        <th>Total à débiter</th>
                        <th class="text-end" id="m-total-debiter">0 Ar</th>
                      </tr>
                      <tr class="table-success">
                        <td><strong>Total reçu par les destinataires</strong></td>
                        <td class="text-end"><strong id="m-total-recu">0 Ar</strong></td>
                      </tr>
                    </tbody>
                  </table>
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
                oninput="verifierOperateur(this.value, 'multiple'); calculerDetailsMultiple();">
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




    // Also refresh the details panel for multiple mode
    calculerDetailsMultiple();
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




  /**
   * Formate un nombre en monnaie (ex: 1 500 Ar)
   */
  function formatMoney(amount) {
    return Number(amount).toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Ar';
  }




  /**
   * Calcule et affiche les détails pour le transfert simple
   */
  function calculerDetailsSimple() {
    const numero = document.querySelector('#form-simple input[name="numero_destination"]').value.trim();
    const montant = parseFloat(document.querySelector('#form-simple input[name="montant"]').value) || 0;
    const inclureFrais = document.getElementById('inclure_frais_simple').checked;


    const detailDiv = document.getElementById('detail-simple');
    if (numero.length < 3 || montant <= 0) {
      detailDiv.classList.add('d-none');
      return;
    }


    const url = '/client/calculer-details?montant=' + encodeURIComponent(montant)
              + '&numero=' + encodeURIComponent(numero)
              + '&inclure_frais=' + (inclureFrais ? '1' : '0');


    fetch(url)
      .then(r => r.json())
      .then(data => {
        if (data.error) {
          detailDiv.classList.add('d-none');
          return;
        }


        detailDiv.classList.remove('d-none');


        document.getElementById('s-montant').textContent = formatMoney(data.montant);
        document.getElementById('s-frais').textContent = data.frais > 0 ? formatMoney(data.frais) : '0 Ar';
        document.getElementById('s-bareme-info').textContent = data.bareme_info || '-';


        // Commission row (only show for different operators)
        const commissionRow = document.getElementById('s-commission-row');
        if (!data.meme_operateur && data.commission > 0) {
          commissionRow.classList.remove('d-none');
          document.getElementById('s-commission').textContent = formatMoney(data.commission);
          document.getElementById('s-commission-label').textContent = data.commission_pourcentage + '%';
        } else {
          commissionRow.classList.add('d-none');
        }


        document.getElementById('s-total-debiter').textContent = formatMoney(data.montant_total);
        document.getElementById('s-montant-recu').textContent = formatMoney(data.montant_a_recevoir);
      })
      .catch(() => {});
  }




  /**
   * Calcule et affiche les détails pour le transfert multiple
   */
  function calculerDetailsMultiple() {
    const montantTotal = parseFloat(document.getElementById('montant_total').value) || 0;
    const rows = document.querySelectorAll('#destinataires-container .destinataire-row');
    const inclureFrais = document.getElementById('inclure_frais_multiple').checked;
    const detailDiv = document.getElementById('detail-multiple');
    const detailContent = document.getElementById('detail-multiple-content');


    if (montantTotal <= 0 || rows.length === 0) {
      detailDiv.classList.add('d-none');
      return;
    }


    detailDiv.classList.remove('d-none');


    // Build per-destinataire preview table
    let html = '<table class="table table-sm table-bordered mb-2">';
    html += '<thead><tr><th>Destinataire</th><th class="text-end">Montant</th><th class="text-end">Frais</th><th class="text-end">À recevoir</th></tr></thead><tbody>';


    rows.forEach(row => {
      const idx = row.dataset.index;
      const input = row.querySelector('input[name="numeros[]"]');
      const numero = input ? input.value.trim() : '';
      const montantStr = document.getElementById('montant-preview-' + idx);
      const montant = montantStr ? parseFloat(montantStr.textContent) || 0 : 0;
      const numShort = numero.length >= 10 ? numero.slice(0, 4) + '…' + numero.slice(-4) : numero;


      html += `<tr>
        <td><small>${numShort || 'N°' + idx}</small></td>
        <td class="text-end">${formatMoney(montant)}</td>
        <td class="text-end" id="m-frais-${idx}">…</td>
        <td class="text-end" id="m-recu-${idx}">…</td>
      </tr>`;
    });


    html += '</tbody></table>';
    detailContent.innerHTML = html;


    // Track totals across all fetches
    let totalFrais = 0;
    let totalDebiter = 0;
    let totalRecu = 0;
    let completedFetches = 0;


    // Now fetch actual data for each destinataire
    rows.forEach(row => {
      const idx = row.dataset.index;
      const input = row.querySelector('input[name="numeros[]"]');
      const numero = input ? input.value.trim() : '';
      const montantStr = document.getElementById('montant-preview-' + idx);
      const montant = montantStr ? parseFloat(montantStr.textContent) || 0 : 0;


      if (numero.length < 3 || montant <= 0) {
        completedFetches++;
        return;
      }


      const url = '/client/calculer-details?montant=' + encodeURIComponent(montant)
                + '&numero=' + encodeURIComponent(numero)
                + '&inclure_frais=' + (inclureFrais ? '1' : '0');


      fetch(url)
        .then(r => r.json())
        .then(data => {
          if (data.error) return;


          // Update the individual row
          const fraisEl = document.getElementById('m-frais-' + idx);
          const recuEl = document.getElementById('m-recu-' + idx);
          if (fraisEl) fraisEl.textContent = formatMoney(data.frais);
          if (recuEl) recuEl.textContent = formatMoney(data.montant_a_recevoir);


          totalFrais += data.frais;
          totalDebiter += data.montant_total;
          totalRecu += data.montant_a_recevoir;
          completedFetches++;


          // Update summary when all fetches done
          if (completedFetches === rows.length) {
            document.getElementById('m-total-frais').textContent = formatMoney(totalFrais);
            document.getElementById('m-total-debiter').textContent = formatMoney(totalDebiter);
            document.getElementById('m-total-recu').textContent = formatMoney(totalRecu);
          }
        })
        .catch(() => {
          completedFetches++;
        });
    });
  }
  </script>
  </body>
  </html>
