# Simulateur d'opérateur Mobile Money

## Phase 0 — Mise en place du projet

- [X] Configuration CodeIgniter 4
- [X] Configurer la connexion SQLite dans `.env` (`database.default.DBDriver = SQLite3`, `database.default.database = database.db`)
- [X] Créer la base à partir de `base.sql`
- [X] Mettre en place la structure de dossiers : `Controllers/Admin`, `Controllers/Client`, `Models`, `Views/admin`, `Views/client`
- [X] Définir les routes de base (`app/Config/Routes.php`) : groupe `admin` et groupe `client`

## Phase 1 — Authentification

### Côté opérateur (admin)
- [X] Table + Model `Administrateurs`
- [ ] Formulaire de connexion (username / mot de passe hashé)
- [ ] Déconnexion

### Côté client
- [ ] Formulaire de connexion par numéro de téléphone uniquement (pas d'inscription)
- [ ] Vérifier que le préfixe du numéro saisi correspond à un préfixe actif (table `prefixes_operateur`)
- [ ] Si le numéro n'existe pas encore en base : création automatique du compte (solde = 0)
- [ ] Session client (numéro de téléphone / id compte)
- [ ] Déconnexion

## Phase 2 — Module Opérateur

- [X] CRUD **Préfixes valables** (ex : 033, 037) — activer/désactiver un préfixe
- [X] CRUD **Types d'opération** (Dépôt, Retrait, Transfert)
- [X] CRUD **Barèmes de frais** par tranche de montant, modifiable, rattaché à un type d'opération
  - [X] Formulaire d'ajout d'une tranche (montant min, montant max, frais)
  - [X] Validation : pas de chevauchement entre tranches d'un même type d'opération
- [X] Écran **Situation des gains** : total des frais perçus (retrait + transfert), filtrable par période / type d'opération
- [X] Écran **Situation des comptes clients** : liste des comptes avec solde, date de création, nombre d'opérations
- [X] Tableau de bord opérateur (résumé : nb clients, solde total, gains du jour)

## Phase 3 — Module Client

- [X] Écran **Voir le solde**
- [X] **Dépôt** (crédit automatique et immédiat du compte, sans frais — à confirmer)
- [X] **Retrait**
  - [X] Vérifier le solde suffisant (montant + frais)
  - [X] Calculer les frais selon le barème de la tranche correspondante
  - [X] Débiter automatiquement
- [X] **Transfert**
  - [X] Vérifier que le compte destinataire existe (ou le créer si numéro valide et inconnu)
  - [X] Calculer les frais selon le barème "transfert"
  - [X] Débiter l'expéditeur (montant + frais), créditer le destinataire (montant)
- [X] **Historique des opérations** du compte connecté (dépôts, retraits, transferts envoyés/reçus)

## Phase 4 — Logique métier commune

- [X] Service/Helper `CalculFrais` : trouver la tranche applicable pour un type d'opération + montant, retourner le frais
- [X] Génération d'une référence unique par opération (ex : `OP-20260720-000123`)
- [X] Gestion des erreurs métier : solde insuffisant, préfixe invalide, montant hors barème, compte destinataire introuvable
- [X] Journalisation systématique de chaque opération dans la table `operations`


