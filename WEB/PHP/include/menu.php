<!-- Sidebar -->
<!-- 
  On fait un for de toute les catégories pères et dans chacune d'entre elle : on récuprère les sous catég.
  pour les écrire sous la forme de lien :
      catégorie père = 1 sidebar-item 
      ses catégories fils = x sidebar-item dans la liste sidebar-dropdown
-->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body overflow-auto">
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#Categorie1" aria-expanded="false" aria-controls="Categorie1">Categorie 1</a>
            <ul id="Categorie1" class="sidebar-dropdown list-unstyled collapse">
                <li class="sidebar-item"><a href="#" class="sidebar-link">Sous-catégorie 1</a></li>
                <li class="sidebar-item"><a href="#" class="sidebar-link">Sous-catégorie 2</a></li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" data-bs-target="#Categorie2" aria-expanded="false" aria-controls="Categorie2">Categorie 2</a>
            <ul id="Categorie2" class="sidebar-dropdown list-unstyled collapse">
                <li class="sidebar-item"><a href="#" class="sidebar-link">Sous-catégorie 1</a></li>
                <li class="sidebar-item"><a href="#" class="sidebar-link">Sous-catégorie 2</a></li>
            </ul>
        </li>
    </ul>
  </div>
</div>

