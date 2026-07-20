<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministrateurModel extends Model
{
    protected $table            = 'administrateurs';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password', 'nom'];
    protected $useTimestamps    = false;

    public function verifierIdentifiants(string $username, string $password): ?array
    {
        $admin = $this->where('username', $username)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return null;
    }
}