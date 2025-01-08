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

-- ----------------------------------------------------------------------------- 
--       Créer une commande
-- ----------------------------------------------------------------------------- 
DROP PROCEDURE IF EXISTS CreerCommande;/
CREATE PROCEDURE CreerCommande(
    IN p_idUtilisateur INT,
    IN p_modelivraison VARCHAR(30),
    IN p_adresseLivraison VARCHAR(50),
    IN p_idPointRelais INT,
    IN p_modePaiement VARCHAR(30),
    IN p_prixCommande DECIMAL(15, 2)
)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_idCommande INT;
    DECLARE v_idPaiement INT;
    DECLARE v_idProduit INT;
    DECLARE v_quantitePanier INT;
    DECLARE v_stockProduit INT;

    DECLARE panierCursor CURSOR FOR
    SELECT IDPRODUIT, QUANTITEPANIER
    FROM DETAILPANIER
    WHERE IDUTILISATEUR = p_idUtilisateur;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- Insertion du paiement
    INSERT INTO PAIEMENT (PRIXCOMMANDE, MODEPAIEMENT)
    VALUES (p_prixCommande, p_modePaiement);
    -- Récup de l'idPaiement
    SET v_idPaiement = LAST_INSERT_ID();

    -- Insertion de la commande
    INSERT INTO COMMANDE (IDUTILISATEUR, IDPAIEMENT, DATECOMMANDE, MODELIVRAISON, ADRESSELIVRAISON, IDPOINTRELAIS)
    VALUES (p_idUtilisateur, v_idPaiement, NOW(), p_modelivraison, p_adresseLivraison, p_idPointRelais);
    -- Récup de l'idCommande
    SET v_idCommande = LAST_INSERT_ID();

    OPEN panierCursor;
        read_loop: LOOP
            FETCH panierCursor INTO v_idProduit, v_quantitePanier;
            IF done THEN
                LEAVE read_loop;
            END IF;

            -- Vérifier le stock disponible
            SELECT STOCK INTO v_stockProduit
            FROM PRODUIT
            WHERE IDPRODUIT = v_idProduit;

            -- Si le stock est suffisant, procéder à l'insertion dans DETAILCOMMANDE
            IF v_stockProduit >= v_quantitePanier THEN
                INSERT INTO DETAILCOMMANDE (IDPRODUIT, IDCOMMANDE, QUANTITECOMMANDEE)
                VALUES (v_idProduit, v_idCommande, v_quantitePanier);

                -- Mise à jour du stock après la commande
                UPDATE PRODUIT
                SET STOCK = STOCK - v_quantitePanier
                WHERE IDPRODUIT = v_idProduit;
            ELSE
                -- Si le stock est insuffisant, gérer l'erreur (par exemple, on peut lever une exception ou stopper la procédure)
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuffisant pour un des produits';
            END IF;
        END LOOP;
    CLOSE panierCursor;

    -- Vide le panier de l'utilisateur (commande réussi)
    DELETE FROM DETAILPANIER
    WHERE IDUTILISATEUR = p_idUtilisateur;
END
/