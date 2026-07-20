<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CompteClientModel;
use App\Models\OperationModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $compteModel = new CompteClientModel();
        $operationModel = new OperationModel();

        $nbClients = $compteModel->countAll();
        $nbComptesActifs = $compteModel->where('statut', 'ACTIF')->countAllResults();
        $nbComptesBloques = $compteModel->where('statut', 'BLOQUE')->countAllResults();

        $gains = $operationModel->getGainsFrais();
        $dernieresOperations = $operationModel->orderBy('date_operation', 'DESC')->findAll(10);

        return view('admin/dashboard/index', [
            'nbClients'          => $nbClients,
            'nbComptesActifs'    => $nbComptesActifs,
            'nbComptesBloques'   => $nbComptesBloques,
            'gains'              => $gains,
            'dernieresOperations' => $dernieresOperations,
        ]);
    }
}

