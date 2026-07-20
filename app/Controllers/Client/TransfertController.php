<?php


namespace App\Controllers\Client;


use App\Controllers\BaseController;
use App\Models\BaremeFraisModel;
use App\Models\CompteClientModel;
use App\Models\CommissionOperateurModel;
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


    /**
     * Transfert simple vers un seul destinataire
     */
    public function store()
    {
        $montant = (float) $this->request->getPost('montant');
        $numeroDestination = trim((string) $this->request->getPost('numero_destination'));
        $inclureFrais = (bool) $this->request->getPost('inclure_frais');


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


        // Vérifier préfixe destination
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


        // Vérifier si l'opérateur destinataire est le même que l'expéditeur
        $expediteurCompte = $compteModel->find($expediteurId);
        $statutExpediteur = $prefixeModel->getOperateurByNumero($expediteurCompte['numero_telephone']);
        $statutDestinataire = $prefixeModel->getOperateurByNumero($numeroDestination);


        $db = $compteModel->db;
        $db->transStart();


        try {
            $bareme = $baremeModel->getBaremePourMontant((int)$typeTransfert['id'], $montant);
            if (! $bareme) {
                throw new RuntimeException('Montant hors barème pour le transfert.');
            }


            $frais = (float)$bareme['frais'];


            // Pas de frais de retrait si l'opérateur destinataire est différent
            $estMemeOperateur = ($statutExpediteur && $statutDestinataire
                && $statutExpediteur['id'] === $statutDestinataire['id']);


            if (! $estMemeOperateur) {
                $frais = 0;
            }


            // Commission supplémentaire pour les transferts vers d'autres opérateurs
            $commissionModel = new CommissionOperateurModel();
            $commission = 0;
            if (! $estMemeOperateur && $statutDestinataire) {
                $commission = $commissionModel->calculerCommission((int)$statutDestinataire['id'], $montant);
            }


            if ($inclureFrais) {
                $montantADebiter = $montant + $frais + $commission;
                $montantARecevoir = $montant;
            } else {
                $montantADebiter = $montant;
                $montantARecevoir = $montant - $frais - $commission;
                if ($montantARecevoir <= 0) {
                    throw new RuntimeException('Montant insuffisant après déduction des frais.');
                }
            }


            $compteModel->debiter($expediteurId, $montantADebiter);
            $compteModel->crediter($destId, $montantARecevoir);


            $reference = $operationModel->genererReference();


            $operationModel->insert([
                'reference' => $reference,
                'type_operation_id' => (int)$typeTransfert['id'],
                'compte_source_id' => $expediteurId,
                'compte_destination_id' => $destId,
                'bareme_id' => (int)$bareme['id'],
                'montant' => $montant,
                'frais' => $frais + $commission,
                'montant_total' => $montantADebiter,
                'statut' => OperationModel::STATUT_REUSSI,
            ]);


            $db->transComplete();
            return redirect()->to('/client/dashboard')->with('message', 'Transfert effectué.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Échec transfert : ' . $e->getMessage());
        }
    }


    /**
     * Vérification AJAX : retourne l'opérateur d'un numéro
     */
    public function verifierOperateur()
    {
        $numero = trim((string) $this->request->getGet('numero'));
        if ($numero === '') {
            return $this->response->setJSON(['operateur' => null, 'meme_operateur' => false]);
        }


        $prefixeModel = new PrefixeModel();
        $operateur = $prefixeModel->getOperateurByNumero($numero);


        if (! $operateur) {
            return $this->response->setJSON(['operateur' => null, 'meme_operateur' => false]);
        }


        $compteModel = new CompteClientModel();
        $expediteurId = (int) session()->get('client_id');
        $expediteur = $compteModel->find($expediteurId);
        $meme = false;
        if ($expediteur) {
            $operateurExpediteur = $prefixeModel->getOperateurByNumero($expediteur['numero_telephone']);
            $meme = ($operateurExpediteur && $operateurExpediteur['id'] === $operateur['id']);
        }


        return $this->response->setJSON([
            'operateur' => $operateur['nom'],
            'meme_operateur' => $meme,
        ]);
    }


    /**
     * Transfert multiple vers plusieurs numéros du même opérateur
     */
    public function storeMultiple()
    {
        $montantTotal = (float) $this->request->getPost('montant_total');
        $numeros = (array) $this->request->getPost('numeros');
        $inclureFrais = (bool) $this->request->getPost('inclure_frais');


        $numeros = array_filter($numeros, function ($n) {
            return trim((string) $n) !== '';
        });
        $numeros = array_values($numeros);


        if ($montantTotal <= 0) {
            return redirect()->back()->with('error', 'Montant total invalide.');
        }
        if (count($numeros) < 2) {
            return redirect()->back()->with('error', 'Veuillez ajouter au moins 2 destinataires.');
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


        // Vérifier que tous les numéros appartiennent au même opérateur
        $operateurCommun = null;
        foreach ($numeros as $num) {
            $operateur = $prefixeModel->getOperateurByNumero(trim($num));
            if (! $operateur) {
                return redirect()->back()->with('error', "Numéro $num : opérateur inconnu ou inactif.");
            }
            if ($operateurCommun === null) {
                $operateurCommun = $operateur['id'];
            } elseif ($operateur['id'] !== $operateurCommun) {
                return redirect()->back()->with('error', 'Tous les destinataires doivent appartenir au même opérateur.');
            }
        }


        // Vérifier que l'opérateur des destinataires est le même que l'expéditeur
        $expediteurCompte = $compteModel->find($expediteurId);
        $operateurExpediteur = $prefixeModel->getOperateurByNumero($expediteurCompte['numero_telephone']);
        if (! $operateurExpediteur || $operateurExpediteur['id'] !== $operateurCommun) {
            return redirect()->back()->with('error', 'Le transfert multiple est réservé aux transferts vers le même opérateur.');
        }


        // Répartition du montant
        $nbDestinataires = count($numeros);
        $montantParPersonne = floor(($montantTotal * 100) / $nbDestinataires) / 100;
        $reste = round($montantTotal - ($montantParPersonne * $nbDestinataires), 2);


        $montants = [];
        for ($i = 0; $i < $nbDestinataires; $i++) {
            $montants[$i] = $montantParPersonne;
        }
        $montants[0] = round($montants[0] + $reste, 2);


        $db = $compteModel->db;
        $db->transStart();


        try {
            $montantTotalADebiter = 0;


            foreach ($numeros as $i => $num) {
                $num = trim($num);
                $montant = $montants[$i];


                if ($montant <= 0) {
                    throw new RuntimeException('Montant réparti invalide pour un destinataire.');
                }


                $prefixeModel->getPrefixeActif($num);
                $destinationCompte = $compteModel->trouverOuCreerCompte($num);
                $destId = (int) $destinationCompte['id'];


                if ($destId === $expediteurId) {
                    throw new RuntimeException('Impossible de transférer vers votre propre compte.');
                }


                $bareme = $baremeModel->getBaremePourMontant((int)$typeTransfert['id'], $montant);
                if (! $bareme) {
                    throw new RuntimeException('Montant hors barème pour le transfert.');
                }


                $frais = (float)$bareme['frais'];


                if ($inclureFrais) {
                    $montantADebiter = $montant + $frais;
                    $montantARecevoir = $montant;
                } else {
                    $montantADebiter = $montant;
                    $montantARecevoir = $montant - $frais;
                    if ($montantARecevoir <= 0) {
                        throw new RuntimeException('Montant insuffisant après déduction des frais.');
                    }
                }


                $montantTotalADebiter += $montantADebiter;


                $compteModel->crediter($destId, $montantARecevoir);


                $reference = $operationModel->genererReference();
                $operationModel->insert([
                    'reference' => $reference,
                    'type_operation_id' => (int)$typeTransfert['id'],
                    'compte_source_id' => $expediteurId,
                    'compte_destination_id' => $destId,
                    'bareme_id' => (int)$bareme['id'],
                    'montant' => $montant,
                    'frais' => $frais,
                    'montant_total' => $montantADebiter,
                    'statut' => OperationModel::STATUT_REUSSI,
                ]);
            }


            $compteModel->debiter($expediteurId, $montantTotalADebiter);


            $db->transComplete();
            return redirect()->to('/client/dashboard')->with('message', 'Transferts multiples effectués avec succès.');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Échec transfert multiple : ' . $e->getMessage());
        }
    }
}
