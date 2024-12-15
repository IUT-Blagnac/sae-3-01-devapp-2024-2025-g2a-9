-- ----------------------------------------------------------------------------- 
--       Calcul du montant total d'un panier (d'un utilisateur)
-- ----------------------------------------------------------------------------- 
DROP PROCEDURE IF EXISTS CalculerTotalPanier;/
CREATE PROCEDURE CalculerTotalPanier(
    IN user_id INT,
    OUT total DECIMAL(10, 2)
)
BEGIN
    -- Calculer le total du panier pour un utilisateur donné
    SELECT SUM(dp.QUANTITEPANIER * p.PRIX)
    INTO total
    FROM DETAILPANIER dp
    JOIN PRODUIT p ON dp.IDPRODUIT = p.IDPRODUIT
    WHERE dp.IDUTILISATEUR = user_id;

    -- Si aucun produit, total est 0
    IF total IS NULL THEN
        SET total = 0;
    END IF;
END/

-- ----------------------------------------------------------------------------- 
--       Modifier un utilisateur
-- ----------------------------------------------------------------------------- 
DROP PROCEDURE IF EXISTS ModifierUtilisateur;/
CREATE PROCEDURE ModifierUtilisateur(
    IN idUtilisateur INT,
    IN civilite VARCHAR(3),
    IN nom VARCHAR(50),
    IN prenom VARCHAR(50),
    IN pays VARCHAR(30),
    IN dateN DATE,
    IN mail VARCHAR(50),
    IN telephone VARCHAR(15),
    IN adresse VARCHAR(50),
    IN password VARCHAR(255),
    IN modifierPassword BOOLEAN
)
BEGIN
    IF modifierPassword THEN
        -- Update pour le mot de passe
        UPDATE UTILISATEUR
        SET PASSWORD = password
        WHERE IDUTILISATEUR = idUtilisateur;
    ELSE
        -- Update pour les informations personnelles
        UPDATE UTILISATEUR
        SET CIVILITE = civilite,
            NOM = nom,
            PRENOM = prenom,
            PAYS = pays,
            DATEN = dateN,
            MAIL = mail,
            TELEPHONE = telephone,
            ADRESSE = adresse
        WHERE IDUTILISATEUR = idUtilisateur;
    END IF;
END/

-- ----------------------------------------------------------------------------- 
--       Calculer le nombre total de produits d'une commande
-- ----------------------------------------------------------------------------- 
DROP PROCEDURE IF EXISTS CalculerTotalArticleCommande;/
CREATE PROCEDURE CalculerTotalArticleCommande(
    IN idCommande INT,
    OUT total INT
)
BEGIN
    -- Calculer le total des produits commandés pour une commande donnée
    SELECT SUM(DC.QUANTITECOMMANDEE)
    INTO total
    FROM DETAILCOMMANDE DC
    WHERE DC.IDCOMMANDE = idCommande;

    -- Techniquement une commande contient toujours au minimum 1 produit (au cas ou)
    IF total IS NULL THEN
        SET total = 0;
    END IF;
END
/
