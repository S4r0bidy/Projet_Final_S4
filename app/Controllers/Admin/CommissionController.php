<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommissionOperateurModel;
use App\Models\OperateurTelecomModel;

class CommissionController extends BaseController
{
    protected CommissionOperateurModel $commissionModel;
    protected OperateurTelecomModel $operateurModel;

    public function __construct()
    {
        $this->commissionModel = new CommissionOperateurModel();
        $this->operateurModel = new OperateurTelecomModel();
    }

    public function index()
    {
        $commissions = $this->commissionModel->getAvecOperateur();
        $operateurs = $this->operateurModel->findAll();

        return view('admin/commissions/index', [
            'commissions' => $commissions,
            'operateurs' => $operateurs,
        ]);
    }

    public function create()
    {
        $operateurId = (int) $this->request->getPost('operateur_destination_id');
        $pourcentage = (float) $this->request->getPost('pourcentage');

        if ($operateurId <= 0 || $pourcentage <= 0) {
            return redirect()->back()->with('error', 'Opérateur et pourcentage requis.');
        }

        try {
            $this->commissionModel->insert([
                'operateur_destination_id' => $operateurId,
                'pourcentage' => $pourcentage,
                'actif' => 1,
            ]);

            return redirect()->to('/admin/commissions')->with('message', 'Commission créée avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de créer cette commission.');
        }
    }

    public function edit(int $id)
    {
        $commission = $this->commissionModel->find($id);
        if (! $commission) {
            return redirect()->to('/admin/commissions')->with('error', 'Commission introuvable.');
        }

        $operateurs = $this->operateurModel->findAll();

        return view('admin/commissions/edit', [
            'commission' => $commission,
            'operateurs' => $operateurs,
        ]);
    }

    public function update(int $id)
    {
        $commission = $this->commissionModel->find($id);
        if (! $commission) {
            return redirect()->to('/admin/commissions')->with('error', 'Commission introuvable.');
        }

        $pourcentage = (float) $this->request->getPost('pourcentage');

        if ($pourcentage <= 0) {
            return redirect()->back()->with('error', 'Pourcentage valide requis.');
        }

        try {
            $this->commissionModel->update($id, ['pourcentage' => $pourcentage]);
            return redirect()->to('/admin/commissions')->with('message', 'Commission mise à jour.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    public function toggleActif(int $id)
    {
        $commission = $this->commissionModel->find($id);
        if (! $commission) {
            return redirect()->to('/admin/commissions')->with('error', 'Commission introuvable.');
        }

        $newActif = ((int) $commission['actif'] === 1) ? 0 : 1;
        $this->commissionModel->update($id, ['actif' => $newActif]);

        return redirect()->to('/admin/commissions')->with('message', 'Statut de la commission mis à jour.');
    }
}
