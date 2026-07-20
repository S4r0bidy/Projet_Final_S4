<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PrefixeModel;
use App\Models\OperateurTelecomModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;
    protected OperateurTelecomModel $operateurModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
        $this->operateurModel = new OperateurTelecomModel();
    }

    public function index()
    {
        $prefixes = $this->prefixeModel->getAvecOperateur();
        $operateurs = $this->operateurModel->findAll();

        return view('admin/prefixes/index', [
            'prefixes' => $prefixes,
            'operateurs' => $operateurs,
        ]);
    }

    public function create()
    {
        $prefixe = trim((string) $this->request->getPost('prefixe'));
        $operateurId = (int) $this->request->getPost('operateur_telecom_id');

        if ($prefixe === '' || $operateurId <= 0) {
            return redirect()->back()->with('error', 'Préfixe et opérateur requis.');
        }

        try {
            $this->prefixeModel->insert([
                'prefixe' => $prefixe,
                'operateur_telecom_id' => $operateurId,
                'actif' => 1,
            ]);

            return redirect()->to('/admin/prefixes')->with('message', 'Préfixe créé.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Impossible de créer ce préfixe (existe peut-être déjà).');
        }
    }

    public function toggleActif(int $num)
    {
        $prefix = $this->prefixeModel->find($num);
        if (! $prefix) {
            return redirect()->to('/admin/prefixes')->with('error', 'Préfixe introuvable.');
        }

        $newActif = ((int)$prefix['actif'] === 1) ? 0 : 1;
        $this->prefixeModel->update($num, ['actif' => $newActif]);

        return redirect()->to('/admin/prefixes')->with('message', 'Préfixe mis à jour.');
    }
}

