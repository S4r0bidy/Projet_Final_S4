<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Client\AuthClientController::index');

// -------------------------------------------------------------------
// GROUPE ADMIN
// -------------------------------------------------------------------
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

    // Authentification admin
    $routes->get('login', 'AuthAdminController::index');
    $routes->post('login', 'AuthAdminController::login');
    $routes->get('logout', 'AuthAdminController::logout');

    // Tout ce qui suit necessite d'etre connecte en tant qu'admin
    $routes->group('', ['filter' => 'adminAuth'], function ($routes) {

        $routes->get('dashboard', 'DashboardController::index');

        // Configuration des operateurs
        $routes->get('operateurs', 'OperateurController::index');
        $routes->post('operateurs/create', 'OperateurController::create');
        $routes->get('operateurs/(:num)/edit', 'OperateurController::edit/$1');
        $routes->post('operateurs/(:num)/update', 'OperateurController::update/$1');
        $routes->post('operateurs/(:num)/delete', 'OperateurController::delete/$1');

        // Commissions inter-operateurs
        $routes->get('commissions', 'CommissionController::index');
        $routes->post('commissions/create', 'CommissionController::create');
        $routes->get('commissions/(:num)/edit', 'CommissionController::edit/$1');
        $routes->post('commissions/(:num)/update', 'CommissionController::update/$1');
        $routes->post('commissions/(:num)/toggle', 'CommissionController::toggleActif/$1');

        // Configuration des prefixes
        $routes->get('prefixes', 'PrefixeController::index');
        $routes->post('prefixes/create', 'PrefixeController::create');
        $routes->get('prefixes/(:num)/edit', 'PrefixeController::edit/$1');
        $routes->post('prefixes/(:num)/update', 'PrefixeController::update/$1');
        $routes->post('prefixes/(:num)/toggle', 'PrefixeController::toggleActif/$1');

        // Baremes de frais (retrait / transfert)
        $routes->get('baremes', 'BaremeFraisController::index');
        $routes->get('baremes/(:num)/edit', 'BaremeFraisController::edit/$1');
        $routes->post('baremes/(:num)/update', 'BaremeFraisController::update/$1');
        $routes->post('baremes/create', 'BaremeFraisController::create');

        // Situation des comptes clients
        $routes->get('comptes', 'CompteClientController::index');
        $routes->get('comptes/(:num)', 'CompteClientController::show/$1');
        $routes->post('comptes/(:num)/bloquer', 'CompteClientController::bloquer/$1');
        $routes->post('comptes/(:num)/debloquer', 'CompteClientController::debloquer/$1');

        // Situation des gains via les frais (retrait / transfert)
        $routes->get('gains', 'GainController::index');

        // Situation des montants a envoyer aux operateurs
        $routes->get('situation-operateurs', 'SituationOperateurController::index');

        // Statistiques des transferts
        $routes->get('statistiques', 'StatistiqueController::index');
    });
});

// -------------------------------------------------------------------
// GROUPE CLIENT
// -------------------------------------------------------------------
$routes->group('client', ['namespace' => 'App\Controllers\Client'], function ($routes) {

    // Login automatique par numero de telephone
    $routes->get('login', 'AuthClientController::index');
    $routes->post('login', 'AuthClientController::login');
    $routes->get('logout', 'AuthClientController::logout');

    // Tout ce qui suit necessite d'etre connecte en tant que client
    $routes->group('', ['filter' => 'clientAuth'], function ($routes) {

        $routes->get('dashboard', 'DashboardController::index');

        $routes->get('depot', 'DepotController::index');
        $routes->post('depot', 'DepotController::store');

        $routes->get('retrait', 'RetraitController::index');
        $routes->post('retrait', 'RetraitController::store');

        $routes->get('transfert', 'TransfertController::index');
        $routes->post('transfert', 'TransfertController::store');
        $routes->post('transfert/multiple', 'TransfertController::storeMultiple');
        $routes->get('verifier-operateur', 'TransfertController::verifierOperateur');

        $routes->get('historique', 'HistoriqueController::index');
    });
});
