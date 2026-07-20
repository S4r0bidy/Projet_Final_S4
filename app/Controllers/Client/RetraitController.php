<?php


namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\BaremeFraisModel;
use App\Models\CompteClientModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;


class RetraitController extends BaseController
{
    public function index()
    {
        return view('client/retrait/index');
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
        $baremeModel = new BaremeFraisModel();


        $typeRetrait = $typeModel->getByCode(TypeOperationModel::RETRAIT);
        if (! $typeRetrait) {
            return redirect()->back()->with('error', 'Type RETRAIT introuvable.');
        }


        $db = $compteModel->db;
        $db->transStart();


        try {
            $bareme = $baremeModel->getBaremePourMontant((int)$typeRetrait['id'], $montant);
            if (! $bareme) {
                throw new \RuntimeException('Montant hors barème pour le retrait.');
            }


            $frais = (float) $bareme['frais'];
            $montantTotal = $montant + $frais;


            // Débit montantTotal
            $compteModel->debiter($compteId, $montantTotal);


            $reference = $operationModel->genererReference();


            $operationModel->insert([
                'reference' => $reference,
                'type_operation_id' => (int)$typeRetrait['id'],
                'compte_source_id' => $compteId,
                'compte_destination_id' => null,
                'bareme_id' => (int)$bareme['id'],
                'montant' => $montant,
                'frais' => $frais,
                'montant_total' => $montantTotal,
                'statut' => OperationModel::STATUT_REUSSI,
            ]);


            $db->transComplete();
            return redirect()->to('/client/dashboard')->with('message', 'Retrait effectué.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Échec retrait : ' . $e->getMessage());
        }
    }
}


