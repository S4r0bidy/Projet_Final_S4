<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'reference', 'type_operation_id', 'compte_source_id', 'compte_destination_id',
        'bareme_id', 'montant', 'frais', 'montant_total', 'statut',
    ];
    protected $useTimestamps    = false; // date_operation gérée par DEFAULT CURRENT_TIMESTAMP

    public const STATUT_REUSSI = 'REUSSI';
    public const STATUT_ECHEC  = 'ECHEC';

    /**
     * Génère une référence unique pour une opération.
     * Format: OP-YYYYMMDD-XXXXXX
     */
    public function genererReference(): string
    {
        do {
            $reference = 'OP-' . date('Ymd') . '-' . str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while ($this->where('reference', $reference)->first());

        return $reference;
    }

    /**
     * Historique des opérations liées à un compte (source ou destination),
     * via la vue v_historique_operations.
     */
    public function getHistoriquePourCompte(int $compteId)
    {
        return $this->db->table('operations o')
            ->select('o.*, t.libelle as type_operation,
                      src.numero_telephone as numero_source,
                      dst.numero_telephone as numero_destination')
            ->join('types_operation t', 't.id = o.type_operation_id')
            ->join('comptes_clients src', 'src.id = o.compte_source_id', 'left')
            ->join('comptes_clients dst', 'dst.id = o.compte_destination_id', 'left')
            ->groupStart()
                ->where('o.compte_source_id', $compteId)
                ->orWhere('o.compte_destination_id', $compteId)
            ->groupEnd()
            ->orderBy('o.date_operation', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Historique complet (vue admin), via la vue SQL v_historique_operations.
     */
    public function getHistoriqueComplet()
    {
        return $this->db->table('v_historique_operations')->get()->getResultArray();
    }

    /**
     * Situation des gains de l'opérateur (frais perçus), via la vue v_gains_frais.
     */
    public function getGainsFrais()
    {
        return $this->db->table('v_gains_frais')->get()->getResultArray();
    }
}