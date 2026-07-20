<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class StatistiqueController extends BaseController
{
    public function index()
    {
        $operationModel = new OperationModel();

        // Statistiques des transferts internes
        $internes = $this->getStatsFromView('v_statistiques_transferts_internes');

        // Statistiques des transferts externes
        $externes = $this->getStatsFromView('v_statistiques_transferts_externes');

        // Statistiques par operateur
        $statsParOperateur = $operationModel->getStatistiquesParOperateur();

        // Total des commissions supplementaires
        $totalCommissions = $operationModel->getTotalCommissionsSupplementaires();

        return view('admin/statistiques/index', [
            'internes'            => $internes,
            'externes'            => $externes,
            'statsParOperateur'   => $statsParOperateur,
            'totalCommissions'    => $totalCommissions,
        ]);
    }

    private function getStatsFromView(string $viewName): array
    {
        $db = \Config\Database::connect();
        $result = $db->table($viewName)->get()->getRowArray();
        return $result ?: [
            'montant_total' => 0,
            'nb_transferts' => 0,
            'total_frais' => 0,
            'nb_expediteurs' => 0,
            'nb_destinataires' => 0,
        ];
    }
}
