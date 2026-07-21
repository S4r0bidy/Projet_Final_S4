<?php

namespace App\Models;

use CodeIgniter\Model;
use RuntimeException;

class CompteClientModel extends Model
{
    protected $table            = 'comptes_clients';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['numero_telephone', 'prefixe_id', 'solde', 'statut', 'solde_epargne', 'pourcentage_epargne'];
    protected $useTimestamps    = false; // date_creation gérée par DEFAULT CURRENT_TIMESTAMP

    public const STATUT_ACTIF  = 'ACTIF';
    public const STATUT_BLOQUE = 'BLOQUE';

    /**
     * Récupère le compte lié à un numéro, ou le crée automatiquement s'il n'existe pas.
     *
     * @throws RuntimeException si le préfixe du numéro n'est pas actif/reconnu
     */
    public function trouverOuCreerCompte(string $numeroTelephone): array
    {
        $compte = $this->where('numero_telephone', $numeroTelephone)->first();
        if ($compte) {
            return $compte;
        }

        $prefixeModel = new PrefixeModel();
        $prefixe = $prefixeModel->getPrefixeActif($numeroTelephone);

        if (! $prefixe) {
            throw new RuntimeException("Préfixe inconnu ou inactif pour le numéro {$numeroTelephone}.");
        }

        $id = $this->insert([
            'numero_telephone' => $numeroTelephone,
            'prefixe_id'       => $prefixe['id'],
            'solde'            => 0,
            'statut'           => self::STATUT_ACTIF,
        ]);

        return $this->find($id);
    }

    /**
     * Liste des comptes avec infos préfixe / opérateur (vue admin)
     */
    public function getSituationComptes()
    {
        return $this->db->table('v_situation_comptes')->get()->getResultArray();
    }

    public function bloquer(int $id): bool
    {
        return $this->update($id, ['statut' => self::STATUT_BLOQUE]);
    }

    public function debloquer(int $id): bool
    {
        return $this->update($id, ['statut' => self::STATUT_ACTIF]);
    }

    public function estActif(array $compte): bool
    {
        return $compte['statut'] === self::STATUT_ACTIF;
    }

    /**
     * Crédite le solde d'un compte (dépôt, réception de transfert)
     */
    public function crediter(int $id, float $montant): bool
    {
        return $this->set('solde', 'solde + ' . (float) $montant, false)
                     ->where('id', $id)
                     ->update();
    }

    /**
     * Débite le solde d'un compte (retrait, envoi de transfert)
     * Vérifie que le solde est suffisant.
     *
     * @throws RuntimeException si le solde est insuffisant
     */
    public function debiter(int $id, float $montant): bool
    {
        $compte = $this->find($id);

        if (! $compte || $compte['solde'] < $montant) {
            throw new RuntimeException("Solde insuffisant pour effectuer cette opération.");
        }

        return $this->set('solde', 'solde - ' . (float) $montant, false)
                     ->where('id', $id)
                     ->update();
    }

    public function crediterEpargne(int $id, float $montant){
        $compte = $this->find($id);

        $this->update($id, [
            'solde_epargne' => 
                $compte['solde_epargne'] + montant
        ]);
    }

    public fonction modifierPourcentage(int $id, float $pourcentage){
        return $this->update($id, [
            'pourcentage_epargne'=>$pourcentage
        ]);
    }
}