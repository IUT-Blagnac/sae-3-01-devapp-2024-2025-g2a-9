<?php
$pageTitle = "Contact";
require_once "./include/head.php";
?>
<body>
<?php
    require_once "./include/header.php";
    require_once "./include/menu.php";
?>

<style>
*, *:before, *:after {
  box-sizing: border-box;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.screen {
  margin-top: 2rem;
  position: relative;
  background: #ECF0FB;
  border-radius: 15px;
}

.screen:after {
  content: '';
  display: block;
  position: absolute;
  top: 0;
  left: 20px;
  right: 20px;
  bottom: 0;
  border-radius: 15px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
  z-index: -1;
}

.screen-body {
  display: flex;
}

.screen-body-item {
  flex: 1;
  padding: 50px;
}

.screen-body-item.left {
  display: flex;
  flex-direction: column;
}

.app-title {
  display: flex;
  flex-direction: column;
  position: relative;
  color: #007bff;
  font-size: 30px;
}

.app-title:after {
  content: '';
  display: block;
  position: absolute;
  left: 0;
  bottom: -10px;
  width: 25px;
  height: 4px;
  background: #007bff;
}

.app-form-group {
  margin-bottom: 15px;
}

.app-form-group.message {
  margin-top: 40px;
}

.app-form-group.buttons {
  margin-bottom: 0;
  text-align: right;
}

.app-form-control {
  width: 100%;
  padding: 10px 0;
  background: none;
  border: none;
  border-bottom: 1px solid #666;
  color: black;
  font-size: 14px;
  outline: none;
  transition: border-color .2s;
}

textarea.app-form-control {
    width: 100%; /* Largeur pleine */
    resize: none; /* Désactive le redimensionnement manuel */
    overflow: hidden; /* Évite les barres de défilement */
    margin-top: 0px;
    padding-top: 0px;
}

.app-form-control::placeholder {
  color: #666;
}

.app-form-control:focus {
  border-bottom-color: #007bff;
}

.app-form-button {
  background: none;
  border: none;
  color: #007bff;
  font-size: 14px;
  cursor: pointer;
  outline: none;
  transition: transform 0.3s ease, color 0.3s ease;
}

.app-form-button:hover {
  color:rgb(0, 70, 145);
}

@media screen and (max-width: 520px) {
  .screen-body {
    flex-direction: column;
  }

  .screen-body-item.left {
    margin-bottom: 30px;
  }

  .app-title {
    flex-direction: row;
  }

  .app-title span {
    margin-right: 12px;
  }

  .app-title:after {
    display: none;
  }
}

@media screen and (max-width: 600px) {
  .screen-body {
    padding: 40px;
  }

  .screen-body-item {
    padding: 0;
  }
}
</style>

    <!-- Contenu principal -->
    <main role="main" class="container my-5">
        <div class="background">
            <div class="container">
                <div class="screen">
                    <div class="screen-body">
                        <div class="screen-body-item left">
                            <div class="app-title">
                                <span>CONTACTER</span>
                                <span>NOUS</span>
                            </div>
                        </div>
                        <div class="screen-body-item">
                            <form class="app-form" method="POST" action="https://formspree.io/f/mzzzjojz">
                                <div class="app-form-group">
                                    <input class="app-form-control" name="name" placeholder="NOM" required>
                                </div>
                                <div class="app-form-group">
                                    <input class="app-form-control" type="email" name="email" placeholder="EMAIL" required>
                                </div>
                                <div class="app-form-group">
                                    <input class="app-form-control" name="subject" placeholder="OBJET">
                                </div>
                                <div class="app-form-group message">
                                    <textarea class="app-form-control" name="message" placeholder="MESSAGE" oninput="autoResize(this)" rows="1" required></textarea>
                                </div>
                                <div class="app-form-group buttons">
                                    <button type="submit" class="app-form-button">ENVOYER</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function autoResize(textarea) {
            // Réinitialise la hauteur pour calculer correctement
            textarea.style.height = 'auto';
            // Ajuste la hauteur en fonction du défilement
            textarea.style.height = textarea.scrollHeight + 'px';
        }
    </script>
    
    <!-- Pied de page -->
    <?php require_once "./include/footer.php"; ?>
</body>
</html>
