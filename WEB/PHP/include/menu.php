<!-- Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body overflow-auto">
    <ul class="sidebar-nav list-unstyled">
        <?php
          include ("./include/connect.inc.php");
          $reqCateg = $conn->prepare("SELECT * FROM CATEGORIE WHERE IDCATEGPERE IS NULL;") ;
          $reqCateg->execute();
          foreach($reqCateg as $categ) {
            echo "<li class=\"sidebar-item\">";
              echo "<a href=\"#\" class=\"sidebar-link has-dropdown collapsed\" data-bs-toggle=\"collapse\" data-bs-target=\"#".$categ['IDCATEGORIE']."\" aria-expanded=\"false\" aria-controls=\"".$categ['IDCATEGORIE']."\">".$categ['NOMCATEGORIE']."</a>";
              echo "<ul id=\"".$categ['IDCATEGORIE']."\" class=\"sidebar-dropdown list-unstyled collapse\">";
                echo "<li class=\"sidebar-item\"><a href=\"consultProduits.php?idCateg=".$categ['IDCATEGORIE']."\" class=\"sidebar-link\">Afficher tout les produits</a></li>";  
                $reqSousCateg = $conn->prepare("SELECT * FROM CATEGORIE WHERE IDCATEGPERE = ? ;") ;
                $reqSousCateg->execute([$categ['IDCATEGORIE']]);
                foreach($reqSousCateg as $sousCateg) {
                  echo "<li class=\"sidebar-item\"><a href=\"consultProduits.php?idCateg=".$sousCateg['IDCATEGORIE']."\" class=\"sidebar-link\">".$sousCateg['NOMCATEGORIE']."</a></li>";
                }
                $reqSousCateg->closeCursor();
              echo "</ul>";
            echo "</li>";
          }   
          $reqCateg->closeCursor();
        ?>
    </ul>
  </div>
</div>