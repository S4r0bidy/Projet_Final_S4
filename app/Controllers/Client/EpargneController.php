<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\CompteClientModel;

class EpargneController extends BaseController
{
    protected CompteClientModel $compteModel;

    public function __construct(){
        $this->compteModel = new CompteClientModel();
    }

    public function index()
    {
        $clientId = session()->get('client_id');
        $client = $this->compteModel->find($clientId);

        if (!$client) {
            return redirect()
            ->to('/client/dashboard')
            ->with('error', 'Compte introuvable');
        }

        return view('client/epargne', [
            'client' => $client
        ]);
    }

    public function update()
    {
        $clientId = session()->get('client_id');

        $client = $this->compteModel->find($clientId);

        if (!$client) {
            return redirect()
                ->back()
                ->with('error', 'Compte introuvable.');
        }
        $pourcentage = (float)$this->request->getPost('pourcentage_epargne');

        if ($pourcentage < 0 || $pourcentage > 100) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pourcentage incorrecte');
        }
        try {
            $this->$compteModel->update($clientId, [
                'pourcentage_epargne' => $pourcentage
            ]);
            return redirect()
                ->to('/client/epargne')
                ->with('message', 'Pourcentage d\'epargne mis à jour avec succès');

        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur:' .$th->getMessage());
        }
    }

}