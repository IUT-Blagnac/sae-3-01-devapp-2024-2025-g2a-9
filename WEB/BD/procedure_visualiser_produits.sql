DELIMITER //

-- Supprimer la procédure existante ObtenirCategoriesEnfants si elle existe
DROP PROCEDURE IF EXISTS ObtenirCategoriesEnfants //

-- Créer la procédure ObtenirCategoriesEnfants qui prend 
CREATE PROCEDURE ObtenirCategoriesEnfants(IN idCategorieParent INT)
BEGIN
    -- Sélectionner les catégories enfants d'une catégorie parent
    SELECT IDCATEGORIE FROM CATEGORIE WHERE IDCATEGPERE = idCategorieParent;
END //

-- Supprimer la procédure existante ObtenirProduitsFiltres si elle existe
DROP PROCEDURE IF EXISTS ObtenirProduitsFiltres //

-- Créer la procédure ObtenirProduitsFiltres qui prend en paramètre les filtres de recherche et l'ordre de tri
CREATE PROCEDURE ObtenirProduitsFiltres(
    IN idCategorie INT,            -- Identifiant de la catégorie
    IN filtreEnergie VARCHAR(50),  -- Filtre sur l'énergie
    IN filtreTaille INT,           -- Filtre sur la taille
    IN filtrePrixMin DECIMAL(10,2),-- Filtre sur le prix minimum
    IN filtrePrixMax DECIMAL(10,2),-- Filtre sur le prix maximum
    IN ordreTri VARCHAR(4)         -- Ordre de tri
)
BEGIN
    DECLARE tailleMin INT;
    DECLARE tailleMax INT;
    
    -- Calculer les limites de taille, on prend une marge de 5 cm autour de la taille spécifiée
    SET tailleMin = filtreTaille - 5;
    SET tailleMax = filtreTaille + 5;
    
    -- Sélectionner les produits en fonction des filtres
    SELECT * FROM PRODUIT
    WHERE IDCATEGORIE IN (
        -- Inclure les produits de la catégorie spécifiée et de ses sous-catégories
        SELECT IDCATEGORIE FROM CATEGORIE WHERE IDCATEGORIE = idCategorie
        UNION
        SELECT IDCATEGORIE FROM CATEGORIE WHERE IDCATEGPERE = idCategorie
    )
    -- Appliquer les filtres
    AND (filtreEnergie IS NULL OR ENERGIE = filtreEnergie)
    AND (filtreTaille IS NULL OR CAST(REPLACE(TAILLE, 'm', '') AS UNSIGNED) BETWEEN tailleMin AND tailleMax)
    AND (filtrePrixMin IS NULL OR PRIX >= filtrePrixMin)
    AND (filtrePrixMax IS NULL OR PRIX <= filtrePrixMax)
    -- Appliquer l'ordre de tri
    ORDER BY
        CASE
            WHEN ordreTri = 'asc' THEN PRIX
        END ASC,
        CASE
            WHEN ordreTri = 'desc' THEN PRIX
        END DESC;
END //

DELIMITER ;