CREATE DATABASE gameraddict
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_general_ci;

USE gameraddict;

-- Suppression des tables existantes pour une recréation propre
DROP TABLE IF EXISTS detail_commande;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS panier;
DROP TABLE IF EXISTS produit;
DROP TABLE IF EXISTS usager;


CREATE TABLE produit (
    idProduit   TINYINT(6)      UNSIGNED    NOT NULL    AUTO_INCREMENT,
    
    nomProduit  VARCHAR(255)    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL, 
    fournisseur VARCHAR(50)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    
    quantite    SMALLINT(6)     NULL,
    
    console     VARCHAR(25)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    
    prix        DECIMAL(5,2)    NULL,
    
    description VARCHAR(255)    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
    
    age         TINYINT(3)      UNSIGNED    NULL,
    
    imageNom    VARCHAR(60)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,

    typeProduit VARCHAR(20)     CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL, 
    
    PRIMARY KEY (idProduit)

) ENGINE=InnoDB 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;


/* --- JEUX (20 entrées au total) --- */
INSERT INTO produit 
(nomProduit, fournisseur, quantite, console, prix, description, age, imageNom, typeProduit) 
VALUES
('Cybernetic Dawn', 'Inconnu', 10, 'PC, PS5', 79.99, 'Un RPG futuriste en monde ouvert où les choix moraux décident de l\'avenir de la cité néon.', 18, 'Media/Jeux1.png', 'Jeu'),
('Ancient Echoes', 'Inconnu', 10, 'Xbox Series X, PC', 59.99, 'Un jeu d\'aventure et de mystère au cœur de ruines mayas, avec des énigmes complexes et des combats épiques.', 16, 'Media/Jeux2.png', 'Jeu'),
('Starfall Tactics', 'Inconnu', 10, 'PC', 44.50, 'Jeu de stratégie spatiale en temps réel. Construisez votre flotte de combat pour conquérir la galaxie.', 12, 'Media/Jeux3.png', 'Jeu'),
('Mech Arena', 'Inconnu', 10, 'PS5, Xbox Series X', 69.99, 'Contrôlez de puissants Mechs de combat dans des arènes de destruction, un must pour les fans de robotique.', 16, 'Media/Jeux4.png', 'Jeu'),
('Phantom Drift', 'Inconnu', 10, 'PC, Nintendo Switch', 39.99, 'Un jeu de course anti-gravité ultra-rapide avec des tracés néons et une physique exigeante.', 7, 'Media/Jeux5.png', 'Jeu'),
('Galactic Odyssey', 'Space Dev', 25, 'PS5', 89.99, 'L\'aventure spatiale ultime. Explorez des galaxies inconnues et négociez la paix avec des espèces extraterrestres.', 18, 'Media/Jeux6.png', 'Jeu'),
('Neon Assassin', 'CyberCorp', 15, 'PC, Xbox Series X', 74.99, 'Infiltration furtive dans un monde cyberpunk dystopique. Chaque ombre est votre alliée.', 18, 'Media/Jeux7.png', 'Jeu'),
('Fantasy Realm IV', 'Magic Soft', 30, 'Nintendo Switch', 59.99, 'Le dernier opus de la saga JRPG. Un monde coloré rempli de magie et de quêtes épiques.', 12, 'Media/Jeux8.png', 'Jeu'),
('Desert Storm 2077', 'War Games', 5, 'PC', 49.99, 'Simulateur militaire tactique dans un futur ravagé par la guerre climatique.', 18, 'Media/Jeux9.png', 'Jeu'),
('Aqua Builders', 'IndieDev', 40, 'PC, PS5, Xbox Series X', 29.99, 'Construisez et gérez votre propre cité sous-marine.', 7, 'Media/Jeux10.png', 'Jeu'),
('Eternal Quest', 'RolePlay Inc.', 22, 'PS5, Xbox Series X', 69.99, 'Un RPG classique avec plus de 100 heures de contenu et une histoire profonde.', 16, 'Media/Jeux11.png', 'Jeu'),
('Pixel Runner', 'Retro Games', 50, 'Nintendo Switch', 19.99, 'Jeu de plateforme 2D au style rétro, très difficile et addictif.', 7, 'Media/Jeux12.png', 'Jeu'),
('Zombie Outbreak', 'Horror Masters', 8, 'PC, PS5', 54.99, 'Survival horror coopératif. Utilisez des ressources limitées pour survivre à l\'apocalypse.', 18, 'Media/Jeux13.png', 'Jeu'),
('Formula Zero', 'Speed Demons', 18, 'Xbox Series X', 79.99, 'Jeu de course futuriste sans gravité avec des vitesses folles.', 7, 'Media/Jeux14.png', 'Jeu'),
('Dungeon Master', 'Fantasy Dev', 12, 'PC', 34.99, 'Jeu de stratégie et de construction de donjons.', 12, 'Media/Jeux15.png', 'Jeu'),
('Future Shock', 'Sci-Fi Studio', 14, 'PS5', 79.99, 'FPS rapide et nerveux se déroulant sur une station spatiale corrompue.', 18, 'Media/Jeux16.png', 'Jeu'),
('Mythic Legends', 'Indie Studio', 28, 'Nintendo Switch', 49.99, 'Action-RPG isométrique avec des héros inspirés de la mythologie.', 16, 'Media/Jeux17.png', 'Jeu'),
('Tokyo Drifter', 'Racing Kings', 17, 'PC, Xbox Series X', 64.99, 'Jeu de drift nocturne dans les rues animées de Tokyo.', 16, 'Media/Jeux18.png', 'Jeu'),
('Robo Wars', 'Tech Gen', 9, 'PS5', 59.99, 'Jeu de combat de robots en temps réel, entièrement destructible.', 12, 'Media/Jeux19.png', 'Jeu'),
('The Last Citadel', 'Epic Studios', 11, 'PC', 69.99, 'Jeu de survie en milieu hostile post-apocalyptique.', 18, 'Media/Jeux20.png', 'Jeu');

/* --- EXCLUSIVITÉS (5 entrées) --- */
INSERT INTO produit 
(nomProduit, fournisseur, quantite, console, prix, description, age, imageNom, typeProduit) 
VALUES
('Cyber Runner 2', 'Exclu Dev', 500, 'PC, PS5', 99.99, 'Exclusivité GameVerse: Le jeu de l\'année. Vitesse, néon et haute technologie.', 18, 'Media/Exclu1.png', 'Exclusivité'),
('Star Command', 'Exclu Soft', 500, 'PS5, Xbox Series X', 89.99, 'Exclusivité: Prenez le commandement de la flotte stellaire et sauvez l\'humanité.', 12, 'Media/Exclu2.png', 'Exclusivité'),
('Arctic Survivor', 'Exclu Studios', 500, 'PC', 79.99, 'Exclusivité: Un survival hardcore dans les toundras gelées. Mises à jour hebdomadaires garanties.', 16, 'Media/Exclu3.png', 'Exclusivité'),
('Retro Mania 3D', 'Exclu Arcade', 500, 'Nintendo Switch', 49.99, 'Exclusivité: L\'arcade rétro-futuriste en 3D. Des heures de fun garanties.', 7, 'Media/Exclu4.png', 'Exclusivité'),
('Deep Sea Mining', 'Exclu Tech', 500, 'PC, Xbox Series X', 59.99, 'Exclusivité: Gérez votre entreprise d\'extraction minière sous-marine. Simulation économique complexe.', 12, 'Media/Exclu5.png', 'Exclusivité');

/* --- CONSOLES (5 entrées) --- */
INSERT INTO produit 
(nomProduit, fournisseur, quantite, console, prix, description, age, imageNom, typeProduit) 
VALUES
('PlayStation 5 (Standard)', 'Sony', 50, 'PS5', 699.99, 'Console de salon Next-Gen avec lecteur Blu-ray 4K. Vitesse de chargement ultra-rapide.', 3, '../Media/ConsolePS5.png', 'Console'),
('Xbox Series X', 'Microsoft', 45, 'Xbox Series X', 649.99, 'La console la plus puissante du marché. 12 TFLOPS de puissance graphique.', 3, '../Media/ConsoleXbox.png', 'Console'),
('Nintendo Switch OLED', 'Nintendo', 60, 'Nintendo Switch', 469.99, 'Modèle avec écran OLED vibrant et support ajustable large.', 3, '../Media/ConsoleSwitch.jpg', 'Console'),
('PC Gaming Custom (R-4090)', 'GameVerse', 10, 'PC', 4999.99, 'Le PC de jeu ultime, configuré par GameVerse avec une carte graphique RTX 4090.', 3, '../Media/ConsolePC.png', 'Console'),
('Meta Quest 3 (VR)', 'Meta', 30, 'PC', 649.99, 'Casque de réalité virtuelle autonome pour une immersion totale.', 3, '../Media/ConsoleVR.png', 'Console');


/* --- Tables Usager, Panier, Commande --- */

CREATE TABLE usager (
    idUsager    VARCHAR(40)  NOT NULL,
    password    VARCHAR(64)  NOT NULL, 
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
('gabrielherve94@gmail.com', 'K&xU9oneQuPH', ' Gabriel', 'Hervé', '1998-12-02', '5000000000', 'png', '[BLOB - 58,4 kio]'),
('gabrielherve94250@gmail.com', 'QvLnKs8MvZQ0', ' bibou', 'zboub', '2002-12-02', '5000000000', 'png', '[BLOB - 58,4 kio]'),
('admin@gameverse.com', 'admin', 'Amin', 'Admin', '1988-01-15', '5559876543', NULL, NULL),
('tom@zboub.com', 'ZboubSevran', 'Tom', 'Rujaco', '2005-09-15', '5551112222', NULL, NULL);


CREATE TABLE IF NOT EXISTS panier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUsager    VARCHAR(40)  NOT NULL, 
    id_produit  TINYINT(6)   UNSIGNED NOT NULL, 
    quantite INT DEFAULT 1,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (idUsager) REFERENCES usager(idUsager) ON DELETE CASCADE,
    FOREIGN KEY (id_produit) REFERENCES produit(idProduit) ON DELETE CASCADE
) ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUsager    VARCHAR(40)  NOT NULL, 
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    montant_total DECIMAL(10,2) NOT NULL,
    mode_paiement VARCHAR(50),
    adresse_livraison TEXT,
    type_livraison VARCHAR(50),
    
    FOREIGN KEY (idUsager) REFERENCES usager(idUsager)
) ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS detail_commande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_produit TINYINT(6) UNSIGNED NOT NULL, 
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    
    FOREIGN KEY (id_commande) REFERENCES commande(id),
    FOREIGN KEY (id_produit) REFERENCES produit(idProduit)
) ENGINE = InnoDB
DEFAULT CHARSET = utf8mb4
COLLATE = utf8mb4_general_ci;