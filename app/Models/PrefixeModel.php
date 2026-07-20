<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['prefixe', 'operateur_telecom_id', 'actif'];
    protected $useTimestamps    = false;

    /**
     * Liste des préfixes avec le nom de l'opérateur (pour affichage admin)
     */
    public function getAvecOperateur()
    {
        return $this->select('prefixes.*, operateurs_telecom.nom as operateur_nom')
                    ->join('operateurs_telecom', 'operateurs_telecom.id = prefixes.operateur_telecom_id')
                    ->orderBy('prefixes.prefixe', 'ASC')
                    ->findAll();
    }

    /**
     * Vérifie que le préfixe extrait du numéro correspond à un préfixe actif.
     */
    public function getPrefixeActif(string $numeroTelephone): ?array
    {
        $prefixeTexte = substr($numeroTelephone, 0, 3); // ex: '034'

        return $this->where('prefixe', $prefixeTexte)
                    ->where('actif', 1)
                    ->first();
    }

    /**
     * Récupère l'opérateur associé à un numéro de téléphone.
     * Retourne les infos de l'opérateur (id, nom) ou null si inconnu.
     */
    public function getOperateurByNumero(string $numeroTelephone): ?array
    {
        $prefixeTexte = substr($numeroTelephone, 0, 3);

        return $this->select('operateurs_telecom.*')
                    ->join('operateurs_telecom', 'operateurs_telecom.id = prefixes.operateur_telecom_id')
                    ->where('prefixe', $prefixeTexte)
                    ->where('actif', 1)
                    ->first();
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
