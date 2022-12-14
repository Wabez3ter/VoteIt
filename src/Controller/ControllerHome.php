<?php
namespace App\VoteIt\Controller;

class ControllerHome{

    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../view/$cheminVue"; // Charge la vue
    }

    public static function accueil(){
        self::afficheVue('home/accueil.php', []);
    }

    public static function home(){
        self::afficheVue('view.php', ['pagetitle' => "VoteIt", 'cheminVueBody' => "home/home.php"]);
    }

    public static function cgu(){
        self::afficheVue('view.php', ['pagetitle' => "VoteIt - Conditions Générales d'Utilisation", 'cheminVueBody' => "home/cgu.php"]);
    }

    public static function error(){
        MessageFlash::ajouter('warning', "Erreur sur la page principale");
        header("frontController.php");
        exit();
    }
}
