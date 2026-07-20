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
    protected $useTimestamps    = false;

    public const STATUT_REUSSI = 'REUSSI';
    public const STATUT_ECHEC  = 'ECHEC';

    /**
     * Generates a unique reference for an operation
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
     * Historique des operations liees a un compte (source ou destination)
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
     * Historique complet via la vue v_historique_operations
     */
    public function getHistoriqueComplet()
    {
        return $this->db->table('v_historique_operations')->get()->getResultArray();
    }

    /**
     * Gains de l'operateur (frais percus) via la vue v_gains_frais
     */
    public function getGainsFrais()
    {
        return $this->db->table('v_gains_frais')->get()->getResultArray();
    }

    /**
     * Gains detailles avec separation meme operateur / autre operateur
     */
    public function getGainsFraisDetail()
    {
        return $this->db->table('v_gains_frais_detail')->get()->getResultArray();
    }

    /**
     * Situation des montants a envoyer a chaque operateur
     */
    public function getSituationOperateurs()
    {
        return $this->db->table('v_situation_operateurs')->get()->getResultArray();
    }

    /**
     * Statistiques des transferts par operateur de destination
     */
    public function getStatistiquesParOperateur()
    {
        return $this->db->query("
            SELECT
                ot.nom AS operateur,
                COUNT(o.id) AS nb_transferts,
                COALESCE(SUM(o.montant), 0) AS montant_total,
                COALESCE(SUM(o.frais), 0) AS total_frais,
                COUNT(DISTINCT o.compte_source_id) AS nb_expediteurs,
                COUNT(DISTINCT o.compte_destination_id) AS nb_destinataires
            FROM operations o
            JOIN types_operation t ON t.id = o.type_operation_id AND t.code = 'TRANSFERT' AND o.statut = 'REUSSI'
            JOIN comptes_clients dst ON dst.id = o.compte_destination_id
            JOIN prefixes p ON p.id = dst.prefixe_id
            JOIN operateurs_telecom ot ON ot.id = p.operateur_telecom_id
            GROUP BY ot.nom
            ORDER BY ot.nom ASC
        ")->getResultArray();
    }

    /**
     * Total des commissions supplementaires percues par operateur
     */
    public function getTotalCommissionsSupplementaires()
    {
        return $this->db->query("
            SELECT
                ot.nom AS operateur,
                COALESCE(SUM(o.frais), 0) AS total_commissions
            FROM operations o
            JOIN types_operation t ON t.id = o.type_operation_id AND t.code = 'TRANSFERT' AND o.statut = 'REUSSI'
            JOIN comptes_clients dst ON dst.id = o.compte_destination_id
            JOIN prefixes p ON p.id = dst.prefixe_id
            JOIN operateurs_telecom ot ON ot.id = p.operateur_telecom_id
            GROUP BY ot.nom
            ORDER BY ot.nom ASC
        ")->getResultArray();
    }

    /**
     * Historique complet avec operateurs source/destination et type de transfert
     */
    public function getHistoriqueCompletAvecOperateurs()
    {
        return $this->db->table('v_historique_operations')
            ->orderBy('date_operation', 'DESC')
            ->get()
            ->getResultArray();
    }
}
