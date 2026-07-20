<?php


namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\BaremeFraisModel;
use App\Models\CompteClientModel;
use App\Models\OperationModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use RuntimeException;


class TransfertController extends BaseController
{
    public function index()
    {
        return view('client/transfert/index');
    }


    public function store()
    {
        $montant = (float) $this->request->getPost('montant');
        $numeroDestination = trim((string) $this->request->getPost('numero_destination'));


        if ($montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide.');
        }
        if ($numeroDestination === '') {
            return redirect()->back()->with('error', 'Numéro destination requis.');
        }


        $expediteurId = (int) session()->get('client_id');
        $compteModel = new CompteClientModel();
        $operationModel = new OperationModel();
        $typeModel = new TypeOperationModel();
        $baremeModel = new BaremeFraisModel();
        $prefixeModel = new PrefixeModel();


        $typeTransfert = $typeModel->getByCode(TypeOperationModel::TRANSFERT);
        if (! $typeTransfert) {
            return redirect()->back()->with('error', 'Type TRANSFERT introuvable.');
        }


        // Vérifier préfixe destination actif + possibilité de création du compte si inconnu
        try {
            $prefixeModel->getPrefixeActif($numeroDestination);
            $destinationCompte = $compteModel->trouverOuCreerCompte($numeroDestination);
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }


        $destId = (int) $destinationCompte['id'];


        if ($destId === $expediteurId) {
            return redirect()->back()->with('error', 'Impossible de transférer vers votre propre compte.');
        }


        $db = $compteModel->db;
        $db->transStart();


        try {
            $bareme = $baremeModel->getBaremePourMontant((int)$typeTransfert['id'], $montant);
            if (! $bareme) {
                throw new RuntimeException('Montant hors barème pour le transfert.');
            }


            $frais = (float)$bareme['frais'];
            $montantTotal = $montant + $frais;


            // Débit expéditeur (montant + frais)
            $compteModel->debiter($expediteurId, $montantTotal);


            // Crédit destinataire (montant)
            $compteModel->crediter($destId, $montant);


            $reference = $operationModel->genererReference();


            $operationModel->insert([
                'reference' => $reference,
                'type_operation_id' => (int)$typeTransfert['id'],
                'compte_source_id' => $expediteurId,
                'compte_destination_id' => $destId,
                'bareme_id' => (int)$bareme['id'],
                'montant' => $montant,
                'frais' => $frais,
                'montant_total' => $montantTotal,
                'statut' => OperationModel::STATUT_REUSSI,
            ]);


            $db->transComplete();
            return redirect()->to('/client/dashboard')->with('message', 'Transfert effectué.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Échec transfert : ' . $e->getMessage());
        }
    }
}


