<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\CompteClientModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $compteModel = new CompteClientModel();
        $compte = $compteModel->find(session()->get('client_id'));

        return view('client/dashboard', ['compte' => $compte]);
    }
}