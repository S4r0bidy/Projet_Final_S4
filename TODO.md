# TODO - Projet Mobile Money (Sarobidy)

## Étape 1 — Sécurité & Auth admin
- [X] Créer `app/Filters/AdminAuthFilter.php`
- [X] Créer `app/Controllers/Admin/AuthAdminController.php`


## Étape 2 — Côté opérateur (admin)
- [X] Créer `app/Controllers/Admin/DashboardController.php` + vue
- [X] Créer `app/Controllers/Admin/OperateurController.php` + vues
- [X] Créer `app/Controllers/Admin/PrefixeController.php` + vues
- [X] Créer `app/Controllers/Admin/BaremeFraisController.php` + vues

  - [ ] Validation non-chevauchement des tranches
- [ ] Créer `app/Controllers/Admin/CompteClientController.php` + vues
- [ ] Créer `app/Controllers/Admin/GainController.php` + vues

## Étape 3 — Côté client
- [X] Créer `app/Controllers/Client/DashboardController.php` (si manque déjà) + vue
- [X] Créer `app/Controllers/Client/DepotController.php` + vue
- [X] Créer `app/Controllers/Client/RetraitController.php` + vue
- [X] Créer `app/Controllers/Client/TransfertController.php` + vue
- [X] Créer `app/Controllers/Client/HistoriqueController.php` + vue


## Étape 4 — Logique métier opérations
- [X] Dépôt : crédit solde + insertion operation (frais=0)
- [ ] Retrait : vérifier solde suffisant (montant + frais), calcul frais par tranche, insertion operation
- [ ] Transfert : créer destination si préfixe actif, calcul frais transfert, débit/crédit + insertion operation
- [X] Utiliser `OperationModel::genererReference()`

## Étape 5 — Tests manuels
- [ ] Tester admin : activer préfixe, modifier barèmes
- [ ] Tester client : dépôt, retrait, transfert, historique
- [ ] Tester admin : gains et situation comptes

