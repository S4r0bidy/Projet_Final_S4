<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurTelecomModel extends Model
{
    protected $table            = 'operateurs_telecom';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nom'];
    protected $useTimestamps    = false;
}