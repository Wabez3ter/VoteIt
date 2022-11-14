<?php

namespace App\VoteIt\Controller;
use App\VoteIt\Controller\ControllerErreur;
use App\VoteIt\Model\DataObject\Section;
use App\VoteIt\Model\Repository\SectionRepository;

class ControllerSections{
    private static function afficheVue(string $cheminVue, array $parametres = []) : void {
        extract($parametres); // Crée des variables à partir du tableau $parametres
        require __DIR__ . "/../view/$cheminVue"; // Charge la vue
    }

    public static function createSectionForCreateQuestion(){
        if(isset($_GET['nbSections']) AND isset($_GET['idQuestion'])){
            $nbSection = $_GET['nbSections'];
            $idQuestion = $_GET['idQuestion'];
            self::afficheVue('view.php', ['pagetitle' => "VoteIt | Crée des sections", 'cheminVueBody' => "sections/createForCreateQuestion.php", 'nbSections' => $nbSection, 'idQuestion' => $idQuestion]);
        }else {
            ControllerErreur::erreurCodeErreur('SC-1');
        }
    }

    public static function error(){
        ControllerErreur::erreurCodeErreur('SC-1');
    }


    public static function created(){
        if(isset($_POST['nbSections']) AND isset($_POST['idQuestion']) AND isset($_POST['section1'])){
            $nbSections = $_POST['nbSections'];
            for($i=1; $i<$nbSections+1; $i++){
                $idSection = ((new SectionRepository())->getIdQuestionMax())+1;
                $idQuestion = $_POST['idQuestion'];
                $sectionName = 'section'.$i;
                $section = $_POST[$sectionName];
                $sectionTemp = new Section($idSection, $idQuestion, $section);
                (new SectionRepository())->createSection($sectionTemp);
            }
            header("Location: frontController.php?controller=questions&action=home");
            exit();
        }else {
            ControllerErreur::erreurCodeErreur('SC-1');
        }
    }

}