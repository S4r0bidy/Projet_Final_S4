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

    public function edit(int $id)
    {
        $operateur = $this->operateurModel->find($id);
        if (! $operateur) {
            return redirect()->to('/admin/operateurs')->with('error', 'Opérateur introuvable.');
        }

        return view('admin/operateurs/edit', [
            'operateur' => $operateur,
        ]);
    }

    public function update(int $id)
    {
        $operateur = $this->operateurModel->find($id);
        if (! $operateur) {
            return redirect()->to('/admin/operateurs')->with('error', 'Opérateur introuvable.');
        }

        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return redirect()->back()->with('error', 'Le nom de l\'opérateur est requis.');
        }

        try {
            $this->operateurModel->update($id, ['nom' => $nom]);
            return redirect()->to('/admin/operateurs')->with('message', 'Opérateur modifié avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de modifier cet opérateur (nom existe peut-être déjà).');
        }
    }

    public function delete(int $id)
    {
        $operateur = $this->operateurModel->find($id);
        if (! $operateur) {
            return redirect()->to('/admin/operateurs')->with('error', 'Opérateur introuvable.');
        }

        try {
            $this->operateurModel->delete($id);
            return redirect()->to('/admin/operateurs')->with('message', 'Opérateur supprimé avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de supprimer cet opérateur (utilisé par des préfixes ou commissions).');
        }
    }
}
