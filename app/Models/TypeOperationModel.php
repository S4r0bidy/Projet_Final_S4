<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'types_operation';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['code', 'libelle'];
    protected $useTimestamps    = false;

    // Codes utilisés dans toute l'application
    public const DEPOT     = 'DEPOT';
    public const RETRAIT   = 'RETRAIT';
    public const TRANSFERT = 'TRANSFERT';

    public function getByCode(string $code): ?array
    {
        return $this->where('code', $code)->first();
    }
}