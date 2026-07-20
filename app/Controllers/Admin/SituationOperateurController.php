<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class SituationOperateurController extends BaseController
{
    public function index()
    {
        $operationModel = new OperationModel();
        $situations = $operationModel->getSituationOperateurs();

        return view('admin/situation_operateur/index', [
            'situations' => $situations,
        ]);
    }
}
