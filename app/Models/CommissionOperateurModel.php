<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionOperateurModel extends Model
{
    protected $table            = 'commission_operateur';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['operateur_destination_id', 'pourcentage', 'actif'];
    protected $useTimestamps    = false;

    /**
     * Liste des commissions avec le nom de l'opérateur destination
     */
    public function getAvecOperateur()
    {
        return $this->select('commission_operateur.*, operateurs_telecom.nom as operateur_nom')
                    ->join('operateurs_telecom', 'operateurs_telecom.id = commission_operateur.operateur_destination_id')
                    ->orderBy('operateurs_telecom.nom', 'ASC')
                    ->findAll();
    }

    /**
     * Récupère la commission active pour un opérateur destination donné
     */
    public function getCommissionForOperator(int $operateurId): ?array
    {
        return $this->where('operateur_destination_id', $operateurId)
                    ->where('actif', 1)
                    ->first();
    }

    /**
     * Calcule la commission supplémentaire pour un montant et un opérateur destination
     */
    public function calculerCommission(int $operateurId, float $montant): float
    {
        $commission = $this->getCommissionForOperator($operateurId);
        if (! $commission) {
            return 0;
        }
        return round($montant * ($commission['pourcentage'] / 100), 2);
    }

    public function activer(int $id): bool
    {
        return $this->update($id, ['actif' => 1]);
    }

    public function desactiver(int $id): bool
    {
        return $this->update($id, ['actif' => 0]);
    }
}
