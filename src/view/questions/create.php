<link rel="stylesheet" href="css/formulaire.css">
<link rel="stylesheet" href="css/Questions/questions-formulaire.css">
<form class="formulaire--container" action="frontController.php?controller=questions&action=created" method="post" autocomplete="off">
    <div class="formulaire-template">
        <h2 class="title">Création de la question</h2>
        <div>
            <label for="titreQuestion">Titre</label>
            <input type="text" name="titreQuestion" id="titreQuestion" placeholder="Titre de la question" required/>
        </div>
        <div>
            <label for="nbSection">Nombre de sections</label>
            <input type="number" name="nbSection" id="nbSection" placeholder="3" min="3" max="8" required/>
        </div>
        <div class="autocomplete" id="responsable-container">
            <label for="responsableReponse">Responsable de réponse</label>
            <input type="text" id="respReponse" placeholder="johndoe10@gmail.com" class="autocomplete-input">
        </div>
        <div style="display: flex; flex-direction: row; align-items: left;">
            <div id="ajout-responsable" style="width: 21px; margin: 4px 2px; cursor: pointer;">
                <img id="plus" style="width: 21px; height: 21px;" src="assets/questions/update/add.png" alt="ajout d'un responsable de réponse">
            </div>
            <div id="supprimer-responsable" style="width: 21px; margin: 4px 2px; cursor: pointer;">
                <img id="minus" style="width: 21px; height: 21px;" src="assets/questions/update/minus.png" alt="suppression d'un responsable de réponse">
            </div>
        </div>
        <div class="autocomplete" id="user-votant-container">
            <label for="votant">Utilisateur(s) votant(s)</label>
            <input type="text" id="userVotant" placeholder="johndoe10@gmail.com" class="autocomplete-input">
        </div>
        <div style="display: flex; flex-direction: row; align-items: left;">
            <div id="ajout-votant" style="width: 21px; margin: 4px 2px; cursor: pointer;">
                <img id="plus" style="width: 21px; height: 21px;" src="assets/questions/update/add.png" alt="ajout d'un votant">
            </div>
            <div id="supprimer-votant" style="width: 21px; margin: 4px 2px; cursor: pointer;">
                <img id="minus" style="width: 21px; height: 21px;" src="assets/questions/update/minus.png" alt="suppression d'un votant">
            </div>
        </div>
        <div>
            <label for="categorieQuestion">Catégorie</label>
            <select name="categorieQuestion" id="categorieQuestion" required>
                <?php
                foreach ($categories as $categorie){
                    ?><option value="<?php echo(htmlspecialchars($categorie->getNomCategorie())) ?>"><?php echo(htmlspecialchars($categorie->getNomCategorie())) ?></option><?php
                }
                ?>
            </select>
        </div>
        <div class="date--container">
            <H2>Dates</H2>
            <div>
                <label id="title-date" for="ecritureDateDebut">Début d'écriture des réponses</label>
                <input id="date-input" type="date" name="ecritureDateDebut" id="ecritureDateDebut" placeholder="01-01-2001" required/></span>
                <label id="title-date" for="ecritureDateDebut">Fin d'écriture des réponses</label>
                <input id="date-input" type="date" name="ecritureDateFin" id="ecritureDateFin" placeholder="01-01-2001" required/></span>
            </div>


            <div>
                <label id="title-date" for="voteDateDebut">Début des votes</label>
                <input id="date-input" type="date" name="voteDateDebut" id="voteDateDebut" placeholder="01-01-2001" required/></span>
                <label id="title-date" for="voteDateDebut">Fin des votes</label>
                <input id="date-input" type="date" name="voteDateFin" id="voteDateFin " placeholder="01-01-2001" required/></span>
            </div>
        </div>
        <div id="end-form">
            <input type="hidden" name="controller" value="questions">
            <input type="hidden" name="action" value="created">
            <input type="text" name="autheur" id="autheur" placeholder="JohnDoe10" value="<?php echo(htmlspecialchars($idAuteur))?>" hidden readonly/>

            <?php
            if($poserQuestion){
                ?>
                <input type="hidden" name="poserQuestion" value="<?php echo(htmlspecialchars($poserQuestion)); ?>">
                <input type="submit" value="Poser la question" id="submit-btn">
                <?php
            }else {
                ?>
                <input type="hidden" name="proposerQuestion" value="<?php echo(htmlspecialchars($proposerQuestion)); ?>">
                <input type="submit" value="Proposer la question" id="submit-btn">
                <?php
            }
            ?>
        </div>
        <script type="text/javascript" src="src=../../../web/js/questions/script.js"></script>
        <script type="text/javascript">
            <?php
            use App\VoteIt\Model\Repository\UtilisateurRepository;
            $mails = (new UtilisateurRepository())->getMails();
            ?>
            var mails = JSON.parse(atob('<?php echo base64_encode(json_encode($mails));?>'));
            autocomplete(document.getElementById('userVotant'), mails);
            autocomplete(document.getElementById('respReponse'), mails);
            const plusVotant = document.getElementById('ajout-votant');
            const moinsVotant = document.getElementById('supprimer-votant');
            const plusResponsable = document.getElementById('ajout-responsable');
            const moinsResponsable = document.getElementById('supprimer-responsable');            
            const submit = document.getElementById('submit-btn');

            plusVotant.addEventListener('click', () => {
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.id = 'userVotant';
                newInput.placeholder = 'johndoe10@gmail.com';
                newInput.className = 'autocomplete-input';
                newInput.style.marginTop = '20px';
                document.getElementById('user-votant-container').appendChild(newInput);
                autocomplete(newInput, mails);
            });

            moinsVotant.addEventListener('click', () => {
                const parent = document.getElementById('user-votant-container');
                if(parent.childElementCount > 2){
                    parent.removeChild(parent.lastChild);
                }
            });

            plusResponsable.addEventListener('click', () => {
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.id = 'respReponse';
                newInput.placeholder = 'johndoe10@gmail.com';
                newInput.className = 'autocomplete-input';
                newInput.style.marginTop = '20px';
                document.getElementById('responsable-container').appendChild(newInput);
                autocomplete(newInput, mails);
            });

            moinsResponsable.addEventListener('click', () => {
                const parent = document.getElementById('responsable-container');
                if(parent.childElementCount > 2){
                    parent.removeChild(parent.lastChild);
                }
            });

            submit.addEventListener('click', () => {
                //Votants
                const votantContainer = document.querySelector('#user-votant-container');
                const listVotant = votantContainer.querySelectorAll('input#userVotant');
                var resVotant = "";
                for(var i=0; i<listVotant.length-1; i++){
                    resVotant += listVotant[i].value + ", ";
                }
                resVotant += listVotant[listVotant.length-1].value;
                const inputVotant = document.createElement('input');
                inputVotant.type = 'hidden';
                inputVotant.name = 'userVotant';
                inputVotant.value = resVotant;
                document.getElementById('end-form').appendChild(inputVotant);
                //Responsables
                const responsableContainer = document.querySelector('#responsable-container');
                const listResponsable = responsableContainer.querySelectorAll('input#respReponse');
                var resResponsable = "";
                for(var i=0; i<listResponsable.length-1; i++){
                    resResponsable += listResponsable[i].value + ", ";
                }
                resResponsable += listResponsable[listResponsable.length-1].value;
                const inputResponsable = document.createElement('input');
                inputResponsable.type = 'hidden';
                inputResponsable.name = 'respReponse';
                inputResponsable.value = resResponsable;
                document.getElementById('end-form').appendChild(inputResponsable);
                //Submit
                document.getElementById('form').submit();
            });
        </script>
    </div>
</form>