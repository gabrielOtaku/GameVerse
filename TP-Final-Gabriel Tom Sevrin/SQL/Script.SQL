CREATE DATABASE gameraddict
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_general_ci;

USE gameraddict;


CREATE TABLE produit (
    idProduit   TINYINT(6)      UNSIGNED    NOT NULL    AUTO_INCREMENT,
    
    nomProduit  VARCHAR(255)    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
    fournisseur VARCHAR(50)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    
    quantite    SMALLINT(6)     NULL,
    
    console     VARCHAR(25)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    
    prix        DECIMAL(4,2)    NULL,
    
    description VARCHAR(150)    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    
    age         TINYINT(3)      UNSIGNED    NULL,
    
    imageNom    VARCHAR(60)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,

    PRIMARY KEY (idProduit)

) ENGINE=InnoDB 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;



INSERT INTO produit 
(nomProduit, fournisseur, quantite, console, prix, description, age, imageNom) 
VALUES
('Cybernetic Dawn', 'Inconnu', 10, 'PC, PS5', 79.99, 'Un RPG futuriste en monde ouvert où les choix moraux décident de l\'avenir de la cité néon.', 7, '../Media/Jeux1.png'),

('Ancient Echoes', 'Inconnu', 10, 'Xbox Series X, PC', 59.99, 'Un jeu d\'aventure et de mystère au cœur de ruines mayas, avec des énigmes complexes et des combats épiques.', 7, '../Media/Jeux2.png'),

('Starfall Tactics', 'Inconnu', 10, 'PC', 44.50, 'Jeu de stratégie spatiale en temps réel. Construisez votre flotte de combat pour conquérir la galaxie.', 7, '../Media/Jeux3.png'),

('Mech Arena', 'Inconnu', 10, 'PS5, Xbox Series X', 69.99, 'Contrôlez de puissants Mechs de combat dans des arènes de destruction, un must pour les fans de robotique.', 7, '../Media/Jeux4.png'),

('Phantom Drift', 'Inconnu', 10, 'PC, Nintendo Switch', 39.99, 'Un jeu de course anti-gravité ultra-rapide avec des tracés néons et une physique exigeante.', 7, '../Media/Jeux5.png');



/*Table user*/ 

CREATE TABLE usager (
    idUsager    VARCHAR(40)  NOT NULL,
    password    VARCHAR(64)  NOT NULL, -- MODIFIÉ : Max 64 caractères
    prenom      VARCHAR(20)  NOT NULL,
    nom         VARCHAR(20)  NOT NULL,
    dateNaiss   DATE         NULL,
    telephone   VARCHAR(12)  NOT NULL,
    photoType   VARCHAR(10)  NULL,
    photoData   BLOB         NULL,
    PRIMARY KEY (idUsager)
) ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_general_ci;

INSERT INTO usager 
(idUsager, password, prenom, nom, dateNaiss, telephone, photoType, photoData) 
VALUES

('fbrassard@test.com', 'cegepstfe', 'Félix', 'Brassard', '1995-07-20', '5551234567', NULL, NULL),
('gabrielherve94@gmail.com', ' K&xU9oneQuPH3Z', ' Gabriel', 'Hervé', '1998-12-02', '5000000000', 'png', '[BLOB - 58,4 kio]'),
('admin@gameverse.com', 'admin', 'Amin', 'Admin', '1988-01-15', '5559876543', NULL, NULL),
('tom@zboub.com', 'ZboubSevran', 'Tom', 'Rujaco', '2005-09-15', '5551112222', NULL, NULL);