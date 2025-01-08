<?php
$pageTitle = "À propos de Nautic Horizon";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
    require_once './include/connect.inc.php';
?>

<!-- Contenu principal -->
<main role="main">
    <section style="background-color: #ECF0FB;">
        <section class="container mb-5" style="position: relative; width: 100%; max-width: 1500px; margin: 0 auto; padding-top: 30px; padding-bottom: 30px;">
            <img src="image/bateau.jpg" alt="Image Nautic Horizon" style="width: 100%; height: 600px; display: block; border-radius: 10px;">
            <div style="
                position: absolute; 
                top: 40px; 
                left: 25px; 
                background-color: rgba(0, 0, 0, 0.7); 
                color: #fff; 
                padding: 20px; 
                width: calc(100% - 40px); 
                max-width: 350px; 
                border-radius: 10px; 
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); 
                font-family: 'Arial', sans-serif;
            ">
                <h2 style="font-size: 1.8rem; margin-bottom: 10px; font-weight: bold;">À propos de Nautic Horizon</h2>
                <p style="font-size: 1rem; line-height: 1.6; margin: 0;">
                    Basée à Singapour, Nautic Horizon est un leader dans la vente de bateaux et d’équipements maritimes, avec une présence forte en France, en Suisse et au Luxembourg.
                    L'entreprise combine tradition et innovation pour offrir des solutions nautiques adaptées à tous.
                </p>
            </div>
        </section>
    </section>

    <section>
        <section class="container mb-5" style="display: flex; justify-content: space-between; gap: 2rem; align-items: center; height: 100%;">
            <div class="left-section" style="flex: 1; font-size: 1.2rem; line-height: 1.8;">
                <h2 style="font-size: 2rem; margin-bottom: 1rem; color: #0056b3;">Pourquoi choisir Nautic Horizon ?</h2>
            </div>
            <div class="right-section" style="flex: 1; display: flex; flex-direction: column; gap: 1rem; font-size: 1.2rem; line-height: 1.8; padding-left: 1rem; border-left: 2px solid #ddd;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="position: relative; padding-left: 1.5rem;">
                        <span style="position: absolute; left: 0; color: #28a745;">✔</span>
                        Expertise historique : Un héritage maritime depuis le XVIIIe siècle.
                    </li>
                    <li style="position: relative; padding-left: 1.5rem;">
                        <span style="position: absolute; left: 0; color: #28a745;">✔</span>
                        Catalogue diversifié : Des yachts de luxe aux équipements maritimes spécialisés.
                    </li>
                    <li style="position: relative; padding-left: 1.5rem;">
                        <span style="position: absolute; left: 0; color: #28a745;">✔</span>
                        Présence internationale : Des magasins stratégiquement situés et un siège social à Singapour.
                    </li>
                    <li style="position: relative; padding-left: 1.5rem;">
                        <span style="position: absolute; left: 0; color: #28a745;">✔</span>
                        Engagement envers l’innovation : Intégration des dernières technologies dans chaque produit.
                    </li>
                </ul>
            </div>
        </section>
    </section>

    <section style="background-color: #ECF0FB;">
        <section class="container mb-5" style="padding-top: 2rem; padding-bottom: 3rem;">
            <h2 style="text-align: center; font-size: 2.5rem; font-weight: bold; color: #0056b3; margin-bottom: 2rem;">Ce que disent nos clients</h2>
            
            <div id="carouselTestimonials" class="carousel slide" data-bs-ride="carousel">
                <!-- Indicateurs -->
                <div class="carousel-indicators" style="margin-bottom: -30px;">
                    <button type="button" data-bs-target="#carouselTestimonials" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1" style="background-color: #0056b3; width: 10px; height: 10px;"></button>
                    <button type="button" data-bs-target="#carouselTestimonials" data-bs-slide-to="1" aria-label="Slide 2" style="background-color: #0056b3; width: 10px; height: 10px;"></button>
                    <button type="button" data-bs-target="#carouselTestimonials" data-bs-slide-to="2" aria-label="Slide 3" style="background-color: #0056b3; width: 10px; height: 10px;"></button>
                </div>

                <!-- Contenu du carrousel -->
                <div class="carousel-inner" style="background-color: #f8f9fa; border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); padding: 2rem;">
                    <div class="carousel-item active">
                        <blockquote class="blockquote text-center" style="font-style: italic; font-size: 1.5rem; max-width: 700px; margin: 0 auto;">
                            <p>"Grâce à Nautic Horizon, j'ai pu réaliser mon rêve d'acquérir un yacht de luxe. Le service client est impeccable."</p>
                            <footer class="blockquote-footer" style="margin-top: 1rem; font-size: 1rem; color: #555;">Robert, amateur de nautisme</footer>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="blockquote text-center" style="font-style: italic; font-size: 1.5rem; max-width: 700px; margin: 0 auto;">
                            <p>"Une expérience fluide et des produits de grande qualité, parfait pour mes besoins professionnels."</p>
                            <footer class="blockquote-footer" style="margin-top: 1rem; font-size: 1rem; color: #555;">Maria, chef d'entreprise</footer>
                        </blockquote>
                    </div>
                    <!-- Troisième témoignage -->
                    <div class="carousel-item">
                        <blockquote class="blockquote text-center" style="font-style: italic; font-size: 1.5rem; max-width: 700px; margin: 0 auto;">
                            <p>"L'équipe de Nautic Horizon m'a guidé à chaque étape de l'achat de mon bateau. Leur expertise est incomparable."</p>
                            <footer class="blockquote-footer" style="margin-top: 1rem; font-size: 1rem; color: #555;">William, passionné de voile</footer>
                        </blockquote>
                    </div>
                </div>

                <!-- Contrôles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselTestimonials" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                    <span class="visually-hidden">Précédent</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselTestimonials" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>
            </div>
        </section>
    </section>

    <section>
        <section class="container mb-5" style="display: flex; align-items: center; gap: 2rem; margin-bottom: 5rem; padding: 2rem; background-color: #f8f9fa; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
            <!-- Image historique -->
            <div style="flex: 1; text-align: center;">
                <img src="https://www.parismatch.com/lmnr/r/960,640,FFFFFF,forcex,center-middle/img/var/pm/public/styles/paysage/public/media/image/2022/03/04/21/Le-tournage-de-Pirates-des-Caraibes-5-repousse.jpg?VersionId=KZaC7ef8ZMkvqeZhDRdShPc6QmfoCAfB" 
                    alt="Histoire Nautic Horizon" 
                    style="max-width: 100%; border-radius: 10px; object-fit: cover; box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.15);">
                <p style="font-size: 1.2rem; font-weight: bold; color: #333; margin-top: 0.5rem;">William Leboeuf</p>
            </div>
            <!-- Texte -->
            <div style="flex: 2;">
                <h2 style="font-size: 2.5rem; font-weight: bold; color: #0056b3; margin-bottom: 1rem;">Notre Histoire</h2>
                <p style="font-size: 1.2rem; line-height: 1.8; color: #333; text-align: justify;">
                    L’entreprise tire ses racines de William Leboeuf, pionnier de l’architecture navale. À travers les siècles, ses descendants ont perpétué cet héritage,
                    créant <strong>Nautic Horizon</strong> pour offrir des solutions modernes tout en respectant des traditions maritimes fortes.
                </p>
            </div>
        </section>
    </section>

    <section style="background-color: #ECF0FB;">
        <section class="container mb-5" style="max-width: 700px; margin: 0 auto;">
            <h2 style="text-align: center; font-size: 2.5rem; color: #0056b3; margin-bottom: 2rem;">Notre Équipe</h2>
            <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                <!-- Membre 1 -->
                <div style="text-align: center;">
                    <img src="https://img.20mn.fr/M0L5sgogQkOJk6YFacRw_yk/722x460_booba-attending-the-casablanca-womenswear-spring-summer-2024-fashion-show-as-part-of-the-paris-fashion-week-in-paris-france-on-october-01-2023-03haedrichjm-jmh-0047-credit-jm-haedrich-sipa-2310020623" alt="Nolhan Biblocque" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.5rem;">Nolhan Biblocque</h3>
                    <p style="font-size: 1rem; color: #777;">Directeur Général</p>
                </div>

                <!-- Membre 2 -->
                <div style="text-align: center;">
                    <img src="https://quai-m.fr/sites/quaim/files/styles/16x9_1920/public/2023-03/werenoicfifou_3.jpg?h=c03ca587&itok=kMkoiaeK" alt="Mathys Laguilliez" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.5rem;">Mathys Laguilliez</h3>
                    <p style="font-size: 1rem; color: #777;">Co-Directeur</p>
                </div>

                <!-- Membre 3 -->
                <div style="text-align: center;">
                    <img src="https://www.parismatch.com/lmnr/r/960,640,FFFFFF,forcex,center-middle/img/var/pm/public/styles/paysage/public/media/image/2022/03/03/19/Maitre-Gims-de-la-rue-aux-studios.jpg?VersionId=1UUY9IzBfTQzaf7QDHw_r.h7nUG0raSd" alt="Victor Jockin" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.5rem;">Victor Jockin</h3>
                    <p style="font-size: 1rem; color: #777;">Responsable Design</p>
                </div>

                <!-- Membre 4 -->
                <div style="text-align: center;">
                    <img src="https://cdn-images.dzcdn.net/images/artist/e51a371262f19bb529820f88527d1410/1900x1900-000000-80-0-0.jpg" alt="Mucahit Lekesiz" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.5rem;">Mucahit Lekesiz</h3>
                    <p style="font-size: 1rem; color: #777;">Responsable Technique</p>
                </div>

                <!-- Membre 5 -->
                <div style="text-align: center;">
                    <img src="https://www.metalorgie.com/media/cache/band_hero/images/band/picture/Kendrick-Lamar.jpeg" alt="Léo Guinvarc’h" style="border-radius: 50%; width: 150px; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                    <h3 style="font-size: 1.5rem; color: #333; margin-bottom: 0.5rem;">Léo Guinvarc’h</h3>
                    <p style="font-size: 1rem; color: #777;">Responsable Marketing</p>
                </div>
            </div>
        </section>
    </section>

    <section>
        <section class="container mb-5" style="max-width: 1200px; margin: 0 auto;">
            <h2 style="text-align: center; font-size: 2.5rem; color: #0056b3; margin-bottom: 2rem;">Nos chiffres clés</h2>
            <div style="display: flex; justify-content: space-around; gap: 2rem; flex-wrap: wrap;">

                <!-- Années d'existance -->
                <div style="text-align: center; width: 200px; background-color: #f4f4f4; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">+300</div>
                    <div style="font-size: 1rem; color: #777; margin-top: 0.5rem;">Années d'expérience</div>
                </div>

                <!-- Chiffre d'affaires -->
                <div style="text-align: center; width: 200px; background-color: #f4f4f4; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">399M€</div>
                    <div style="font-size: 1rem; color: #777; margin-top: 0.5rem;">Chiffre d’affaires</div>
                </div>

                <!-- Capitalisation boursière -->
                <div style="text-align: center; width: 200px; background-color: #f4f4f4; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">942M€</div>
                    <div style="font-size: 1rem; color: #777; margin-top: 0.5rem;">Capitalisation boursière</div>
                </div>

                <!-- Implentation -->
                <div style="text-align: center; width: 200px; background-color: #f4f4f4; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">10</div>
                    <div style="font-size: 1rem; color: #777; margin-top: 0.5rem;">Magasins physiques</div>
                </div>

                <!-- Ventes -->
                <div style="text-align: center; width: 200px; background-color: #f4f4f4; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">+10k</div>
                    <div style="font-size: 1rem; color: #777; margin-top: 0.5rem;">Bateaux vendus</div>
                </div>

            </div>
        </section>
    </section>
</main>

<!-- Pied de page -->
<?php require_once "./include/footer.php"; ?>
</body>
</html>
