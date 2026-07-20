<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\CompteClientModel;
use RuntimeException;

class AuthClientController extends BaseController
{
    protected CompteClientModel $compteClientModel;

    public function __construct()
    {
        $this->compteClientModel = new CompteClientModel();
    }

    /**
     * Affiche le formulaire de connexion (ou redirige si déjà connecté)
     */
    public function index()
    {
        if (session()->get('isClientLoggedIn')) {
            return redirect()->to('/client/dashboard');
        }

        return view('client/auth/login');
    }

    /**
     * Traite la connexion par numéro de téléphone
     */
    public function login()
    {
        $numero = trim($this->request->getPost('numero_telephone'));

        // Validation basique du format
        $rules = [
            'numero_telephone' => 'required|regex_match[/^0[0-9]{9}$/]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                              ->withInput()
                              ->with('errors', $this->validator->getErrors());
        }

        try {
            // Trouve le compte existant OU le crée automatiquement
            $compte = $this->compteClientModel->trouverOuCreerCompte($numero);
        } catch (RuntimeException $e) {
            // Préfixe inconnu ou inactif
            return redirect()->back()
                              ->withInput()
                              ->with('error', $e->getMessage());
        }

        // Vérifie que le compte n'est pas bloqué
        if ($compte['statut'] === CompteClientModel::STATUT_BLOQUE) {
            return redirect()->back()
                              ->withInput()
                              ->with('error', 'Ce compte est bloqué. Contactez l\'opérateur.');
        }

        // Ouverture de la session client
        session()->set([
            'client_id'               => $compte['id'],
            'client_numero_telephone' => $compte['numero_telephone'],
            'client_statut'           => $compte['statut'],
            'isClientLoggedIn'        => true,
        ]);

        return redirect()->to('/client/dashboard');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/client/login')->with('message', 'Vous avez été déconnecté.');
    }
}