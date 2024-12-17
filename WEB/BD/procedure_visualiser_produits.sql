DELIMITER $$

CREATE PROCEDURE ObtenirProduitsFiltres(
    IN p_categories TEXT,
    IN p_energie VARCHAR(50),
    IN p_taille INT,
    IN p_prixMin DECIMAL(10, 2),
    IN p_prixMax DECIMAL(10, 2),
    IN p_sort VARCHAR(4)
)
BEGIN
    SET @query = 'SELECT * FROM PRODUIT WHERE 1=1';

    -- Filtrer par catégories
    IF p_categories IS NOT NULL AND p_categories != '' THEN
        SET @query = CONCAT(@query, ' AND IDCATEGORIE IN (', p_categories, ')');
    END IF;

    -- Filtrer par énergie
    IF p_energie IS NOT NULL THEN
        SET @query = CONCAT(@query, ' AND ENERGIE = "', p_energie, '"');
    END IF;

    -- Filtrer par taille avec tolérance
    IF p_taille IS NOT NULL THEN
        SET @tailleMin = p_taille - 5;
        SET @tailleMax = p_taille + 5;
        SET @query = CONCAT(@query, ' AND CAST(REPLACE(TAILLE, "m", "") AS UNSIGNED) BETWEEN ', @tailleMin, ' AND ', @tailleMax);
    END IF;

    -- Filtrer par prix minimum avec tolérance
    IF p_prixMin IS NOT NULL THEN
        SET @prixMinTol = p_prixMin * 0.8;
        SET @query = CONCAT(@query, ' AND PRIX >= ', @prixMinTol);
    END IF;

    -- Filtrer par prix maximum avec tolérance
    IF p_prixMax IS NOT NULL THEN
        SET @prixMaxTol = p_prixMax * 1.15;
        SET @query = CONCAT(@query, ' AND PRIX <= ', @prixMaxTol);
    END IF;

    -- Trier les résultats
    IF p_sort = 'asc' THEN
        SET @query = CONCAT(@query, ' ORDER BY PRIX ASC');
    ELSEIF p_sort = 'desc' THEN
        SET @query = CONCAT(@query, ' ORDER BY PRIX DESC');
    ELSE
        SET @query = CONCAT(@query, ' ORDER BY PRIX ASC'); -- Tri par défaut
    END IF;

    -- Exécuter la requête
    PREPARE stmt FROM @query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;