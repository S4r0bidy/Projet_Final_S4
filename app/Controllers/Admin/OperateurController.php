<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OperateurTelecomModel;

class OperateurController extends BaseController
{
    protected OperateurTelecomModel $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurTelecomModel();
    }

    public function index()
    {
        $operateurs = $this->operateurModel->findAll();

        return view('admin/operateurs/index', [
            'operateurs' => $operateurs,
        ]);
    }

    public function create()
    {
        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return redirect()->back()->with('error', 'Le nom de l\'opérateur est requis.');
        }

        try {
            $this->operateurModel->insert(['nom' => $nom]);
            return redirect()->to('/admin/operateurs')->with('message', 'Opérateur créé avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de créer cet opérateur (existe peut-être déjà).');
        }
    }
}

