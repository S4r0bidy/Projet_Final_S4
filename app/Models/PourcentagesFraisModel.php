<?php

namespace App\Models;

use CodeIgniter\Model;

class PourcentagesFraisModel extends Model
{
    protected $table            = 'types_operation';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['pourcentage','type_operation_id','source_operation_id','destination_operation_id','actif','date_debut','date_fin'];
    protected $useTimestamps    = false;


    public function getPromotionActive(
                int $typeOperation,
        int $SourceOperateur,
        int $destinateurOperateur
    ){
        $today = date('Y-m-d');
        return $this
        ->where('codepourcentage', $pourcentage)
        ->where('type_operation_id', $type_operation_id)
        ->where('source_operation_id', $pourcentage)
        ->where('codepourcentage', $pourcentage)
        ->first();
    }
    
}