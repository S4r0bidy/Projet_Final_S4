<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CompteClientModel;

class CompteClientController extends BaseController
{
    protected CompteClientModel $compteModel;

    public function __construct()
    {
        $this->compteModel = new CompteClientModel();
    }

    public function index()
    {
        $comptes = $this->compteModel->getSituationComptes();

        return view('admin/comptes/index', [
            'comptes' => $comptes,
        ]);
    }

    public function show(int $id)
    {
        $compte = $this->compteModel->find($id);
        if (! $compte) {
            return redirect()->to('/admin/comptes')->with('error', 'Compte introuvable.');
        }

        $operationModel = new \App\Models\OperationModel();
        $historique = $operationModel->getHistoriquePourCompte($id);

        return view('admin/comptes/show', [
            'compte'     => $compte,
            'historique' => $historique,
        ]);
    }

    public function bloquer(int $id)
    {
        $compte = $this->compteModel->find($id);
        if (! $compte) {
            return redirect()->to('/admin/comptes')->with('error', 'Compte introuvable.');
        }

        $this->compteModel->bloquer($id);
        return redirect()->back()->with('message', 'Compte bloqué.');
    }

    public function debloquer(int $id)
    {
        $compte = $this->compteModel->find($id);
        if (! $compte) {
            return redirect()->to('/admin/comptes')->with('error', 'Compte introuvable.');
        }

        $this->compteModel->debloquer($id);
        return redirect()->back()->with('message', 'Compte débloqué.');
    }
}

