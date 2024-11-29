DROP DATABASE IF EXISTS MLR1;

CREATE DATABASE IF NOT EXISTS MLR1;
USE MLR1;
# -----------------------------------------------------------------------------
#       TABLE : PAIEMENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PAIEMENT
 (
   IDPAIEMENT CHAR(32) NOT NULL  ,
   IDCOMMANDE CHAR(32) NOT NULL  ,
   PRIXCOMMANDE CHAR(32) NULL  ,
   MODEPAIEMENT CHAR(32) NULL  
   , PRIMARY KEY (IDPAIEMENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE PAIEMENT
# -----------------------------------------------------------------------------


CREATE UNIQUE INDEX I_FK_PAIEMENT_COMMANDE
     ON PAIEMENT (IDCOMMANDE ASC);

# -----------------------------------------------------------------------------
#       TABLE : CATÉGORIE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CATÉGORIE
 (
   IDCATEGORIE CHAR(32) NOT NULL  ,
   IDCATEGORIE_HIÉRARCHISER CHAR(32) NULL  ,
   IDCATEGORIE_ASSO_2 CHAR(32) NULL  ,
   IDCATEGPERE CHAR(32) NULL  ,
   NOMCATEGORIE CHAR(32) NULL  ,
   DESCRIPTION CHAR(32) NULL  
   , PRIMARY KEY (IDCATEGORIE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE CATÉGORIE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_CATÉGORIE_CATÉGORIE
     ON CATÉGORIE (IDCATEGORIE_HIÉRARCHISER ASC);

CREATE  INDEX I_FK_CATÉGORIE_CATÉGORIE1
     ON CATÉGORIE (IDCATEGORIE_ASSO_2 ASC);

# -----------------------------------------------------------------------------
#       TABLE : ENSEMBLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ENSEMBLE
 (
   IDENSEMBLE CHAR(32) NOT NULL  ,
   PRIXENSEMBLE CHAR(32) NULL  
   , PRIMARY KEY (IDENSEMBLE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : COMMANDE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS COMMANDE
 (
   IDCOMMANDE CHAR(32) NOT NULL  ,
   IDCLIENT CHAR(32) NOT NULL  ,
   IDLIVRAISON CHAR(32) NOT NULL  ,
   IDPAIEMENT CHAR(32) NOT NULL  ,
   DATECOMMANDE CHAR(32) NULL  
   , PRIMARY KEY (IDCOMMANDE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE COMMANDE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_COMMANDE_CLIENT
     ON COMMANDE (IDCLIENT ASC);

CREATE UNIQUE INDEX I_FK_COMMANDE_LIVRAISON
     ON COMMANDE (IDLIVRAISON ASC);

CREATE UNIQUE INDEX I_FK_COMMANDE_PAIEMENT
     ON COMMANDE (IDPAIEMENT ASC);

# -----------------------------------------------------------------------------
#       TABLE : AVIS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS AVIS
 (
   IDAVIS CHAR(32) NOT NULL  ,
   IDPRODUIT CHAR(32) NOT NULL  ,
   IDCLIENT CHAR(32) NOT NULL  ,
   NOTE CHAR(32) NULL  ,
   COMMENTAIRE CHAR(32) NULL  ,
   DATE CHAR(32) NULL  
   , PRIMARY KEY (IDAVIS) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE AVIS
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_AVIS_PRODUIT
     ON AVIS (IDPRODUIT ASC);

CREATE  INDEX I_FK_AVIS_CLIENT
     ON AVIS (IDCLIENT ASC);

# -----------------------------------------------------------------------------
#       TABLE : LIVRAISON
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS LIVRAISON
 (
   IDLIVRAISON CHAR(32) NOT NULL  ,
   IDCOMMANDE CHAR(32) NOT NULL  ,
   IDPOINTRELAIS CHAR(32) NULL  ,
   ADRESSELIVRAISON CHAR(32) NULL  ,
   MODELIVRAISON CHAR(32) NULL  
   , PRIMARY KEY (IDLIVRAISON) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE LIVRAISON
# -----------------------------------------------------------------------------


CREATE UNIQUE INDEX I_FK_LIVRAISON_COMMANDE
     ON LIVRAISON (IDCOMMANDE ASC);

CREATE  INDEX I_FK_LIVRAISON_POINTRELAIS
     ON LIVRAISON (IDPOINTRELAIS ASC);

# -----------------------------------------------------------------------------
#       TABLE : PANIER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PANIER
 (
   IDPANIER CHAR(32) NOT NULL  ,
   IDCLIENT CHAR(32) NOT NULL  ,
   DATEPANIER CHAR(32) NULL  
   , PRIMARY KEY (IDPANIER) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE PANIER
# -----------------------------------------------------------------------------


CREATE UNIQUE INDEX I_FK_PANIER_CLIENT
     ON PANIER (IDCLIENT ASC);

# -----------------------------------------------------------------------------
#       TABLE : CLIENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CLIENT
 (
   IDCLIENT CHAR(32) NOT NULL  ,
   CIVILITE CHAR(32) NULL  ,
   NOM CHAR(32) NULL  ,
   PRENOM CHAR(32) NULL  ,
   PAYS CHAR(32) NULL  ,
   VILLE CHAR(32) NULL  ,
   CODEPOSTAL CHAR(32) NULL  ,
   ADRESSE CHAR(32) NULL  ,
   DATEN CHAR(32) NULL  ,
   MAIL CHAR(32) NULL  ,
   TELEPHONE CHAR(32) NULL  ,
   LOGIN CHAR(32) NULL  ,
   PASSWORD CHAR(32) NULL  
   , PRIMARY KEY (IDCLIENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : PRODUIT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS PRODUIT
 (
   IDPRODUIT CHAR(32) NOT NULL  ,
   NOMPRODUIT CHAR(32) NULL  ,
   PRIX CHAR(32) NULL  ,
   DESCRIPTION CHAR(32) NULL  ,
   TAILLE CHAR(32) NULL  ,
   ENERGIE CHAR(32) NULL  ,
   STOCKDISPONIBLE CHAR(32) NULL  ,
   STOCKLIMITE CHAR(32) NULL  
   , PRIMARY KEY (IDPRODUIT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : POINTRELAIS
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS POINTRELAIS
 (
   IDPOINTRELAIS CHAR(32) NOT NULL  ,
   ADRESSEPOINTRELAIS CHAR(32) NULL  
   , PRIMARY KEY (IDPOINTRELAIS) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : DETAILPANIER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DETAILPANIER
 (
   IDPRODUIT CHAR(32) NOT NULL  ,
   IDPANIER CHAR(32) NOT NULL  ,
   QUANTITÉPANIER CHAR(32) NOT NULL  
   , PRIMARY KEY (IDPRODUIT,IDPANIER) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE DETAILPANIER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_DETAILPANIER_PRODUIT
     ON DETAILPANIER (IDPRODUIT ASC);

CREATE  INDEX I_FK_DETAILPANIER_PANIER
     ON DETAILPANIER (IDPANIER ASC);

# -----------------------------------------------------------------------------
#       TABLE : CLASSER
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS CLASSER
 (
   IDCATEGORIE CHAR(32) NOT NULL  ,
   IDPRODUIT CHAR(32) NOT NULL  
   , PRIMARY KEY (IDCATEGORIE,IDPRODUIT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE CLASSER
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_CLASSER_CATÉGORIE
     ON CLASSER (IDCATEGORIE ASC);

CREATE  INDEX I_FK_CLASSER_PRODUIT
     ON CLASSER (IDPRODUIT ASC);

# -----------------------------------------------------------------------------
#       TABLE : ASSO_1
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS ASSO_1
 (
   IDCATEGORIE CHAR(32) NOT NULL  ,
   IDCATEGORIE_1 CHAR(32) NOT NULL  
   , PRIMARY KEY (IDCATEGORIE,IDCATEGORIE_1) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE ASSO_1
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_ASSO_1_CATÉGORIE
     ON ASSO_1 (IDCATEGORIE ASC);

CREATE  INDEX I_FK_ASSO_1_CATÉGORIE1
     ON ASSO_1 (IDCATEGORIE_1 ASC);

# -----------------------------------------------------------------------------
#       TABLE : DETAILENSEMBLE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DETAILENSEMBLE
 (
   IDENSEMBLE CHAR(32) NOT NULL  ,
   IDPRODUIT CHAR(32) NOT NULL  ,
   QUANTITEENSEMBLE CHAR(32) NOT NULL  
   , PRIMARY KEY (IDENSEMBLE,IDPRODUIT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE DETAILENSEMBLE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_DETAILENSEMBLE_ENSEMBLE
     ON DETAILENSEMBLE (IDENSEMBLE ASC);

CREATE  INDEX I_FK_DETAILENSEMBLE_PRODUIT
     ON DETAILENSEMBLE (IDPRODUIT ASC);

# -----------------------------------------------------------------------------
#       TABLE : DETAILCOMMANDE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS DETAILCOMMANDE
 (
   IDPRODUIT CHAR(32) NOT NULL  ,
   IDCOMMANDE CHAR(32) NOT NULL  ,
   QUANTITÉCOMMANDEE CHAR(32) NOT NULL  
   , PRIMARY KEY (IDPRODUIT,IDCOMMANDE) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE DETAILCOMMANDE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_DETAILCOMMANDE_PRODUIT
     ON DETAILCOMMANDE (IDPRODUIT ASC);

CREATE  INDEX I_FK_DETAILCOMMANDE_COMMANDE
     ON DETAILCOMMANDE (IDCOMMANDE ASC);

# -----------------------------------------------------------------------------
#       TABLE : FAVORI
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS FAVORI
 (
   IDPRODUIT CHAR(32) NOT NULL  ,
   IDCLIENT CHAR(32) NOT NULL  ,
   DATEFAVORI CHAR(32) NULL  
   , PRIMARY KEY (IDPRODUIT,IDCLIENT) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE FAVORI
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_FAVORI_PRODUIT
     ON FAVORI (IDPRODUIT ASC);

CREATE  INDEX I_FK_FAVORI_CLIENT
     ON FAVORI (IDCLIENT ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE PAIEMENT 
  ADD FOREIGN KEY FK_PAIEMENT_COMMANDE (IDCOMMANDE)
      REFERENCES COMMANDE (IDCOMMANDE) ;


ALTER TABLE CATÉGORIE 
  ADD FOREIGN KEY FK_CATÉGORIE_CATÉGORIE (IDCATEGORIE_HIÉRARCHISER)
      REFERENCES CATÉGORIE (IDCATEGORIE) ;


ALTER TABLE CATÉGORIE 
  ADD FOREIGN KEY FK_CATÉGORIE_CATÉGORIE1 (IDCATEGORIE_ASSO_2)
      REFERENCES CATÉGORIE (IDCATEGORIE) ;


ALTER TABLE COMMANDE 
  ADD FOREIGN KEY FK_COMMANDE_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE COMMANDE 
  ADD FOREIGN KEY FK_COMMANDE_LIVRAISON (IDLIVRAISON)
      REFERENCES LIVRAISON (IDLIVRAISON) ;


ALTER TABLE COMMANDE 
  ADD FOREIGN KEY FK_COMMANDE_PAIEMENT (IDPAIEMENT)
      REFERENCES PAIEMENT (IDPAIEMENT) ;


ALTER TABLE AVIS 
  ADD FOREIGN KEY FK_AVIS_PRODUIT (IDPRODUIT)
      REFERENCES PRODUIT (IDPRODUIT) ;


ALTER TABLE AVIS 
  ADD FOREIGN KEY FK_AVIS_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE LIVRAISON 
  ADD FOREIGN KEY FK_LIVRAISON_COMMANDE (IDCOMMANDE)
      REFERENCES COMMANDE (IDCOMMANDE) ;


ALTER TABLE LIVRAISON 
  ADD FOREIGN KEY FK_LIVRAISON_POINTRELAIS (IDPOINTRELAIS)
      REFERENCES POINTRELAIS (IDPOINTRELAIS) ;


ALTER TABLE PANIER 
  ADD FOREIGN KEY FK_PANIER_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;


ALTER TABLE DETAILPANIER 
  ADD FOREIGN KEY FK_DETAILPANIER_PRODUIT (IDPRODUIT)
      REFERENCES PRODUIT (IDPRODUIT) ;


ALTER TABLE DETAILPANIER 
  ADD FOREIGN KEY FK_DETAILPANIER_PANIER (IDPANIER)
      REFERENCES PANIER (IDPANIER) ;


ALTER TABLE CLASSER 
  ADD FOREIGN KEY FK_CLASSER_CATÉGORIE (IDCATEGORIE)
      REFERENCES CATÉGORIE (IDCATEGORIE) ;


ALTER TABLE CLASSER 
  ADD FOREIGN KEY FK_CLASSER_PRODUIT (IDPRODUIT)
      REFERENCES PRODUIT (IDPRODUIT) ;


ALTER TABLE ASSO_1 
  ADD FOREIGN KEY FK_ASSO_1_CATÉGORIE (IDCATEGORIE)
      REFERENCES CATÉGORIE (IDCATEGORIE) ;


ALTER TABLE ASSO_1 
  ADD FOREIGN KEY FK_ASSO_1_CATÉGORIE1 (IDCATEGORIE_1)
      REFERENCES CATÉGORIE (IDCATEGORIE) ;


ALTER TABLE DETAILENSEMBLE 
  ADD FOREIGN KEY FK_DETAILENSEMBLE_ENSEMBLE (IDENSEMBLE)
      REFERENCES ENSEMBLE (IDENSEMBLE) ;


ALTER TABLE DETAILENSEMBLE 
  ADD FOREIGN KEY FK_DETAILENSEMBLE_PRODUIT (IDPRODUIT)
      REFERENCES PRODUIT (IDPRODUIT) ;


ALTER TABLE DETAILCOMMANDE 
  ADD FOREIGN KEY FK_DETAILCOMMANDE_PRODUIT (IDPRODUIT)
      REFERENCES PRODUIT (IDPRODUIT) ;


ALTER TABLE DETAILCOMMANDE 
  ADD FOREIGN KEY FK_DETAILCOMMANDE_COMMANDE (IDCOMMANDE)
      REFERENCES COMMANDE (IDCOMMANDE) ;


ALTER TABLE FAVORI 
  ADD FOREIGN KEY FK_FAVORI_PRODUIT (IDPRODUIT)
      REFERENCES PRODUIT (IDPRODUIT) ;


ALTER TABLE FAVORI 
  ADD FOREIGN KEY FK_FAVORI_CLIENT (IDCLIENT)
      REFERENCES CLIENT (IDCLIENT) ;

