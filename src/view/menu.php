<link rel="stylesheet" href="<?php echo(htmlspecialchars('css/menu.css')) ?>"/>
<section class="menu--container">
    <a href="<?php echo('http://' . $_SERVER['HTTP_HOST']) . substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI'])-8) ?>"><img id="close-img" src="assets/menu/close.png" alt="Icone close"/></a>
    <img id="menu-logosomb" src="assets/logo/logoSansOmbre.png" alt="Logo sans ombre"/>
    <h2>VoteIt<span class="colored">.</span></h2>
    <form action="frontController.php" method="get"><input type="hidden" name="controller" value="questions"><input type="hidden" name="action" value="recherche"><input type="text" name="search" id="search" placeholder="Recherche"></form>
    <div class="menu">
        <?php
        if(isset($_GET['controller']) && isset($_GET['action'])){
            if($_GET['controller'] == "home"){
                ?><a href="frontController.php?controller=home&action=home"><p><span>•</span> <span class="bolder">Accueil</span></p></a><?php

            }else {
                ?><a href="frontController.php?controller=home&action=home"><p><span class="colored">•</span> Accueil</p></a><?php
            }
            if($_GET['controller'] == "questions"){
                ?><a href="frontController.php?controller=questions&action=readAll"><p><span>•</span> <span class="bolder">Votes</span></p></a><?php

            }else {
                ?><a href="frontController.php?controller=questions&action=readAll"><p><span class="colored">•</span> Votes</p></a><?php
            }
            if($_GET['controller'] == "profil"){
                ?><a href="frontController.php?controller=profil&action=home"><p><span>•</span> <span class="bolder">Profil</span></p></a><?php

            }else{
                ?><a href="frontController.php?controller=profil&action=home"><p><span class="colored">•</span> Profil</p></a><?php
            }
        }
        ?>
    </div>
</section>