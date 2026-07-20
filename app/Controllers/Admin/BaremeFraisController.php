<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;

class BaremeFraisController extends BaseController
{
    protected BaremeFraisModel $baremeModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->baremeModel = new BaremeFraisModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $types = $this->typeOperationModel->findAll();
        $baremesParType = [];

        foreach ($types as $type) {
            $baremesParType[$type['id']] = [
                'type'    => $type,
                'baremes' => $this->baremeModel->getBaremesParType((int) $type['id']),
            ];
        }

        return view('admin/baremes/index', [
            'types'          => $types,
            'baremesParType' => $baremesParType,
        ]);
    }

    public function create()
    {
        $typeOperationId = (int) $this->request->getPost('type_operation_id');
        $montantMin = (float) $this->request->getPost('montant_min');
        $montantMax = $this->request->getPost('montant_max');
        $frais = (float) $this->request->getPost('frais');

        if ($typeOperationId <= 0 || $montantMin < 0 || $frais <= 0) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs obligatoires.');
        }

        $data = [
            'type_operation_id' => $typeOperationId,
            'montant_min'       => $montantMin,
            'montant_max'       => ($montantMax !== '' && $montantMax !== null) ? (float) $montantMax : null,
            'frais'             => $frais,
            'actif'             => 1,
        ];

        try {
            $this->baremeModel->insert($data);
            return redirect()->to('/admin/baremes')->with('message', 'Barème créé avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du barème.');
        }
    }

    public function edit(int $id)
    {
        $bareme = $this->baremeModel->find($id);
        if (! $bareme) {
            return redirect()->to('/admin/baremes')->with('error', 'Barème introuvable.');
        }

        $types = $this->typeOperationModel->findAll();

        return view('admin/baremes/edit', [
            'bareme' => $bareme,
            'types'  => $types,
        ]);
    }

    public function update(int $id)
    {
        $bareme = $this->baremeModel->find($id);
        if (! $bareme) {
            return redirect()->to('/admin/baremes')->with('error', 'Barème introuvable.');
        }

        $montantMin = (float) $this->request->getPost('montant_min');
        $montantMax = $this->request->getPost('montant_max');
        $frais = (float) $this->request->getPost('frais');

        $data = [
            'montant_min' => $montantMin,
            'montant_max' => ($montantMax !== '' && $montantMax !== null) ? (float) $montantMax : null,
            'frais'       => $frais,
        ];

        $this->baremeModel->update($id, $data);

        return redirect()->to('/admin/baremes')->with('message', 'Barème mis à jour.');
    }
}

