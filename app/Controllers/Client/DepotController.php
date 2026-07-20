<?php


namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\CompteClientModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;


class DepotController extends BaseController
{
    public function index()
    {
        return view('client/depot/index');
    }


    public function store()
    {
        $montant = (float) $this->request->getPost('montant');
        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }


        $compteId = (int) session()->get('client_id');
        $compteModel = new CompteClientModel();
        $operationModel = new OperationModel();
        $typeModel = new TypeOperationModel();


        $typeDepot = $typeModel->getByCode(TypeOperationModel::DEPOT);
        if (! $typeDepot) {
            return redirect()->back()->with('error', 'Type DEPOT introuvable.');
        }


        // Transaction simple (SQLite)
        $db = $compteModel->db;
        $db->transStart();


        try {
            $compteModel->crediter($compteId, $montant);


            $reference = $operationModel->genererReference();


            $operationModel->insert([
                'reference' => $reference,
                'type_operation_id' => (int)$typeDepot['id'],
                'compte_source_id' => null,
                'compte_destination_id' => $compteId,
                'bareme_id' => null,
                'montant' => $montant,
                'frais' => 0,
                'montant_total' => $montant,
                'statut' => OperationModel::STATUT_REUSSI,
            ]);


            $db->transComplete();


            return redirect()->to('/client/dashboard')->with('message', 'Dépôt effectué.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Échec dépôt : ' . $e->getMessage());
        }
    }
}
