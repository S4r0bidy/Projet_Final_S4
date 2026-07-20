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

    // Tout ce qui suit nécessite d'être connecté en tant qu'admin
    // (filtre à créer plus tard, ex: 'filter' => 'adminAuth')
    $routes->group('', ['filter' => 'adminAuth'], function ($routes) {

        $routes->get('dashboard', 'DashboardController::index');

        // Configuration des préfixes / opérateurs
        $routes->get('operateurs', 'OperateurController::index');
        $routes->post('operateurs/create', 'OperateurController::create');

        $routes->get('prefixes', 'PrefixeController::index');
        $routes->post('prefixes/create', 'PrefixeController::create');
        $routes->get('prefixes/(:num)/edit', 'PrefixeController::edit/$1');
        $routes->post('prefixes/(:num)/update', 'PrefixeController::update/$1');
        $routes->post('prefixes/(:num)/toggle', 'PrefixeController::toggleActif/$1');

        // Barèmes de frais (retrait / transfert), modifiables
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
    });
});

// -------------------------------------------------------------------
// GROUPE CLIENT
// -------------------------------------------------------------------
$routes->group('client', ['namespace' => 'App\Controllers\Client'], function ($routes) {

    // Login automatique par numéro de téléphone (pas d'inscription)
    $routes->get('login', 'AuthClientController::index');
    $routes->post('login', 'AuthClientController::login');
    $routes->get('logout', 'AuthClientController::logout');

    // Tout ce qui suit nécessite d'être connecté en tant que client
    $routes->group('', ['filter' => 'clientAuth'], function ($routes) {

        $routes->get('dashboard', 'DashboardController::index'); // voir le solde

        $routes->get('depot', 'DepotController::index');
        $routes->post('depot', 'DepotController::store');

        $routes->get('retrait', 'RetraitController::index');
        $routes->post('retrait', 'RetraitController::store');

        $routes->get('transfert', 'TransfertController::index');
        $routes->post('transfert', 'TransfertController::store');

        $routes->get('historique', 'HistoriqueController::index');
    });

    
});