<?php


namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\OperationModel;


class HistoriqueController extends BaseController
{
    public function index()
    {
        $compteId = (int) session()->get('client_id');
        $operationModel = new OperationModel();


        $historique = $operationModel->getHistoriquePourCompte($compteId);


        return view('client/historique/index', ['historique' => $historique]);
    }
}


