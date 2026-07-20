<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class GainController extends BaseController
{
    public function index()
    {
        $operationModel = new OperationModel();
        $gains = $operationModel->getGainsFrais();
        $gainsDetail = $operationModel->getGainsFraisDetail();

        $totalGains = 0;
        foreach ($gains as $g) {
            $totalGains += (float) $g['total_frais'];
        }

        // Séparer les gains par catégorie
        $gainsRetraits = array_filter($gainsDetail, function ($g) {
            return $g['type_operation_id'] == 2; // RETRAIT
        });
        $gainsTransfertsInternes = array_filter($gainsDetail, function ($g) {
            return $g['categorie_operateur'] === 'MEME_OPERATEUR';
        });
        $gainsTransfertsExternes = array_filter($gainsDetail, function ($g) {
            return $g['categorie_operateur'] === 'AUTRE_OPERATEUR';
        });

        return view('admin/gains/index', [
            'gains'      => $gains,
            'gainsDetail' => $gainsDetail,
            'gainsRetraits' => $gainsRetraits,
            'gainsTransfertsInternes' => $gainsTransfertsInternes,
            'gainsTransfertsExternes' => $gainsTransfertsExternes,
            'totalGains' => $totalGains,
        ]);
    }
}
