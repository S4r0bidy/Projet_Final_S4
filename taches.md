# Simulateur d'opérateur Mobile Money

# TODO - Projet V1

## Phase 1 — Conception
### Base de données
- [X] Créer `prefixe`
- [X] Créer `comptes_clients`
- [X] Créer `type_operation`
- [X] Créer `baremes_frais`
- [X] Créer `types_operation`

## Phase 2 — Structure du projet
### Configuration
- [X] `config/Config.php`

### Modèles
- [X] `model/CompteClientModel.php`
- [X] `model/OperateurTelecomModel.php`
- [X] `model/PrefixeModel.php`
- [X] `model/TypeOperationModel.php`
- [X] `model/BaremeFraisModel.php`
- [X] `model/OperationModel.php`
- [X] `model/AdministrateurModel.php`

### Contrôleurs
- [X] `controller/client/AuthClientController.php`
- [X] `controller/client/DashboardController.php`
- [X] `controller/client/DepotController.php`
- [X] `controller/client/HistoriqueController.php`
- [X] `controller/client/RetraitController.php`
- [X] `controller/client/TransfertController.php`

- [X] `controller/admin/AuthAdminController.php`
- [X] `controller/admin/BaremeFraisController.php`
- [X] `controller/admin/CompteClientController.php`
- [X] `controller/admin/DashboardController.php`
- [X] `controller/admin/GainController.php`
- [X] `controller/admin/OperateurController.php`
- [X] `controller/admin/PrefixeController.php`

### Vues
- [X] `views/dashboard.php`

- [X] `views/client/auth/login.php`
- [X] `views/client/depot/index.php`
- [X] `views/client/historique/index.php`
- [X] `views/client/retrait/index.php`
- [X] `views/client/transfert/index.php`

- [X] `views/admin/auth/login.php`
- [X] `views/admin/baremes/edit.php`
- [X] `views/admin/baremes/index.php`
- [X] `views/admin/comptes/index.php`
- [X] `views/admin/comptes/show.php`
- [X] `views/admin/dashboard/index.php`
- [X] `views/admin/gains/index.php`
- [X] `views/admin/operateurs/index.php`
- [X] `views/admin/prefixes/index.php`
- [X] `views/admin/prefixes/edit.php`

## Phase 3 — Fonctionnalités Opérateur
- [X] Gestion des préfixes (CRUD)
- [X] Gestion des types d'opérations
- [X] Gestion des barèmes de frais
- [X] Consultation des comptes clients

## Phase 4 — Fonctionnalités Client
- [X] Connexion automatique par numéro
- [X] Création automatique du compte
- [X] Consulter le solde
- [X] Dépôt
- [X] Retrait
- [X] Transfert
- [X] Historique

## Taches

## Authentification

### Côté opérateur (admin)
- [X] Table + Model `Administrateurs`
- [X] Formulaire de connexion (username / mot de passe hashé)
- [X] Déconnexion

### Côté client
- [X] Formulaire de connexion par numéro de téléphone uniquement (pas d'inscription)
- [X] Vérifier que le préfixe du numéro saisi correspond à un préfixe actif (table `prefixes_operateur`)
- [X] Si le numéro n'existe pas encore en base : création automatique du compte (solde = 0)
- [X] Session client (numéro de téléphone / id compte)
- [X] Déconnexion

## Module Opérateur

- [X] CRUD **Préfixes valables** (ex : 033, 037) — activer/désactiver un préfixe
- [X] CRUD **Types d'opération** (Dépôt, Retrait, Transfert)
- [X] CRUD **Barèmes de frais** par tranche de montant, modifiable, rattaché à un type d'opération
  - [X] Formulaire d'ajout d'une tranche (montant min, montant max, frais)
  - [X] Validation : pas de chevauchement entre tranches d'un même type d'opération
- [X] Écran **Situation des gains** : total des frais perçus (retrait + transfert), filtrable par période / type d'opération
- [X] Écran **Situation des comptes clients** : liste des comptes avec solde, date de création, nombre d'opérations
- [X] Tableau de bord opérateur (résumé : nb clients, solde total, gains du jour)

## Module Client

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

## Logique métier commune

- [X] Service/Helper `CalculFrais` : trouver la tranche applicable pour un type d'opération + montant, retourner le frais
- [X] Génération d'une référence unique par opération (ex : `OP-20260720-000123`)
- [X] Gestion des erreurs métier : solde insuffisant, préfixe invalide, montant hors barème, compte destinataire introuvable
- [X] Journalisation systématique de chaque opération dans la table `operations`


## TO DO V2

# Phase 1-1 — Base de données

## Commission des transferts inter-opérateurs

- [X] Créer une table `commission_operateur`

Cette table doit contenir :

- [X] opérateur destination
- [X] pourcentage supplémentaire
- [X] date de création
- [X] statut actif

## Situation des gains
- [X] Ajouter l'information :
    - même opérateur
    - autre opérateur

## Situation des transferts

Créer une nouvelle vue ou table permettant de calculer :

- [X] Total à envoyer vers Orange Money
- [X] Total à envoyer vers Airtel Money
- [X] Total à envoyer vers Telma
- [X] Total à envoyer vers les autres opérateurs

# Phase 1-2 — Modèles

## Créer ou modifier les modèles

- [X] Modifier `PrefixeModel.java`
- [X] Créer `OperateurModel.java`
- [X] Créer `CommissionOperateurModel.java`
- [X] Modifier `OperationModel.java`

## Gestion des opérateurs
- [X] CRUD opérateur:
  - [X] Ajouter un opérateur
  - [X] Modifier un opérateur
  - [X] Supprimer

## Gestion des commissions
- [X]CRUD commissiom:
  - [X] Ajouter une commission
  - [X] Modifier une commission
  - [X] Supprimer
  - [X] Calcul automatique des commissions

# Phase 1-3 — Interface Opérateur

## Gestion des opérateurs

Créer

- [X] `operateur.php`

Fonctionnalités

- [X] Ajouter un opérateur
- [X] Modifier
- [X] Supprimer
- [X] Rechercher

## Gestion des commissions

Créer

- [X] `commission.php`

Fonctionnalités

- [X] Ajouter un pourcentage
- [X] Modifier
- [X] Désactiver

## Situation des gains

Modifier

- [X] `gains.php`

Afficher séparément

- [X] Gains sur les retraits
- [X] Gains des transferts internes
- [X] Gains des transferts vers les autres opérateurs


## Situation des montants à envoyer

Créer

- [X] `SituationOperateur.php`

Afficher

- [X] Nom opérateur
- [X] Nombre de transferts
- [X] Montant total
- [X] Date

# Phase 1-4 — Interface Client

## Option "Inclure les frais"

Modifier

- [X] `transfert/index.php`

Ajouter

- [X] Case à cocher

```
☐ Inclure les frais de retrait
```

Traitement (JavaScript)

- [X] Si coché
    - le destinataire reçoit exactement le montant demandé
    - l'expéditeur paie également les frais

- [X] Si non coché
    - fonctionnement classique

## Suppression des frais de retrait

Modifier

- [X] Calcul des frais

Règle

- [X] Aucun frais de retrait pour les autres opérateurs


## Transfert multiple

Modifier
- Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro) même opérateur uniquement

- [X] Bouton "Ajouter un destinataire"

## Répartition automatique

Fonctions

- [X] Vérifier le montant total
- [X] Diviser automatiquement le montant
- [X] Arrondir correctement
- [X] Effectuer chaque transfert


## Vérification des opérateurs

Avant le transfert

- [X] Vérifier que tous les numéros appartiennent au même opérateur

Si non

- [X] Refuser le transfert

Message

```
Tous les destinataires doivent appartenir au même opérateur.
```

---

# Phase 1-5 — Historique

Ajouter

- [X] Opérateur destinataire
- [X] Type de transfert
- [X] Commission supplémentaire
- [X] Frais inclus (Oui / Non)

# Phase 1-6 — Statistiques

Créer

- [X] Statistiques des transferts internes

Créer

- [X] Statistiques des transferts externes

Créer

- [X] Statistiques par opérateur

Créer

- [X] Montants envoyés par opérateur

Créer

- [X] Total des commissions supplémentaires

Notion d'epargne 
Pour chaque client, il choisit le pourcentage de l'epargne 
Par exemple 20 % epargne donc si le client recoit un tranfert venant de quelqu'un d'autre le 20% du montant recu vas dans l'epargne
Faire une page pour saisir les pourcentage à épargner pour le client 