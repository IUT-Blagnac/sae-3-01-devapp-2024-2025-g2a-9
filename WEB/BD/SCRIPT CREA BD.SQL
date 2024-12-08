-- ----------------------------------------------------------------------------- 
--       DROP TABLES
-- ----------------------------------------------------------------------------- 

DROP TABLE IF EXISTS FAVORI;
DROP TABLE IF EXISTS DETAILCOMMANDE;
DROP TABLE IF EXISTS DETAILENSEMBLE;
DROP TABLE IF EXISTS DETAILPANIER;
DROP TABLE IF EXISTS AVIS;
DROP TABLE IF EXISTS COMMANDE;
DROP TABLE IF EXISTS PAIEMENT;
DROP TABLE IF EXISTS ENSEMBLE;
DROP TABLE IF EXISTS PRODUIT;
DROP TABLE IF EXISTS UTILISATEUR;
DROP TABLE IF EXISTS POINTRELAIS;
DROP TABLE IF EXISTS CATEGORIE;

-- ----------------------------------------------------------------------------- 
--       TABLE : CATEGORIE
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS CATEGORIE
(
   IDCATEGORIE INT NOT NULL AUTO_INCREMENT,
   IDCATEGPERE INT NULL,
   NOMCATEGORIE VARCHAR(30) NOT NULL,
   CONSTRAINT pk_categorie PRIMARY KEY (IDCATEGORIE),
   CONSTRAINT fk_categorie_categorie FOREIGN KEY (IDCATEGPERE) REFERENCES CATEGORIE (IDCATEGORIE)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : POINTRELAIS
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS POINTRELAIS
(
   IDPOINTRELAIS INT NOT NULL AUTO_INCREMENT,
   ADRESSEPOINTRELAIS VARCHAR(50) NOT NULL,
   CONSTRAINT pk_pointrelais PRIMARY KEY (IDPOINTRELAIS)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : UTILISATEUR
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS UTILISATEUR
(
   IDUTILISATEUR INT NOT NULL AUTO_INCREMENT,
   CIVILITE VARCHAR(3) NOT NULL,
   NOM VARCHAR(50) NOT NULL,
   PRENOM VARCHAR(50) NOT NULL,
   PAYS VARCHAR(30) NULL,
   DATEN DATE NULL,
   MAIL VARCHAR(50) NOT NULL,
   TELEPHONE VARCHAR(15) NULL,
   PASSWORD VARCHAR(30) NOT NULL,
   DROIT VARCHAR(6) NOT NULL,
   CONSTRAINT pk_utilisateur PRIMARY KEY (IDUTILISATEUR),
   CONSTRAINT ck_utilisateur_civilite CHECK (CIVILITE IN ('MR', 'MME')),
   CONSTRAINT ck_utilisateur_droit CHECK (DROIT IN ('ADMIN', 'CLIENT'))
);

-- ----------------------------------------------------------------------------- 
--       TABLE : PRODUIT
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS PRODUIT
(
   IDPRODUIT INT NOT NULL AUTO_INCREMENT,
   NOMPRODUIT VARCHAR(30) NOT NULL,
   PRIX DECIMAL(10, 2) NULL,
   DESCRIPTION VARCHAR(200) NULL,
   TAILLE VARCHAR(30) NULL,
   ENERGIE VARCHAR(30) NULL,
   STOCKDISPONIBLE INT NULL,
   STOCKLIMITE INT NULL,
   CONSTRAINT pk_produit PRIMARY KEY (IDPRODUIT),
   CONSTRAINT ck_produit_energie CHECK (ENERGIE IN ('Electrique', 'Diesel', 'Essence', 'Manuel')),
   CONSTRAINT ck_produit_stock_positive CHECK (STOCKDISPONIBLE > 0 AND STOCKLIMITE > 0)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : ENSEMBLE
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS ENSEMBLE
(
   IDENSEMBLE INT NOT NULL AUTO_INCREMENT,
   PRIXENSEMBLE DECIMAL(10, 2) NULL,
   CONSTRAINT pk_ensemble PRIMARY KEY (IDENSEMBLE)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : PAIEMENT
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS PAIEMENT
(
   IDPAIEMENT INT NOT NULL AUTO_INCREMENT,
   PRIXCOMMANDE DECIMAL(10, 2) NULL,
   MODEPAIEMENT VARCHAR(30) NULL,
   CONSTRAINT pk_paiement PRIMARY KEY (IDPAIEMENT),
   CONSTRAINT ck_paiement_mode CHECK (MODEPAIEMENT IN ('Carte Bancaire', 'PayPal'))
);

-- ----------------------------------------------------------------------------- 
--       TABLE : COMMANDE
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS COMMANDE
(
   IDCOMMANDE INT NOT NULL AUTO_INCREMENT,
   IDUTILISATEUR INT NOT NULL,
   IDPAIEMENT INT NOT NULL,
   DATECOMMANDE DATE NULL,
   MODELIVRAISON VARCHAR(30) NULL,
   ADRESSELIVRAISON VARCHAR(50) NULL,
   IDPOINTRELAIS INT NULL,
   CONSTRAINT pk_commande PRIMARY KEY (IDCOMMANDE),
   CONSTRAINT fk_commande_utilisateur FOREIGN KEY (IDUTILISATEUR) REFERENCES UTILISATEUR (IDUTILISATEUR),
   CONSTRAINT fk_commande_paiement FOREIGN KEY (IDPAIEMENT) REFERENCES PAIEMENT (IDPAIEMENT),
   CONSTRAINT fk_livraison_pointrelais FOREIGN KEY (IDPOINTRELAIS) REFERENCES POINTRELAIS (IDPOINTRELAIS),
   CONSTRAINT ck_livraison_modelivraison CHECK (MODELIVRAISON IN ('Domicile', 'Relais'))
);

-- ----------------------------------------------------------------------------- 
--       TABLE : AVIS
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS AVIS
(
   IDAVIS INT NOT NULL AUTO_INCREMENT,
   IDPRODUIT INT NOT NULL,
   IDUTILISATEUR INT NOT NULL,
   NOTE INT NULL,
   COMMENTAIRE VARCHAR(200) NULL,
   DATEAVIS DATE NULL,
   CONSTRAINT pk_avis PRIMARY KEY (IDAVIS),
   CONSTRAINT fk_avis_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_avis_utilisateur FOREIGN KEY (IDUTILISATEUR) REFERENCES UTILISATEUR (IDUTILISATEUR),
   CONSTRAINT ck_avis_note_range CHECK (NOTE BETWEEN 0 AND 5)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILPANIER
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS DETAILPANIER
(
   IDPRODUIT INT NOT NULL,
   IDUTILISATEUR INT NOT NULL,
   QUANTITEPANIER INT NOT NULL,
   CONSTRAINT pk_detailpanier PRIMARY KEY (IDPRODUIT, IDUTILISATEUR),
   CONSTRAINT fk_detailpanier_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_detailpanier_utilisateur FOREIGN KEY (IDUTILISATEUR) REFERENCES UTILISATEUR (IDUTILISATEUR),
   CONSTRAINT ck_detailpanier_quantite CHECK (QUANTITEPANIER > 0)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILENSEMBLE
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS DETAILENSEMBLE
(
   IDENSEMBLE INT NOT NULL,
   IDPRODUIT INT NOT NULL,
   QUANTITEENSEMBLE INT NOT NULL,
   CONSTRAINT pk_detailensemble PRIMARY KEY (IDENSEMBLE, IDPRODUIT),
   CONSTRAINT fk_detailensemble_ensemble FOREIGN KEY (IDENSEMBLE) REFERENCES ENSEMBLE (IDENSEMBLE),
   CONSTRAINT fk_detailensemble_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT ck_detailensemble_quantite CHECK (QUANTITEENSEMBLE > 0)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILCOMMANDE
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS DETAILCOMMANDE
(
   IDPRODUIT INT NOT NULL,
   IDCOMMANDE INT NOT NULL,
   QUANTITECOMMANDEE INT NOT NULL,
   CONSTRAINT pk_detailcommande PRIMARY KEY (IDPRODUIT, IDCOMMANDE),
   CONSTRAINT fk_detailcommande_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_detailcommande_commande FOREIGN KEY (IDCOMMANDE) REFERENCES COMMANDE (IDCOMMANDE),
   CONSTRAINT ck_detailcommande_quantite CHECK (QUANTITECOMMANDEE > 0)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : FAVORI
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS FAVORI
(
   IDPRODUIT INT NOT NULL,
   IDUTILISATEUR INT NOT NULL,
   DATEFAVORI DATE NULL,
   CONSTRAINT pk_favori PRIMARY KEY (IDPRODUIT, IDUTILISATEUR),
   CONSTRAINT fk_favori_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_favori_utilisateur FOREIGN KEY (IDUTILISATEUR) REFERENCES UTILISATEUR (IDUTILISATEUR)
);
