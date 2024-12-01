-- ----------------------------------------------------------------------------- 
--       TABLE : CATEGORIE
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS CATEGORIE
(
   IDCATEGORIE INT NOT NULL,
   IDCATEGPERE INT NULL,
   NOMCATEGORIE VARCHAR(30) NULL,
   DESCRIPTION VARCHAR(200) NULL,
   CONSTRAINT pk_categorie PRIMARY KEY (IDCATEGORIE),
   CONSTRAINT fk_categorie_categorie FOREIGN KEY (IDCATEGPERE) REFERENCES CATEGORIE (IDCATEGORIE)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : POINTRELAIS
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS POINTRELAIS
(
   IDPOINTRELAIS INT NOT NULL,
   ADRESSEPOINTRELAIS VARCHAR(50) NULL,
   CONSTRAINT pk_pointrelais PRIMARY KEY (IDPOINTRELAIS)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : CLIENT
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS CLIENT
(
   IDCLIENT INT NOT NULL,
   CIVILITE VARCHAR(3) NULL,
   NOM VARCHAR(50) NULL,
   PRENOM VARCHAR(50) NULL,
   PAYS VARCHAR(30) NULL,
   VILLE VARCHAR(30) NULL,
   CODEPOSTAL CHAR(5) NULL,
   ADRESSE VARCHAR(50) NULL,
   DATEN DATE NULL,
   MAIL VARCHAR(50) NULL,
   TELEPHONE VARCHAR(15) NULL,
   LOGIN VARCHAR(30) NULL,
   PASSWORD VARCHAR(30) NULL,
   CONSTRAINT pk_client PRIMARY KEY (IDCLIENT),
   CONSTRAINT ck_client_civilite CHECK (CIVILITE IN ('MR', 'MME'))
);

-- ----------------------------------------------------------------------------- 
--       TABLE : PRODUIT
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS PRODUIT
(
   IDPRODUIT INT NOT NULL,
   NOMPRODUIT VARCHAR(30) NULL,
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
   IDENSEMBLE INT NOT NULL,
   PRIXENSEMBLE DECIMAL(10, 2) NULL,
   CONSTRAINT pk_ensemble PRIMARY KEY (IDENSEMBLE)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : PAIEMENT
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS PAIEMENT
(
   IDPAIEMENT INT NOT NULL,
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
   IDCOMMANDE INT NOT NULL,
   IDCLIENT INT NOT NULL,
   IDPAIEMENT INT NOT NULL,
   DATECOMMANDE DATE NULL,
   MODELIVRAISON VARCHAR(30) NULL,
   ADRESSELIVRAISON VARCHAR(50) NULL,
   IDPOINTRELAIS INT NULL,
   CONSTRAINT pk_commande PRIMARY KEY (IDCOMMANDE),
   CONSTRAINT fk_commande_client FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT),
   CONSTRAINT fk_commande_paiement FOREIGN KEY (IDPAIEMENT) REFERENCES PAIEMENT (IDPAIEMENT),
   CONSTRAINT fk_livraison_pointrelais FOREIGN KEY (IDPOINTRELAIS) REFERENCES POINTRELAIS (IDPOINTRELAIS),
   CONSTRAINT ck_livraison_modelivraison CHECK (MODELIVRAISON IN ('Domicile', 'Relais'))
);

-- ----------------------------------------------------------------------------- 
--       TABLE : PANIER
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS PANIER
(
   IDPANIER INT NOT NULL,
   IDCLIENT INT NOT NULL,
   DATEPANIER DATE NULL,
   CONSTRAINT pk_panier PRIMARY KEY (IDPANIER),
   CONSTRAINT fk_panier_client FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : AVIS
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS AVIS
(
   IDAVIS INT NOT NULL,
   IDPRODUIT INT NOT NULL,
   IDCLIENT INT NOT NULL,
   NOTE INT NULL,
   COMMENTAIRE VARCHAR(200) NULL,
   DATE DATE NULL,
   CONSTRAINT pk_avis PRIMARY KEY (IDAVIS),
   CONSTRAINT fk_avis_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_avis_client FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT),
   CONSTRAINT ck_avis_note_range CHECK (NOTE BETWEEN 0 AND 5)
);

-- ----------------------------------------------------------------------------- 
--       TABLE : DETAILPANIER
-- ----------------------------------------------------------------------------- 

CREATE TABLE IF NOT EXISTS DETAILPANIER
(
   IDPRODUIT INT NOT NULL,
   IDPANIER INT NOT NULL,
   QUANTITEPANIER INT NOT NULL,
   CONSTRAINT pk_detailpanier PRIMARY KEY (IDPRODUIT, IDPANIER),
   CONSTRAINT fk_detailpanier_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_detailpanier_panier FOREIGN KEY (IDPANIER) REFERENCES PANIER (IDPANIER),
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
   IDCLIENT INT NOT NULL,
   DATEFAVORI DATE NULL,
   CONSTRAINT pk_favori PRIMARY KEY (IDPRODUIT, IDCLIENT),
   CONSTRAINT fk_favori_produit FOREIGN KEY (IDPRODUIT) REFERENCES PRODUIT (IDPRODUIT),
   CONSTRAINT fk_favori_client FOREIGN KEY (IDCLIENT) REFERENCES CLIENT (IDCLIENT)
);
