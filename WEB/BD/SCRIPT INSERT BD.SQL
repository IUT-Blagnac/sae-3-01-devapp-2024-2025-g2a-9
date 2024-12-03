-- ----------------------------------------------------------------------------- 
--       AUTO_INCREMENT RESET
-- ----------------------------------------------------------------------------- 

ALTER TABLE CATEGORIE AUTO_INCREMENT = 1
ALTER TABLE POINTRELAIS AUTO_INCREMENT = 1
ALTER TABLE CLIENT AUTO_INCREMENT = 1
ALTER TABLE PRODUIT AUTO_INCREMENT = 1
ALTER TABLE ENSEMBLE AUTO_INCREMENT = 1
ALTER TABLE PAIEMENT AUTO_INCREMENT = 1
ALTER TABLE COMMANDE AUTO_INCREMENT = 1
ALTER TABLE AVIS AUTO_INCREMENT = 1


-- ----------------------------------------------------------------------------- 
--       TABLE : CATEGORIE
-- ----------------------------------------------------------------------------- 

-- Insertion des catégories parentes
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateaux de transport', NULL);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateaux de plaisance', NULL);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateaux de loisirs', NULL);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateaux de pêche', NULL);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateaux non motorisés', NULL);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateaux spécialisés', NULL);

-- Insertion des sous-catégories pour "Bateaux de transport"
-- Nous devons d'abord récupérer l'ID de "Bateaux de transport"
SET @parent_transport = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateaux de transport');
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Porte-conteneurs', @parent_transport);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Porte-avion', @parent_transport);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Tanker', @parent_transport);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Ferry', @parent_transport);

-- Insertion des sous-catégories pour "Bateaux de plaisance"
SET @parent_plaisance = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateaux de plaisance');
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Yacht', @parent_plaisance);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Voilier', @parent_plaisance);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Catamaran', @parent_plaisance);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateau de croisière', @parent_plaisance);

-- Insertion des sous-catégories pour "Bateaux de loisirs"
SET @parent_loisirs = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateaux de loisirs');
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Jetski', @parent_loisirs);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateau sans permis', @parent_loisirs);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateau cabine', @parent_loisirs);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Péniche', @parent_loisirs);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Hydroglisseur', @parent_loisirs);

-- Insertion des sous-catégories pour "Bateaux de pêche"
SET @parent_peche = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateaux de pêche');
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Chalutier', @parent_peche);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bolincheur', @parent_peche);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Caseyeur', @parent_peche);

-- Insertion des sous-catégories pour "Bateaux non motorisés"
SET @parent_non_motorises = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateaux non motorisés');
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Canoë', @parent_non_motorises);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Pédalo', @parent_non_motorises);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Kayak', @parent_non_motorises);

-- Insertion des sous-catégories pour "Bateaux spécialisés"
SET @parent_speciaux = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateaux spécialisés');
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateau offshore hydravion', @parent_speciaux);
INSERT INTO CATEGORIE (NOMCATEGORIE, IDCATEGPERE) VALUES ('Bateau en kit', @parent_speciaux);


-- ----------------------------------------------------------------------------- 
--       TABLE : POINTRELAIS
-- ----------------------------------------------------------------------------- 

-- Insertion de 10 points relais près de la côte en France
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Port de la Pointe Rouge, Marseille');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Plage de Plaisance, Le Grau-du-Roi');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Port de Saint-Tropez, Saint-Tropez');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Promenade des Anglais, Nice');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Port de la Rochelle, La Rochelle');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Plage de Cabourg, Cabourg');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Port de Honfleur, Honfleur');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Plage de la Côte des Basques, Biarritz');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Port de Cannes, Cannes');
INSERT INTO POINTRELAIS (ADRESSEPOINTRELAIS) VALUES ('Plage de Deauville, Deauville');


-- ----------------------------------------------------------------------------- 
--       TABLE : CLIENT
-- ----------------------------------------------------------------------------- 

-- Insertion de 10 clients
INSERT INTO CLIENT (CIVILITE, NOM, PRENOM, PAYS, VILLE, CODEPOSTAL, ADRESSE, DATEN, MAIL, TELEPHONE, LOGIN, PASSWORD) 
VALUES 
('MR', 'Durand', 'Pierre', 'France', 'Paris', '75001', '1 Rue de la République', '1985-06-15', 'pierre.durand@mail.com', '0123456789', 'pierreD85', 'pass1234'),
('MME', 'Lemoine', 'Marie', 'France', 'Lyon', '69002', '12 Rue des Cèdres', '1990-03-22', 'marie.lemoine@mail.com', '0987654321', 'marieL90', 'mdp5678'),
('MR', 'Martin', 'François', 'France', 'Marseille', '13001', '35 Boulevard Saint-Marcel', '1982-11-05', 'francois.martin@mail.com', '0612345678', 'francoisM82', 'motdepasse01'),
('MME', 'Bouvier', 'Isabelle', 'Belgique', 'Bruxelles', '99', '45 Avenue Louise', '1975-07-30', 'isabelle.bouvier@mail.com', '0323456789', 'isabelleB75', 'password123'),
('MR', 'Dupont', 'Jean', 'France', 'Toulouse', '31000', '89 Rue Victor Hugo', '1995-01-12', 'jean.dupont@mail.com', '0789654321', 'jeanD95', 'securePass456'),
('MME', 'Blanchard', 'Claire', 'France', 'Nice', '06000', '23 Promenade des Anglais', '1988-09-18', 'claire.blanchard@mail.com', '0612348901', 'claireB88', 'clairePass789'),
('MR', 'Leclerc', 'Julien', 'Canada', 'Montréal', '99', '222 Rue Sainte-Catherine', '1983-02-20', 'julien.leclerc@mail.com', '5141234567', 'julienL83', 'motdepasse456'),
('MME', 'Garnier', 'Sophie', 'France', 'Bordeaux', '33000', '76 Rue de la Liberté', '1992-12-03', 'sophie.garnier@mail.com', '0551234567', 'sophieG92', 'sophiePass123'),
('MR', 'Lemoine', 'Jacques', 'France', 'Nantes', '44000', '56 Rue de la Loire', '1978-05-25', 'jacques.lemoine@mail.com', '0298765432', 'jacquesL78', 'jacco1234'),
('MME', 'Robert', 'Cécile', 'France', 'Lille', '59000', '11 Rue de la République', '1986-08-17', 'cecile.robert@mail.com', '0321122334', 'cecileR86', 'cecilePass321');


-- ----------------------------------------------------------------------------- 
--       TABLE : PRODUIT
-- ----------------------------------------------------------------------------- 

-- Bateaux de transport

-- Porte-conteneurs
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Porte-conteneurs GE-2000', 1500000.00, 'Porte-conteneurs de grande capacité, idéal pour les longues distances.', '200m', 'Diesel', 5, 10),
('Porte-conteneurs EcoTrans', 2000000.00, 'Porte-conteneurs à faible consommation énergie, conçu pour le transport écologique.', '210m', 'Diesel', 3, 7);

-- Porte-avion
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Porte-avion Viper', 5000000.00, 'Porte-avion militaire haut de gamme pour aviation navale.', '300m', 'Essence', 2, 5),
('Porte-avion Falcon', 6000000.00, 'Porte-avion ultra-moderne avec une capacité de stockage élevée pour les avions de chasse.', '310m', 'Essence', 4, 8);

-- Tanker
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Tanker Alpha', 2000000.00, 'Tanker pour transport de pétrole, équipé avec un système de sécurité avancé.', '250m', 'Diesel', 6, 12),
('Tanker Omega', 2500000.00, 'Tanker à grande capacité pour transport de produits chimiques.', '270m', 'Diesel', 3, 6);

-- Ferry
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Ferry Croisière', 1000000.00, 'Ferry rapide pour croisières en mer, avec un grand confort.', '180m', 'Electrique', 10, 20),
('Ferry Maritime', 1200000.00, 'Ferry pour transport de passagers et véhicules sur de longues distances.', '200m', 'Electrique', 8, 15);

-- Bateaux de plaisance

-- Yacht
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Yacht Prestige 50', 3500000.00, 'Yacht de luxe avec tous les équipements modernes pour un confort maximal.', '50m', 'Diesel', 4, 8),
('Yacht Royal 60', 4000000.00, 'Yacht de luxe avec plusieurs suites et une piscine intérieure.', '60m', 'Diesel', 2, 5);

-- Voilier
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Voilier Classic', 500000.00, 'Voilier élégant avec un design classique et un excellent rapport qualité/prix.', '12m', 'Manuel', 10, 15),
('Voilier Oceanic', 600000.00, 'Voilier robuste pour les expéditions océaniques, conçu pour les navigateurs expérimentés.', '15m', 'Manuel', 5, 10);

-- Catamaran
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Catamaran Lux', 900000.00, 'Catamaran de luxe pour des croisières privées avec un confort exceptionnel.', '18m', 'Electrique', 7, 14),
('Catamaran Speed', 800000.00, 'Catamaran rapide pour des courses maritimes excitantes.', '16m', 'Electrique', 6, 12);

-- Bateau de croisière
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Croisière Horizon', 1500000.00, 'Bateau de croisière avec des cabines luxueuses et des équipements modernes.', '250m', 'Diesel', 3, 6),
('Croisière Pacific', 1800000.00, 'Bateau de croisière offrant des croisières autour du monde, avec des restaurants et casinos.', '260m', 'Diesel', 4, 8);

-- Bateaux de loisirs

-- Jetski
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Jetski Turbo', 15000.00, 'Jetski haute performance pour des sensations fortes en mer.', '4m', 'Essence', 15, 30),
('Jetski Speedster', 12000.00, 'Jetski rapide et économique, parfait pour des balades sur l\'eau.', '3.5m', 'Essence', 10, 20);

-- Bateau sans permis
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Bateau Flottant', 5000.00, 'Petit bateau sans permis, idéal pour les promenades en famille.', '5m', 'Electrique', 25, 50),
('Bateau Détente', 4500.00, 'Bateau sans permis, conçu pour une navigation tranquille sur les lacs et rivières.', '4.5m', 'Electrique', 30, 60);

-- Bateau cabine
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Bateau Cabin Cruiser', 30000.00, 'Bateau cabine moderne avec un grand espace pour les croisières.', '10m', 'Diesel', 8, 16),
('Bateau Cabin Explorer', 35000.00, 'Bateau cabine avec plusieurs cabines, idéal pour des aventures longues.', '12m', 'Diesel', 5, 10);

-- Péniche
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Péniche Riviera', 25000.00, 'Péniche de luxe avec toutes les commodités modernes pour vivre sur l\'eau.', '15m', 'Diesel', 4, 8),
('Péniche Classique', 22000.00, 'Péniche confortable pour des séjours prolongés sur les canaux.', '14m', 'Diesel', 6, 12);

-- Hydroglisseur
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Hydroglisseur Racing', 35000.00, 'Hydroglisseur performant pour des courses sur eau à grande vitesse.', '7m', 'Essence', 3, 6),
('Hydroglisseur Splash', 30000.00, 'Hydroglisseur rapide pour des sorties en mer et sensations extrêmes.', '6m', 'Essence', 4, 8);

-- Bateaux de pêche

-- Chalutier
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Chalutier Marine', 500000.00, 'Chalutier robuste conçu pour une pêche en haute mer.', '25m', 'Diesel', 5, 10),
('Chalutier Atlantic', 550000.00, 'Chalutier performant pour la pêche commerciale en mer.', '28m', 'Diesel', 4, 8);

-- Bolincheur
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Bolincheur Excell', 400000.00, 'Bolincheur conçu pour la pêche de poissons pélagiques.', '18m', 'Diesel', 6, 12),
('Bolincheur Elite', 450000.00, 'Bolincheur de haute performance avec des équipements avancés pour la pêche.', '20m', 'Diesel', 3, 6);

-- Caseyeur
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Caseyeur Pacific', 300000.00, 'Caseyeur robuste pour la pêche au large, conçu pour résister aux conditions extrêmes.', '22m', 'Diesel', 5, 10),
('Caseyeur Titan', 350000.00, 'Caseyeur puissant pour une pêche efficace dans les zones profondes.', '24m', 'Diesel', 4, 8);

-- Bateaux non motorisés

-- Canoë
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Canoë Classic', 300.00, 'Canoë léger pour des balades sur des rivières tranquilles.', '4m', 'Manuel', 30, 60),
('Canoë Sport', 350.00, 'Canoë conçu pour la performance en eaux vives.', '4.5m', 'Manuel', 25, 50);

-- Pédalo
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Pédalo Relax', 500.00, 'Pédalo confortable pour des moments de détente en famille.', '4m', 'Manuel', 20, 40),
('Pédalo Fun', 450.00, 'Pédalo ludique, idéal pour des sorties amusantes sur le lac.', '4m', 'Manuel', 25, 50);

-- Kayak
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Kayak Explorer', 700.00, 'Kayak léger et maniable pour des aventures en eau calme.', '3m', 'Manuel', 15, 30),
('Kayak Vitesse', 750.00, 'Kayak performant pour les sorties rapides sur rivière.', '3.5m', 'Manuel', 12, 25);

-- Bateaux spécialisés

-- Bateau offshore hydravion
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Bateau Offshore Jet', 1000000.00, 'Bateau offshore avec hydravion intégré pour des courses en mer.', '20m', 'Essence', 3, 6),
('Bateau Offshore Zéphyr', 1200000.00, 'Bateau spécialisé pour des courses et aventures en haute mer.', '22m', 'Essence', 2, 4);

-- Bateau en kit
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE)
VALUES
('Kit Bateau Nautica', 15000.00, 'Kit complet pour assembler votre propre bateau de plaisance.', '6m', 'Manuel', 10, 20),
('Kit Bateau Explorer', 18000.00, 'Kit de montage pour un bateau de loisirs, idéal pour les débutants.', '7m', 'Manuel', 8, 16);


-- ----------------------------------------------------------------------------- 
--       TABLE : ENSEMBLE
-- ----------------------------------------------------------------------------- 

-- Insertion des ensembles
-- Ensemble 1 : Ensemble de bateau de plaisance (Yacht et Voilier)
INSERT INTO ENSEMBLE (PRIXENSEMBLE) 
VALUES (5000000.00); -- Prix total de l'ensemble

-- Ensemble 2 : Ensemble de bateau de pêche (Chalutier et Bolincheur)
INSERT INTO ENSEMBLE (PRIXENSEMBLE) 
VALUES (850000.00); -- Prix total de l'ensemble

-- Ensemble 3 : Ensemble de bateau de loisirs (Jetski et Péniche)
INSERT INTO ENSEMBLE (PRIXENSEMBLE) 
VALUES (35000.00); -- Prix total de l'ensemble


-- ----------------------------------------------------------------------------- 
--       TABLE : ENSEMBLE
-- ----------------------------------------------------------------------------- 

INSERT INTO DETAILENSEMBLE (IDENSEMBLE, IDPRODUIT, QUANTITEENSEMBLE)
VALUES 
(1, 1, 1),  -- 1 Yacht Prestige 50
(1, 2, 2);  -- 2 Voilier Classic

INSERT INTO DETAILENSEMBLE (IDENSEMBLE, IDPRODUIT, QUANTITEENSEMBLE)
VALUES 
(2, 3, 1),  -- 1 Chalutier Marine
(2, 4, 2);  -- 2 Bolincheur Excell

INSERT INTO DETAILENSEMBLE (IDENSEMBLE, IDPRODUIT, QUANTITEENSEMBLE)
VALUES 
(3, 5, 1),  -- 1 Jetski Turbo
(3, 6, 3);  -- 3 Péniche Riviera


-- ----------------------------------------------------------------------------- 
--       TABLE : PAIEMENT
-- ----------------------------------------------------------------------------- 

-- Paiement 1 : Carte Bancaire
INSERT INTO PAIEMENT (PRIXCOMMANDE, MODEPAIEMENT)
VALUES (50000.00, 'Carte Bancaire');

-- Paiement 2 : PayPal
INSERT INTO PAIEMENT (PRIXCOMMANDE, MODEPAIEMENT)
VALUES (850000.00, 'PayPal');

-- Paiement 3 : Carte Bancaire
INSERT INTO PAIEMENT (PRIXCOMMANDE, MODEPAIEMENT)
VALUES (35000.00, 'Carte Bancaire');

-- Paiement 4 : PayPal
INSERT INTO PAIEMENT (PRIXCOMMANDE, MODEPAIEMENT)
VALUES (250000.00, 'PayPal');

-- Paiement 5 : Carte Bancaire
INSERT INTO PAIEMENT (PRIXCOMMANDE, MODEPAIEMENT)
VALUES (1200000.00, 'Carte Bancaire');


-- ----------------------------------------------------------------------------- 
--       TABLE : COMMANDE
-- ----------------------------------------------------------------------------- 

-- Commande 1 : Client 1, Paiement 1, Livraison à domicile
INSERT INTO COMMANDE (IDCLIENT, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON)
VALUES (1, 1, '2024-12-01', 'Domicile', '12 Rue de la Mer, 75001 Paris');

-- Commande 2 : Client 2, Paiement 2, Livraison en point relais (adresse livraison NULL)
INSERT INTO COMMANDE (IDCLIENT, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON, IDPOINTRELAIS)
VALUES (2, 2, '2024-12-02', 'Relais', NULL, 1); -- point relais avec ID 1

-- Commande 3 : Client 3, Paiement 3, Livraison à domicile
INSERT INTO COMMANDE (IDCLIENT, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON)
VALUES (3, 3, '2024-12-03', 'Domicile', '34 Avenue des Plages, 13001 Marseille');

-- Commande 4 : Client 4, Paiement 4, Livraison en point relais (adresse livraison NULL)
INSERT INTO COMMANDE (IDCLIENT, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON, IDPOINTRELAIS)
VALUES (4, 4, '2024-12-04', 'Relais', NULL, 2); -- point relais avec ID 2

-- Commande 5 : Client 5, Paiement 5, Livraison à domicile
INSERT INTO COMMANDE (IDCLIENT, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON)
VALUES (5, 5, '2024-12-05', 'Domicile', '56 Boulevard des Bateaux, 67000 Strasbourg');


-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILCOMMANDE
-- ----------------------------------------------------------------------------- 

-- Commande 1 : Produit 1 et 2
INSERT INTO DETAILCOMMANDE (IDPRODUIT, IDCOMMANDE, QUANTITECOMMANDEE)
VALUES 
(1, 1, 1),  -- 1 Yacht Prestige 50
(2, 1, 1);  -- 1 Voilier Classic

-- Commande 2 : Produit 3 et 4
INSERT INTO DETAILCOMMANDE (IDPRODUIT, IDCOMMANDE, QUANTITECOMMANDEE)
VALUES 
(3, 2, 1),  -- 1 Chalutier Marine
(4, 2, 2);  -- 2 Bolincheur Excell

-- Commande 3 : Produit 5 et 6
INSERT INTO DETAILCOMMANDE (IDPRODUIT, IDCOMMANDE, QUANTITECOMMANDEE)
VALUES 
(5, 3, 1),  -- 1 Jetski Turbo
(6, 3, 1);  -- 1 Péniche Riviera

-- Commande 4 : Produit 7 et 8
INSERT INTO DETAILCOMMANDE (IDPRODUIT, IDCOMMANDE, QUANTITECOMMANDEE)
VALUES 
(7, 4, 1),  -- 1 Bateau Offshore Jet
(8, 4, 1);  -- 1 Bateau Offshore Zéphyr

-- Commande 5 : Produit 9 et 10
INSERT INTO DETAILCOMMANDE (IDPRODUIT, IDCOMMANDE, QUANTITECOMMANDEE)
VALUES 
(9, 5, 2),  -- 2 Kit Bateau Nautica
(10, 5, 1);  -- 1 Kit Bateau Explorer


-- ----------------------------------------------------------------------------- 
--       TABLE : AVIS
-- ----------------------------------------------------------------------------- 

-- Insertion des avis

-- Avis 1 : Produit 1 (Yacht Prestige 50), Client 1, Note 5
INSERT INTO AVIS (IDPRODUIT, IDCLIENT, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (1, 1, 5, 'Excellent yacht, parfait pour des croisières de luxe. Très confortable et performant.', '2024-12-01');

-- Avis 2 : Produit 2 (Voilier Classic), Client 2, Note 4
INSERT INTO AVIS (IDPRODUIT, IDCLIENT, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (2, 2, 4, 'Très bon voilier pour des sorties en mer, mais pourrait être un peu plus rapide.', '2024-12-02');

-- Avis 3 : Produit 3 (Chalutier Marine), Client 3, Note 3
INSERT INTO AVIS (IDPRODUIT, IDCLIENT, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (3, 3, 3, 'Bon chalutier pour la pêche, mais manquant de quelques équipements modernes.', '2024-12-03');

-- Avis 4 : Produit 5 (Jetski Turbo), Client 4, Note 5
INSERT INTO AVIS (IDPRODUIT, IDCLIENT, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (5, 4, 5, 'Super jetski ! Très rapide et facile à manier, parfait pour des sensations fortes sur eau.', '2024-12-04');

-- Avis 5 : Produit 6 (Péniche Riviera), Client 5, Note 4
INSERT INTO AVIS (IDPRODUIT, IDCLIENT, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (6, 5, 4, 'Très agréable pour des vacances sur eau. Le confort est au rendez-vous, mais le moteur est un peu bruyant.', '2024-12-05');


-- ----------------------------------------------------------------------------- 
--       TABLE : FAVORI
-- ----------------------------------------------------------------------------- 

-- Insertion des articles favoris pour des clients

-- Article favori 1 : Produit 1 (Yacht Prestige 50) pour Client 1
INSERT INTO FAVORI (IDPRODUIT, IDCLIENT, DATEFAVORI)
VALUES (1, 1, '2024-12-01');

-- Article favori 2 : Produit 3 (Chalutier Marine) pour Client 2
INSERT INTO FAVORI (IDPRODUIT, IDCLIENT, DATEFAVORI)
VALUES (3, 2, '2024-12-02');

-- Article favori 3 : Produit 5 (Jetski Turbo) pour Client 3
INSERT INTO FAVORI (IDPRODUIT, IDCLIENT, DATEFAVORI)
VALUES (5, 3, '2024-12-03');

-- Article favori 4 : Produit 7 (Bateau Offshore Jet) pour Client 4
INSERT INTO FAVORI (IDPRODUIT, IDCLIENT, DATEFAVORI)
VALUES (7, 4, '2024-12-04');

-- Article favori 5 : Produit 9 (Kit Bateau Nautica) pour Client 5
INSERT INTO FAVORI (IDPRODUIT, IDCLIENT, DATEFAVORI)
VALUES (9, 5, '2024-12-05');


-- ----------------------------------------------------------------------------- 
--       TABLE : PANIER
-- ----------------------------------------------------------------------------- 

-- Panier 1 : Client 1, Date : 2024-12-01
INSERT INTO PANIER (IDCLIENT, DATEPANIER)
VALUES (1, '2024-12-01');

-- Panier 2 : Client 2, Date : 2024-12-02
INSERT INTO PANIER (IDCLIENT, DATEPANIER)
VALUES (2, '2024-12-02');

-- Panier 3 : Client 3, Date : 2024-12-03
INSERT INTO PANIER (IDCLIENT, DATEPANIER)
VALUES (3, '2024-12-03');

-- Panier 4 : Client 4, Date : 2024-12-04
INSERT INTO PANIER (IDCLIENT, DATEPANIER)
VALUES (4, '2024-12-04');

-- Panier 5 : Client 5, Date : 2024-12-05
INSERT INTO PANIER (IDCLIENT, DATEPANIER)
VALUES (5, '2024-12-05');


-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILPANIER
-- ----------------------------------------------------------------------------- 

-- Panier 1 : Détails pour Client 1 (Produit 1 et 3)
INSERT INTO DETAILPANIER (IDPRODUIT, IDPANIER, QUANTITEPANIER)
VALUES 
(1, 1, 2),  -- 2 Yacht Prestige 50
(3, 1, 1);  -- 1 Chalutier Marine

-- Panier 2 : Détails pour Client 2 (Produit 2 et 4)
INSERT INTO DETAILPANIER (IDPRODUIT, IDPANIER, QUANTITEPANIER)
VALUES 
(2, 2, 1),  -- 1 Voilier Classic
(4, 2, 2);  -- 2 Bolincheur Excell

-- Panier 3 : Détails pour Client 3 (Produit 5 et 6)
INSERT INTO DETAILPANIER (IDPRODUIT, IDPANIER, QUANTITEPANIER)
VALUES 
(5, 3, 1),  -- 1 Jetski Turbo
(6, 3, 3);  -- 3 Péniche Riviera

-- Panier 4 : Détails pour Client 4 (Produit 7 et 8)
INSERT INTO DETAILPANIER (IDPRODUIT, IDPANIER, QUANTITEPANIER)
VALUES 
(7, 4, 1),  -- 1 Bateau Offshore Jet
(8, 4, 2);  -- 2 Bateau Offshore Zéphyr

-- Panier 5 : Détails pour Client 5 (Produit 9 et 10)
INSERT INTO DETAILPANIER (IDPRODUIT, IDPANIER, QUANTITEPANIER)
VALUES 
(9, 5, 2),  -- 2 Kit Bateau Nautica
(10, 5, 1);  -- 1 Kit Bateau Explorer
