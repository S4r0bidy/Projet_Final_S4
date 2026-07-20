<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'baremes_frais';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'type_operation_id', 'montant_min', 'montant_max', 'frais', 'actif',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Trouve la tranche de frais applicable pour un montant donné et un type d'opération.
     * montant_max NULL = pas de plafond (dernière tranche).
     */
    public function getBaremePourMontant(int $typeOperationId, float $montant): ?array
    {
        $builder = $this->where('type_operation_id', $typeOperationId)
                         ->where('actif', 1)
                         ->where('montant_min <=', $montant)
                         ->groupStart()
                             ->where('montant_max >=', $montant)
                             ->orWhere('montant_max', null)
                         ->groupEnd()
                         ->orderBy('montant_min', 'DESC');

        return $builder->first();
    }

    /**
     * Liste des barèmes pour un type d'opération, triés par tranche.
     */
    public function getBaremesParType(int $typeOperationId)
    {
        return $this->where('type_operation_id', $typeOperationId)
                    ->orderBy('montant_min', 'ASC')
                    ->findAll();
    }
}