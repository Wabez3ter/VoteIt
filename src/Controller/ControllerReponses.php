<?php

namespace App\VoteIt\Controller;

use App\VoteIt\Controller\ControllerErreur;
use App\VoteIt\Lib\ConnexionUtilisateur;
use App\VoteIt\Lib\MessageFlash;
use App\VoteIt\Lib\MotDePasse;
use App\VoteIt\Model\DataObject\ReponseSection;
use App\VoteIt\Model\DataObject\Reponse;
use App\VoteIt\Model\Repository\PermissionsRepository;
use App\VoteIt\Model\Repository\QuestionsRepository;
use App\VoteIt\Model\Repository\ReponseSectionRepository;
use App\VoteIt\Model\Repository\ReponsesRepository;
use App\VoteIt\Model\Repository\SectionRepository;
use App\VoteIt\Model\Repository\UtilisateurRepository;
use App\VoteIt\Model\Repository\VoteRepository;

class ControllerReponses{
    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../view/$cheminVue"; // Charge la vue
    }

    public static function create(){
        if(isset($_GET['idQuestion'])){
            $sections = (new SectionRepository())->selectAllByIdQuestion($_GET['idQuestion']);
            self::afficheVue('view.php', ['pagetitle' => "VoteIt - Création d'une réponse", 'cheminVueBody' => "reponses/create.php", 'sections' => $sections]);
        }else {
            MessageFlash::ajouter("warning", "Identifiant Question manquant");
            header("Location: frontController.php?controller=question&action=home");
            exit();
        }
    }

    public static function see(){
        if(isset($_GET['idReponse'])){
            $reponse = (new ReponsesRepository())->selectReponseByIdReponse($_GET['idReponse']);
            $question = (new QuestionsRepository())->select($reponse->getIdQuestion());
            $sectionsReponse = (new ReponseSectionRepository())->selectAllByIdReponse($reponse->getIdReponse());
            $allIdReponses = (new ReponsesRepository())->allIdReponseByIdQuestion($reponse->getIdQuestion());

            $estCoAuteur = (new PermissionsRepository())->getPermissionCoAuteurParIdUtilisateurEtIdReponse($reponse->getIdReponse(), ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $estVotant = (new PermissionsRepository())->getPermissionVotantParIdUtilisateurEtIdQuestion($reponse->getIdQuestion(), ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $user = null;

            $voteState = false;
            $canDelete = false;
            $canModif = false;


            if (ConnexionUtilisateur::estConnecte()) {
                $user =  (new UtilisateurRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
                if ((strcmp($reponse->getAutheurId(), $user->getIdentifiant()) == 0) or (strcmp($user->getGrade(), "Administrateur") == 0)) {
                    $canDelete = true;
                    $canModif = true;
                    $estVotant = true;
                }
                if($estCoAuteur){
                    $canModif = true;
                    $estVotant = true;
                }
                if ($estVotant) {
                    $voteState = (new VoteRepository())->stateVote($reponse->getIdQuestion(), $user->getIdentifiant());
                }
            }

            self::afficheVue('view.php', ['pagetitle' => "VoteIt - Réponse", 'cheminVueBody' => "reponses/see.php", 'reponse' => $reponse, 'sectionsReponse' => $sectionsReponse, 'question' => $question, 'allIdReponses' => $allIdReponses, 'estCoAuteur' => $estCoAuteur, 'estVotant' => $estVotant, 'user' => $user, 'voteState' => $voteState, 'canModif' => $canModif, 'canDelete' => $canDelete]);
        }else {
            MessageFlash::ajouter("warning", "Identifiant Reponse manquant");
            header("Location: frontController.php?controller=question&action=home");
            exit();
        }
    }

    public static function update() {
        if(isset($_GET['idReponse'])){
            $reponse = (new ReponsesRepository())->select($_GET['idReponse']);
            $reponseSection = (new ReponseSectionRepository())->selectAllByIdReponse($reponse->getIdReponse());
            $titleReponse = $reponse->getTitreReponse();

            //Liste des utilisateurs qui sont des co-auteurs
            $coauteur = (new PermissionsRepository())->getListePermissionCoAuteurParReponse($reponse->getIdReponse());
            $coauteurStr = '';
            foreach ($coauteur as $item){
                $coauteurStr = $coauteurStr . ", " . (new UtilisateurRepository())->select($item->getIdUtilisateur())->getMail();
            }
            $coauteurStr = substr($coauteurStr, 2);


            self::afficheVue('view.php', ['pagetitle' => "VoteIt - Modifier une réponse", 'cheminVueBody' => "reponses/update.php", 'reponse' => $reponse, 'reponseSection' => $reponseSection, 'coauteurStr' => $coauteurStr, 'titleReponse' => $titleReponse]);
        }else {
            MessageFlash::ajouter("warning", "Identifiant Reponse manquant");
            header("Location: frontController.php?controller=question&action=home");
            exit();
        }
    }

    public static function delete() {
        if (isset($_GET['idReponse'])) {
            $reponse = (new ReponsesRepository())->select($_GET['idReponse']);
            self::afficheVue('view.php',['pagetitle' => "VoteIt - Suppression de la réponse", 'cheminVueBody' => "reponses/delete.php", 'reponse' => $reponse]);
        }else {
            MessageFlash::ajouter("warning", "Identifiant Reponse manquant");
            header("Location: frontController.php?controller=question&action=home");
            exit();
        }
    }

    public static function error(){
        ControllerErreur::erreurCodeErreur('RC-1');
    }

    //NOT SEE
    public static function created(){
        if(isset($_POST['idQuestion']) and isset($_POST['autheur']) AND isset($_POST['titreReponse']) AND isset($_POST['idSection1']) AND isset($_POST['nbSection']) AND isset($_POST['texteSection1'])){
            $idQuestion = $_POST['idQuestion'];
            $autheur = $_POST['autheur'];
            $titreReponse = $_POST['titreReponse'];
            $nbSection = $_POST['nbSection'];

            $idReponse = (new ReponsesRepository())->getIdReponseMax() + 1;

            $reponse = new Reponse($idReponse, $idQuestion, $titreReponse, $autheur, 0);

            (new ReponsesRepository())->createReponse($reponse);

            for($i=1; $i<$nbSection+1; $i++){
                $texteSection = $_POST['texteSection' . $i];
                $idSection = $_POST['idSection'.$i];

                $ReponseSection = new ReponseSection($idSection, $idReponse, $texteSection);
                (new ReponseSectionRepository())->createReponseSection($ReponseSection);
            }

            //SUPPRESION DES PERMISSION EXISTANTE
            (new PermissionsRepository())->deleteAllPermissionForIdReponse($idReponse);

            //RECUPERATION DE LA LISTE DES UTILISATEUR CO-AUTEUR
            $coauteurInput = $_POST['userCoAuteur'];
            if(strlen($coauteurInput) > 0){
                //SEPARATION EN ARGUMENT
                $coauteurInputArgs = explode(", ", $coauteurInput);
                //POUR TOUS LES UTILISATEUR
                foreach ($coauteurInputArgs as $item){
                    //J'ENTRE LEUR NOUVELLE PERMISSION
                    (new PermissionsRepository())->addReponsePermission((new UtilisateurRepository())->selectUserByMail($item)->getIdentifiant(), $idReponse, "CoAuteur");
                }
            }

            MessageFlash::ajouter("success", "Réponse créée.");
            header("Location: frontController.php?controller=questions&action=see&idQuestion=".$idQuestion);
            exit();
        }else {
            MessageFlash::ajouter("warning", "Informations manquant");
            header("Location: frontController.php?controller=reponses&action=create&idQuestion=".$_POST['idQuestion']);
            exit();
        }
    }

    public static function updated() {
        if(isset($_POST['idReponse']) and isset($_POST['autheur']) AND isset($_POST['titreReponse']) AND isset($_POST['nbSection']) AND isset($_POST['idQuestion'])){
            $modelReponse = new Reponse($_POST['idReponse'], $_POST['idQuestion'], $_POST['titreReponse'], $_POST['autheur'], true);
            (new ReponsesRepository())->update($modelReponse);

            for($i=1; $i<$_POST['nbSection']+1; $i++){
                $modelSection = new ReponseSection($_POST['idSection'.$i], $_POST['idReponse'], $_POST['texteSection'.$i]);
                (new ReponseSectionRepository())->updateReponseSection($modelSection);
            }

            //SUPPRESION DES PERMISSION EXISTANTE
            (new PermissionsRepository())->deleteAllPermissionForIdReponse($_POST['idReponse']);

            //RECUPERATION DE LA LISTE DES UTILISATEUR CO-AUTEUR
            $coauteurInput = $_POST['userCoAuteur'];
            if(strlen($coauteurInput) > 0){
                //SEPARATION EN ARGUMENT
                $coauteurInputArgs = explode(", ", $coauteurInput);
                //POUR TOUS LES UTILISATEURS
                foreach ($coauteurInputArgs as $item){
                    //J'ENTRE LEUR NOUVELLE PERMISSION
                    (new PermissionsRepository())->addReponsePermission((new UtilisateurRepository())->selectUserByMail($item)->getIdentifiant(), $_POST['idReponse'], "CoAuteur");
                }
            }

            MessageFlash::ajouter("info","Réponse mise à jour.");
            header("Location: frontController.php?controller=questions&action=see&idQuestion=".$_POST['idQuestion']);
            exit();
        }else {
            header("Location: frontController.php?controller=questions&action=update&idQuestion=".$_POST['idQuestion']);
            exit();
        }
    }

    public static function deleted(){
        $user = (new UtilisateurRepository())->select(ConnexionUtilisateur::getLoginUtilisateurConnecte());
        if(MotDePasse::verifier($_POST['mdpUser'], $user->getMotDePasse())){
            if(isset($_POST['idReponse'])){
                $idQuestion = (new ReponsesRepository())->selectReponseByIdReponse($_POST['idReponse'])->getIdQuestion();
                (new ReponsesRepository())->deleteReponseByIdReponse($_POST['idReponse']);

                MessageFlash::ajouter("danger", "Réponse supprimée.");
                header("Location: frontController.php?controller=questions&action=see&idQuestion=".$idQuestion);
                exit();
            }else {
                header("Location: frontController.php?controller=questions&action=home");
                exit();
            }
        }else {
            MessageFlash::ajouter("warning", "Mot de passe incorrect");
            header("Location: frontController.php?controller=reponses&action=delete&idReponse=".$_POST['idReponse']);
            exit();
        }

    }



}