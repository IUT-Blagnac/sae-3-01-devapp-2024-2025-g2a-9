-- ----------------------------------------------------------------------------- 
--       AUTO_INCREMENT RESET
-- ----------------------------------------------------------------------------- 

ALTER TABLE CATEGORIE AUTO_INCREMENT = 1;
ALTER TABLE POINTRELAIS AUTO_INCREMENT = 1;
ALTER TABLE UTILISATEUR AUTO_INCREMENT = 1;
ALTER TABLE PRODUIT AUTO_INCREMENT = 1;
ALTER TABLE ENSEMBLE AUTO_INCREMENT = 1;
ALTER TABLE PAIEMENT AUTO_INCREMENT = 1;
ALTER TABLE COMMANDE AUTO_INCREMENT = 1;
ALTER TABLE AVIS AUTO_INCREMENT = 1;


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
--       TABLE : UTILISATEUR
-- ----------------------------------------------------------------------------- 

-- Insertion de 10 UTILISATEURs
INSERT INTO UTILISATEUR (CIVILITE, NOM, PRENOM, PAYS, DATEN, MAIL, TELEPHONE, ADRESSE, PASSWORD, DROIT) 
VALUES 
('MR', 'Durand', 'Pierre', 'France', '1985-06-15', 'pierre.durand@mail.com', '0123456789', '12, rue de Paris, 75001, Paris', 'pass1234', 'CLIENT'),
('MME', 'Lemoine', 'Marie', 'France', '1990-03-22', 'marie.lemoine@mail.com', '0987654321', '25, avenue Victor Hugo, 75016, Paris', 'mdp5678', 'CLIENT'),
('MR', 'Martin', 'François', 'France', '1982-11-05', 'francois.martin@mail.com', '0612345678', '8, impasse des Lilas, 69007, Lyon', 'motdepasse01', 'CLIENT'),
('MME', 'Bouvier', 'Isabelle', 'Belgique', '1975-07-30', 'isabelle.bouvier@mail.com', '0323456789', '10, rue des Flandres, 1000, Bruxelles', 'password123', 'CLIENT'),
('MR', 'Dupont', 'Jean', 'France', '1995-01-12', 'jean.dupont@mail.com', '0789654321', '3, boulevard Haussmann, 75009, Paris', 'securePass456', 'CLIENT'),
('MME', 'Blanchard', 'Claire', 'France', '1988-09-18', 'claire.blanchard@mail.com', '0612348901', '15, rue des Cerisiers, 31000, Toulouse', 'clairePass789', 'CLIENT'),
('MR', 'Leclerc', 'Julien', 'Canada', '1983-02-20', 'julien.leclerc@mail.com', '5141234567', '98, rue Sainte-Catherine, H2X 2J7, Montréal', 'motdepasse456', 'CLIENT'),
('MME', 'Garnier', 'Sophie', 'France', '1992-12-03', 'sophie.garnier@mail.com', '0551234567', '42, chemin des Roches, 33000, Bordeaux', 'sophiePass123', 'CLIENT'),
('MR', 'Lemoine', 'Jacques', 'France', '1978-05-25', 'jacques.lemoine@mail.com', '0298765432', '17, rue de Bretagne, 35000, Rennes', 'jacco1234', 'CLIENT'),
('MME', 'Robert', 'Cécile', 'France', '1986-08-17', 'cecile.robert@mail.com', '0321122334', '21, place de la République, 59000, Lille', 'cecilePass321', 'CLIENT');

-- Insertion de 2 UTILISATEURs ADMIN
INSERT INTO UTILISATEUR (CIVILITE, NOM, PRENOM, MAIL, PASSWORD, DROIT) 
VALUES 
('MR', 'Nonne', 'Laurent', 'laurent.nonne@mail.com', 'pass1234', 'ADMIN'),
('MR', 'Teste', 'Olivier', 'olivier.teste@mail.com', 'mdp5678', 'ADMIN');


-- ----------------------------------------------------------------------------- 
--       TABLE : PRODUIT
-- ----------------------------------------------------------------------------- 

-- Porte-conteneurs
SET @id_porte_conteneurs = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Porte-conteneurs');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Porte-conteneurs GE-2000', 1500000.00, 'Porte-conteneurs de grande capacité, idéal pour les longues distances.', '200m', 'Diesel', 5, 10, @id_porte_conteneurs),
('Porte-conteneurs EcoTrans', 2000000.00, 'Porte-conteneurs à faible consommation énergie, conçu pour le transport écologique.', '210m', 'Diesel', 3, 7, @id_porte_conteneurs);

-- Porte-avion
SET @id_porte_avion = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Porte-avion');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Porte-avion Viper', 5000000.00, 'Porte-avion militaire haut de gamme pour aviation navale.', '300m', 'Essence', 2, 5, @id_porte_avion),
('Porte-avion Falcon', 6000000.00, 'Porte-avion ultra-moderne avec une capacité de stockage élevée pour les avions de chasse.', '310m', 'Essence', 4, 8, @id_porte_avion);

-- Tanker
SET @id_tanker = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Tanker');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Tanker Alpha', 2000000.00, 'Tanker pour transport de pétrole, équipé avec un système de sécurité avancé.', '250m', 'Diesel', 6, 12, @id_tanker),
('Tanker Omega', 2500000.00, 'Tanker à grande capacité pour transport de produits chimiques.', '270m', 'Diesel', 3, 6, @id_tanker);

-- Ferry
SET @id_ferry = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Ferry');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Ferry Croisière', 1000000.00, 'Ferry rapide pour croisières en mer, avec un grand confort.', '180m', 'Electrique', 10, 20, @id_ferry),
('Ferry Maritime', 1200000.00, 'Ferry pour transport de passagers et véhicules sur de longues distances.', '200m', 'Electrique', 8, 15, @id_ferry);

-- Yacht
SET @id_yacht = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Yacht');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Yacht Prestige 50', 3500000.00, 'Yacht de luxe avec tous les équipements modernes pour un confort maximal.', '50m', 'Diesel', 4, 8, @id_yacht),
('Yacht Royal 60', 4000000.00, 'Yacht de luxe avec plusieurs suites et une piscine intérieure.', '60m', 'Diesel', 2, 5, @id_yacht);

-- Voilier
SET @id_voilier = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Voilier');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Voilier Classic', 500000.00, 'Voilier élégant avec un design classique et un excellent rapport qualité/prix.', '12m', 'Manuel', 10, 15, @id_voilier),
('Voilier Oceanic', 600000.00, 'Voilier robuste pour les expéditions océaniques, conçu pour les navigateurs expérimentés.', '15m', 'Manuel', 5, 10, @id_voilier);

-- Catamaran
SET @id_catamaran = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Catamaran');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Catamaran Lux', 900000.00, 'Catamaran de luxe pour des croisières privées avec un confort exceptionnel.', '18m', 'Electrique', 7, 14, @id_catamaran),
('Catamaran Speed', 800000.00, 'Catamaran rapide pour des courses maritimes excitantes.', '16m', 'Electrique', 6, 12, @id_catamaran);

-- Bateau de croisière
SET @id_bateau_croisiere = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateau de croisière');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Croisière Horizon', 1500000.00, 'Bateau de croisière avec des cabines luxueuses et des équipements modernes.', '250m', 'Diesel', 3, 6, @id_bateau_croisiere),
('Croisière Pacific', 1800000.00, 'Bateau de croisière offrant des croisières autour du monde, avec des restaurants et casinos.', '260m', 'Diesel', 4, 8, @id_bateau_croisiere);

-- Jetski
SET @id_jetski = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Jetski');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Jetski Turbo', 15000.00, 'Jetski haute performance pour des sensations fortes en mer.', '4m', 'Essence', 15, 30, @id_jetski),
('Jetski Speedster', 12000.00, 'Jetski rapide et économique, parfait pour des balades sur l\'eau.', '3.5m', 'Essence', 10, 20, @id_jetski);

-- Bateau sans permis
SET @id_bateau_sans_permis = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateau sans permis');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Bateau Flottant', 5000.00, 'Petit bateau sans permis, idéal pour les promenades en famille.', '5m', 'Electrique', 25, 50, @id_bateau_sans_permis),
('Bateau Détente', 4500.00, 'Bateau sans permis, conçu pour une navigation tranquille sur les lacs et rivières.', '4.5m', 'Electrique', 30, 60, @id_bateau_sans_permis);

-- Bateau cabine
SET @id_bateau_cabine = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Bateau cabine');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Bateau Cabin Cruiser', 30000.00, 'Bateau cabine moderne avec un grand espace pour les croisières.', '10m', 'Diesel', 8, 16, @id_bateau_cabine),
('Bateau Cabin Explorer', 35000.00, 'Bateau cabine avec plusieurs cabines, idéal pour des aventures longues.', '12m', 'Diesel', 5, 10, @id_bateau_cabine);

-- Péniche
SET @id_peniche = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Péniche');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Péniche Riviera', 25000.00, 'Péniche de luxe avec toutes les commodités modernes pour vivre sur l\'eau.', '15m', 'Diesel', 4, 8, @id_peniche),
('Péniche Classique', 22000.00, 'Péniche confortable pour des séjours prolongés sur les canaux.', '14m', 'Diesel', 6, 12, @id_peniche);

-- Hydroglisseur
SET @id_hydroglisseur = (SELECT IDCATEGORIE FROM CATEGORIE WHERE NOMCATEGORIE = 'Hydroglisseur');
INSERT INTO PRODUIT (NOMPRODUIT, PRIX, DESCRIPTION, TAILLE, ENERGIE, STOCKDISPONIBLE, STOCKLIMITE, IDCATEGORIE)
VALUES
('Hydroglisseur Lightning', 50000.00, 'Hydroglisseur de course, ultra rapide et conçu pour la compétition.', '8m', 'Essence', 3, 6, @id_hydroglisseur),
('Hydroglisseur Wave', 55000.00, 'Hydroglisseur pour des courses de vitesse en mer avec un design innovant.', '9m', 'Essence', 2, 4, @id_hydroglisseur);


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
--       TABLE : DETAILENSEMBLE
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

-- Commande 1 : UTILISATEUR 1, Paiement 1, Livraison à domicile
INSERT INTO COMMANDE (IDUTILISATEUR, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON)
VALUES (1, 1, '2024-12-01', 'Domicile', '12 Rue de la Mer, 75001 Paris');

-- Commande 2 : UTILISATEUR 2, Paiement 2, Livraison en point relais (adresse livraison NULL)
INSERT INTO COMMANDE (IDUTILISATEUR, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON, IDPOINTRELAIS)
VALUES (2, 2, '2024-12-02', 'Relais', NULL, 1); -- point relais avec ID 1

-- Commande 3 : UTILISATEUR 3, Paiement 3, Livraison à domicile
INSERT INTO COMMANDE (IDUTILISATEUR, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON)
VALUES (3, 3, '2024-12-03', 'Domicile', '34 Avenue des Plages, 13001 Marseille');

-- Commande 4 : UTILISATEUR 4, Paiement 4, Livraison en point relais (adresse livraison NULL)
INSERT INTO COMMANDE (IDUTILISATEUR, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON, IDPOINTRELAIS)
VALUES (4, 4, '2024-12-04', 'Relais', NULL, 2); -- point relais avec ID 2

-- Commande 5 : UTILISATEUR 5, Paiement 5, Livraison à domicile
INSERT INTO COMMANDE (IDUTILISATEUR, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON)
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

-- Avis 1 : Produit 1 (Yacht Prestige 50), UTILISATEUR 1, Note 5
INSERT INTO AVIS (IDPRODUIT, IDUTILISATEUR, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (1, 1, 5, 'Excellent yacht, parfait pour des croisières de luxe. Très confortable et performant.', '2024-12-01');

-- Avis 2 : Produit 2 (Voilier Classic), UTILISATEUR 2, Note 4
INSERT INTO AVIS (IDPRODUIT, IDUTILISATEUR, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (2, 2, 4, 'Très bon voilier pour des sorties en mer, mais pourrait être un peu plus rapide.', '2024-12-02');

-- Avis 3 : Produit 3 (Chalutier Marine), UTILISATEUR 3, Note 3
INSERT INTO AVIS (IDPRODUIT, IDUTILISATEUR, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (3, 3, 3, 'Bon chalutier pour la pêche, mais manquant de quelques équipements modernes.', '2024-12-03');

-- Avis 4 : Produit 5 (Jetski Turbo), UTILISATEUR 4, Note 5
INSERT INTO AVIS (IDPRODUIT, IDUTILISATEUR, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (5, 4, 5, 'Super jetski ! Très rapide et facile à manier, parfait pour des sensations fortes sur eau.', '2024-12-04');

-- Avis 5 : Produit 6 (Péniche Riviera), UTILISATEUR 5, Note 4
INSERT INTO AVIS (IDPRODUIT, IDUTILISATEUR, NOTE, COMMENTAIRE, DATEAVIS)
VALUES (6, 5, 4, 'Très agréable pour des vacances sur eau. Le confort est au rendez-vous, mais le moteur est un peu bruyant.', '2024-12-05');


-- ----------------------------------------------------------------------------- 
--       TABLE : FAVORI
-- ----------------------------------------------------------------------------- 

-- Insertion des articles favoris pour des UTILISATEURs

-- Article favori 1 : Produit 1 (Yacht Prestige 50) pour UTILISATEUR 1
INSERT INTO FAVORI (IDPRODUIT, IDUTILISATEUR, DATEFAVORI)
VALUES (1, 1, '2024-12-01');

-- Article favori 2 : Produit 3 (Chalutier Marine) pour UTILISATEUR 2
INSERT INTO FAVORI (IDPRODUIT, IDUTILISATEUR, DATEFAVORI)
VALUES (3, 2, '2024-12-02');

-- Article favori 3 : Produit 5 (Jetski Turbo) pour UTILISATEUR 3
INSERT INTO FAVORI (IDPRODUIT, IDUTILISATEUR, DATEFAVORI)
VALUES (5, 3, '2024-12-03');

-- Article favori 4 : Produit 7 (Bateau Offshore Jet) pour UTILISATEUR 4
INSERT INTO FAVORI (IDPRODUIT, IDUTILISATEUR, DATEFAVORI)
VALUES (7, 4, '2024-12-04');

-- Article favori 5 : Produit 9 (Kit Bateau Nautica) pour UTILISATEUR 5
INSERT INTO FAVORI (IDPRODUIT, IDUTILISATEUR, DATEFAVORI)
VALUES (9, 5, '2024-12-05');

-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILPANIER
-- ----------------------------------------------------------------------------- 

-- Panier 1 : Détails pour UTILISATEUR 1 (Produit 1 et 3)
INSERT INTO DETAILPANIER (IDPRODUIT, IDUTILISATEUR, QUANTITEPANIER)
VALUES 
(1, 1, 2),  -- 2 Yacht Prestige 50
(3, 1, 1);  -- 1 Chalutier Marine

-- Panier 2 : Détails pour UTILISATEUR 2 (Produit 2 et 4)
INSERT INTO DETAILPANIER (IDPRODUIT, IDUTILISATEUR, QUANTITEPANIER)
VALUES 
(2, 2, 1),  -- 1 Voilier Classic
(4, 2, 2);  -- 2 Bolincheur Excell

-- Panier 3 : Détails pour UTILISATEUR 3 (Produit 5 et 6)
INSERT INTO DETAILPANIER (IDPRODUIT, IDUTILISATEUR, QUANTITEPANIER)
VALUES 
(5, 3, 1),  -- 1 Jetski Turbo
(6, 3, 3);  -- 3 Péniche Riviera

-- Panier 4 : Détails pour UTILISATEUR 4 (Produit 7 et 8)
INSERT INTO DETAILPANIER (IDPRODUIT, IDUTILISATEUR, QUANTITEPANIER)
VALUES 
(7, 4, 1),  -- 1 Bateau Offshore Jet
(8, 4, 2);  -- 2 Bateau Offshore Zéphyr

-- Panier 5 : Détails pour UTILISATEUR 5 (Produit 9 et 10)
INSERT INTO DETAILPANIER (IDPRODUIT, IDUTILISATEUR, QUANTITEPANIER)
VALUES 
(9, 5, 2),  -- 2 Kit Bateau Nautica
(10, 5, 1);  -- 1 Kit Bateau Explorer
