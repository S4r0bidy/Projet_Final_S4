-- Utilisation :
--   sqlite3 database.db < base.sql
-- =====================================================================

PRAGMA foreign_keys = ON;

-- ---------------------------------------------------------------------
-- Nettoyage (utile en cas de ré-exécution du script)
-- ---------------------------------------------------------------------
DROP VIEW IF EXISTS v_historique_operations;
DROP VIEW IF EXISTS v_situation_comptes;
DROP VIEW IF EXISTS v_situation_operateurs;
DROP VIEW IF EXISTS v_gains_frais;
DROP VIEW IF EXISTS v_gains_frais_detail;
DROP VIEW IF EXISTS v_statistiques_transferts_internes;
DROP VIEW IF EXISTS v_statistiques_transferts_externes;

DROP TABLE IF EXISTS commission_operateur;
DROP TABLE IF EXISTS operations;
DROP TABLE IF EXISTS baremes_frais;
DROP TABLE IF EXISTS comptes_clients;
DROP TABLE IF EXISTS types_operation;
DROP TABLE IF EXISTS prefixes;
DROP TABLE IF EXISTS operateurs_telecom;
DROP TABLE IF EXISTS administrateurs;

-- =====================================================================
-- TABLES
-- =====================================================================

-- Administrateurs / opérateur
CREATE TABLE administrateurs (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    username      TEXT NOT NULL UNIQUE,
    password      TEXT NOT NULL,              -- hash (password_hash)
    nom           TEXT,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Opérateurs telecom
CREATE TABLE operateurs_telecom (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    nom           TEXT NOT NULL UNIQUE,       -- ex 'Orange', 'Airtel', 'Yas'
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Commission supplementaire pour les transferts vers les autres operateurs
CREATE TABLE commission_operateur (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    operateur_destination_id INTEGER NOT NULL,
    pourcentage             REAL NOT NULL,
    actif                   INTEGER NOT NULL DEFAULT 1,
    created_at              DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operateur_destination_id) REFERENCES operateurs_telecom(id) ON DELETE CASCADE
);

CREATE TABLE prefixes (
    id                    INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe               TEXT NOT NULL UNIQUE,   -- ex '034'
    operateur_telecom_id  INTEGER NOT NULL,
    actif                 INTEGER NOT NULL DEFAULT 1,
    created_at            DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operateur_telecom_id) REFERENCES operateurs_telecom(id) ON DELETE CASCADE
);

-- Types d'operation : depot, retrait, transfert
CREATE TABLE types_operation (
    id            INTEGER PRIMARY KEY AUTOINCREMENT,
    code          TEXT NOT NULL UNIQUE,       -- DEPOT | RETRAIT | TRANSFERT
    libelle       TEXT NOT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bareme de frais par tranche de montant, par type d'operation
CREATE TABLE baremes_frais (
    id                  INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id   INTEGER NOT NULL,
    montant_min         REAL NOT NULL,
    montant_max         REAL,
    frais               REAL NOT NULL,        -- montant du frais pour cette tranche
    actif               INTEGER NOT NULL DEFAULT 1,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id) ON DELETE CASCADE
);

-- Comptes clients (login automatique par numero)
CREATE TABLE comptes_clients (
    id                    INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone      TEXT NOT NULL UNIQUE,
    prefixe_id            INTEGER,             -- deduit du numero a la creation du compte
    solde                 REAL NOT NULL DEFAULT 0,
    statut                TEXT NOT NULL DEFAULT 'ACTIF', -- ACTIF | BLOQUE
    date_creation         DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prefixe_id) REFERENCES prefixes(id)
);

ALTER TABLE comptes_clients ADD COLUMN solde_epargne REAL DEFAULT 0;
ALTER TABLE comptes_clients ADD COLUMN pourcentage_epargne REAL DEFAULT 0;

-- Historique de toutes les operations (depot / retrait / transfert)
CREATE TABLE operations (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    reference               TEXT NOT NULL UNIQUE,
    type_operation_id       INTEGER NOT NULL,
    compte_source_id        INTEGER,          -- NULL pour un depot (argent externe)
    compte_destination_id   INTEGER,          -- NULL pour un retrait
    bareme_id               INTEGER,          -- tranche de frais appliquee
    montant                 REAL NOT NULL,
    frais                   REAL NOT NULL DEFAULT 0,
    montant_total           REAL NOT NULL,    -- montant +/- frais selon le sens
    statut                  TEXT NOT NULL DEFAULT 'REUSSI', -- REUSSI | ECHEC
    date_operation           DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operation(id),
    FOREIGN KEY (compte_source_id) REFERENCES comptes_clients(id),
    FOREIGN KEY (compte_destination_id) REFERENCES comptes_clients(id),
    FOREIGN KEY (bareme_id) REFERENCES baremes_frais(id)
);

CREATE INDEX idx_operations_source ON operations(compte_source_id);
CREATE INDEX idx_operations_destination ON operations(compte_destination_id);
CREATE INDEX idx_operations_type ON operations(type_operation_id);
CREATE INDEX idx_baremes_type ON baremes_frais(type_operation_id);

-- =====================================================================
-- VUES
-- =====================================================================

-- Gains de l'operateur : total des frais percus, par type d'operation
CREATE VIEW v_gains_frais AS
SELECT
    t.id                AS type_operation_id,
    t.libelle           AS type_operation,
    COUNT(o.id)          AS nb_operations,
    COALESCE(SUM(o.frais), 0) AS total_frais
FROM types_operation t
LEFT JOIN operations o
    ON o.type_operation_id = t.id
    AND o.statut = 'REUSSI'
GROUP BY t.id, t.libelle;

-- Gains detailes : separation meme operateur / autre operateur
CREATE VIEW v_gains_frais_detail AS
SELECT
    t.id                AS type_operation_id,
    t.libelle           AS type_operation,
    CASE
        WHEN t.code = 'TRANSFERT' AND src.prefixe_id IS NOT NULL AND dst.prefixe_id IS NOT NULL
             AND src.prefixe_id != dst.prefixe_id THEN 'AUTRE_OPERATEUR'
        WHEN t.code = 'TRANSFERT' THEN 'MEME_OPERATEUR'
        ELSE 'SANS_OBJET'
    END                 AS categorie_operateur,
    COUNT(o.id)          AS nb_operations,
    COALESCE(SUM(o.frais), 0) AS total_frais
FROM types_operation t
LEFT JOIN operations o
    ON o.type_operation_id = t.id
    AND o.statut = 'REUSSI'
LEFT JOIN comptes_clients src ON src.id = o.compte_source_id
LEFT JOIN comptes_clients dst ON dst.id = o.compte_destination_id
GROUP BY t.id, t.libelle, categorie_operateur;

-- Situation des montants a envoyer a chaque operateur (transferts)
CREATE VIEW v_situation_operateurs AS
SELECT
    ot.id               AS operateur_id,
    ot.nom              AS operateur_nom,
    COUNT(o.id)         AS nb_transferts,
    COALESCE(SUM(o.montant), 0) AS montant_total,
    MAX(o.date_operation) AS derniere_operation
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id AND t.code = 'TRANSFERT' AND o.statut = 'REUSSI'
JOIN comptes_clients dst ON dst.id = o.compte_destination_id
JOIN prefixes p ON p.id = dst.prefixe_id
JOIN operateurs_telecom ot ON ot.id = p.operateur_telecom_id
GROUP BY ot.id, ot.nom
ORDER BY ot.nom ASC;

-- Situation des comptes clients
CREATE VIEW v_situation_comptes AS
SELECT
    c.id,
    c.numero_telephone,
    p.prefixe             AS prefixe,
    ot.nom                AS operateur_telecom,
    c.solde,
    c.statut,
    c.date_creation,
    (SELECT COUNT(*) FROM operations o
        WHERE o.compte_source_id = c.id OR o.compte_destination_id = c.id) AS nb_operations
FROM comptes_clients c
LEFT JOIN prefixes p           ON p.id = c.prefixe_id
LEFT JOIN operateurs_telecom ot ON ot.id = p.operateur_telecom_id;

-- Historique lisible des operations (avec libelles + operateurs)
CREATE VIEW v_historique_operations AS
SELECT
    o.id,
    o.reference,
    t.libelle              AS type_operation,
    t.code                 AS type_operation_code,
    src.numero_telephone   AS numero_source,
    dst.numero_telephone   AS numero_destination,
    ot_src.nom             AS operateur_source,
    ot_dst.nom             AS operateur_destination,
    CASE WHEN t.code = 'TRANSFERT' AND ot_src.id IS NOT NULL AND ot_dst.id IS NOT NULL
              AND ot_src.id = ot_dst.id THEN 'INTERNE'
         WHEN t.code = 'TRANSFERT' THEN 'EXTERNE'
         ELSE NULL
    END                    AS type_transfert,
    o.montant,
    o.frais,
    o.montant_total,
    o.statut,
    o.date_operation
FROM operations o
JOIN types_operation t   ON t.id = o.type_operation_id
LEFT JOIN comptes_clients src ON src.id = o.compte_source_id
LEFT JOIN comptes_clients dst ON dst.id = o.compte_destination_id
LEFT JOIN prefixes p_src ON p_src.id = src.prefixe_id
LEFT JOIN prefixes p_dst ON p_dst.id = dst.prefixe_id
LEFT JOIN operateurs_telecom ot_src ON ot_src.id = p_src.operateur_telecom_id
LEFT JOIN operateurs_telecom ot_dst ON ot_dst.id = p_dst.operateur_telecom_id
ORDER BY o.date_operation DESC;

-- Statistiques des transferts internes (meme operateur)
CREATE VIEW v_statistiques_transferts_internes AS
SELECT
    COALESCE(SUM(o.montant), 0)          AS montant_total,
    COUNT(o.id)                          AS nb_transferts,
    COALESCE(SUM(o.frais), 0)            AS total_frais,
    COUNT(DISTINCT o.compte_source_id)   AS nb_expediteurs,
    COUNT(DISTINCT o.compte_destination_id) AS nb_destinataires
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id AND t.code = 'TRANSFERT' AND o.statut = 'REUSSI'
JOIN comptes_clients src ON src.id = o.compte_source_id
JOIN comptes_clients dst ON dst.id = o.compte_destination_id
WHERE src.prefixe_id = dst.prefixe_id;

-- Statistiques des transferts externes (autre operateur)
CREATE VIEW v_statistiques_transferts_externes AS
SELECT
    COALESCE(SUM(o.montant), 0)          AS montant_total,
    COUNT(o.id)                          AS nb_transferts,
    COALESCE(SUM(o.frais), 0)            AS total_frais,
    COUNT(DISTINCT o.compte_destination_id) AS nb_destinataires
FROM operations o
JOIN types_operation t ON t.id = o.type_operation_id AND t.code = 'TRANSFERT' AND o.statut = 'REUSSI'
JOIN comptes_clients src ON src.id = o.compte_source_id
JOIN comptes_clients dst ON dst.id = o.compte_destination_id
WHERE src.prefixe_id != dst.prefixe_id;

-- =====================================================================
-- DONNEES DE BASE / TEST
-- =====================================================================

-- Administrateur par defaut (mot de passe : "admin123")
INSERT INTO administrateurs (username, password, nom) VALUES
('admin', '$2y$10$vY5imkZiZRxIq5U6fwhUwu0p4GvFWKIoJFUy.jJGf/TstsFbx9T8a', 'Administrateur Principal');

-- Operateurs telecom reels (Madagascar)
INSERT INTO operateurs_telecom (nom) VALUES
('Orange'),
('Airtel'),
('Yas');

-- Orange = 032, 037 | Airtel = 033 | Yas (Telma) = 034, 038
INSERT INTO prefixes (prefixe, operateur_telecom_id, actif) VALUES
('032', (SELECT id FROM operateurs_telecom WHERE nom = 'Orange'),  1),
('037', (SELECT id FROM operateurs_telecom WHERE nom = 'Orange'),  1),
('033', (SELECT id FROM operateurs_telecom WHERE nom = 'Airtel'),  1),
('034', (SELECT id FROM operateurs_telecom WHERE nom = 'Yas'),     1),
('038', (SELECT id FROM operateurs_telecom WHERE nom = 'Yas'),     1);

-- Types d'operation
INSERT INTO types_operation (code, libelle) VALUES
('DEPOT', 'Depot'),
('RETRAIT', 'Retrait'),
('TRANSFERT', 'Transfert');

-- Bareme de frais - RETRAIT (type_operation_id = 2)
INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(2,     100,    5000,    100),
(2,    5001,   20000,    300),
(2,   20001,   50000,    700),
(2,   50001,  100000,   1200),
(2,  100001,     NULL,   2000);

-- Bareme de frais - TRANSFERT (type_operation_id = 3)
INSERT INTO baremes_frais (type_operation_id, montant_min, montant_max, frais) VALUES
(3,     100,    5000,     50),
(3,    5001,   20000,    200),
(3,   20001,   50000,    500),
(3,   50001,  100000,   1000),
(3,  100001,     NULL,   1800);

-- Comptes clients de test (prefixe_id deduit des 3 premiers chiffres du numero)
INSERT INTO comptes_clients (numero_telephone, prefixe_id, solde) VALUES
('0331234567', (SELECT id FROM prefixes WHERE prefixe = '033'), 15000),
('0341112233', (SELECT id FROM prefixes WHERE prefixe = '034'), 0),
('0379876543', (SELECT id FROM prefixes WHERE prefixe = '037'), 50000);

-- Quelques operations de test
INSERT INTO operations (reference, type_operation_id, compte_source_id, compte_destination_id, bareme_id, montant, frais, montant_total, statut) VALUES
('OP-20260701-000001', 1, NULL, 1, NULL, 15000, 0, 15000, 'REUSSI'),
('OP-20260705-000002', 3, 1, 3, 8, 5000, 200, 5200, 'REUSSI'),
('OP-20260710-000003', 2, 3, NULL, 3, 20000, 700, 20700, 'REUSSI');
