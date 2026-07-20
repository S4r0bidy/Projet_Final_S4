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

        $totalGains = 0;
        foreach ($gains as $g) {
            $totalGains += (float) $g['total_frais'];
        }

        return view('admin/gains/index', [
            'gains'      => $gains,
            'totalGains' => $totalGains,
        ]);
    }
}

