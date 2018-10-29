<!-- Fond contenant le formulaire -->
<div class="fond l70 mh2 prolonge_petit">

<div class="titre">Recherche</div>

<!-- Saisie des infos de recherche -->
<form action="/app/controllers/recherche.php" method="post">
<div class="col50">
	<p>
      <label class="label">Pseudo :</label>
      <input class="champ input" autocomplete="off" type="text" title="Entre 3 et 15 caractères." name="pseudo" value="<?= htmlspecialchars($_POST["pseudo"]); ?>"/>
	</p>
	<p>
      <label class="label">Nom :</label>
      <input class="champ input" autocomplete="off" type="text" title="Entre 3 et 30 caractères sans chiffres." name="nom" value="<?= htmlspecialchars($_POST["nom"]); ?>"/>
    </p>
	<p>
      <label class="label">Prénom :</label>
      <input class="champ input" autocomplete="off" type="text" title="Entre 3 et 30 caractères sans chiffres." name="prenom" value="<?= htmlspecialchars($_POST["prenom"]); ?>"/>
	</p>
</div>

<div class="col50">
    <p>
    <div id="sexe" class="ib">
      <input type="radio" name="sexe" id="fille" value="fille"<?php if($_POST["sexe"]=="fille"){echo 'checked="checked"';}?>><label for="fille">une fille</label>
      <input type="radio" name="sexe" id="gars" value="gars"<?php if($_POST["sexe"]=="gars"){echo 'checked="checked"';}?>><label for="gars">un gars</label>
    </div>
    <div id="classe" class="ib">
      <input type="radio" name="classe" id="6" value="6°"<?php if($_POST["classe"]=="6°"){echo 'checked="checked"';}?>><label for="6">6°</label>
      <input type="radio" name="classe" id="5" value="5°"<?php if($_POST["classe"]=="5°"){echo 'checked="checked"';}?>><label for="5">5°</label>
      <input type="radio" name="classe" id="4" value="4°"<?php if($_POST["classe"]=="4°"){echo 'checked="checked"';}?>><label for="4">4°</label>
      <input type="radio" name="classe" id="3" value="3°"<?php if($_POST["classe"]=="3°"){echo 'checked="checked"';}?>><label for="3">3°</label>
			<input type="radio" name="classe" id="teacher" value="Prof"<?php if($_POST["classe"]=="Prof"){echo 'checked="checked"';}?>><label for="teacher">Prof</label>
      <input type="radio" name="classe" id="other" value="Autre"<?php if($_POST["classe"]=="Autre"){echo 'checked="checked"';}?>><label for="other">Autre</label>
    </div>
    </p>
    <p>
    <label for="departement" class="label">Département :</label>
    <input class="champ input" autocomplete="off" type="text" id="departement" name="departement" title="Choisis un département dans la liste." value="<?= htmlspecialchars($_POST["departement"]); ?>"/>
    </p>
    <p>
    <label for="nomCollege" class="label">Collège :</label>
    <input class="champ input mb4" autocomplete="off" type="text" id="nomCollege" name="college" title="Choisis un collège dans la liste." value="<?= htmlspecialchars($_POST["college"]); ?>"/>
    </p>

    <!-- Boutons pour valider le formulaire -->
    <input class="bouton form_droite" id="launch_search" type="submit" name="valider" value="Rechercher" />
		<a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>
    </form>
</div>
<!-- Fin du fond blanc contenant le formulaire -->
<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
</div>

<!-- Affichage des résultats -->
<div class="bordure l70 mh2 centre extra_mghaut">
    <div class="entetes_scroll p1">
    	<div class="ligne_scroll">
            <span class="l20">Pseudo</span>
            <span class="l20">Nom</span>
            <span class="l20">Prénom</span>
            <span class="l5">Sexe</span>
            <span class="l5">Classe</span>
            <span class="l27">Collège</span>
        </div>
    </div>
    <div class="corps_scroll align_centre scroll_moyen">
		<?php
		if(isset($joueurs))
		{
			foreach($joueurs as $joueur_cible)
			{
				if($joueur->id() == $joueur_cible->id())
					{$surbrillance = "fond_beige_clair";} //Met en évidence la ligne du joueur
				else
					{$surbrillance = "";}
				if($joueur->contacts() == "" || !in_array($joueur_cible->id(), $joueur->contacts())) //Si le joueur du profil n'est pas dans les contacts du joueur actif
				{
					$action = "ajouter";
				}
				else
				{
					$action = "supprimer";
				}
				?>
			 <div id="<?= $joueur_cible->id(); ?>" class="ligne_scroll <?= $surbrillance.' '.$action; ?>">
				 <span class="l20"><?= $joueur_cible->pseudo(); ?></span>
				 <span class="l20"><?= $joueur_cible->nom(); ?></span>
				 <span class="l20"><?= $joueur_cible->prenom(); ?></span>
				 <span class="l5"><img class="img_20" src="<?= $joueur_cible->icone_sexe(); ?>"/></span>
				 <span class="l5"><?= $joueur_cible->classe(); ?></span>
				 <span class="l27 p0"><?= $joueur_cible->college(); ?></span>
			 </div>
			 <?php
			 }
			 $nb_joueurs = sizeof($joueurs);
			 if($nb_joueurs == 0)
			 {
				 echo '<span class="mh6 p3 gris">Aucun joueur trouvé, réessaie avec d\'autres critères !</span>';
			 }
		}
		?>
    </div>
</div>


<?php
if(isset($nb_joueurs) &&  $nb_joueurs== 1)
{
	echo '<div id="nb_resultats" class="resultats_recherche mh1">'.$nb_joueurs.' joueur trouvé.</span>';
}
elseif(isset($nb_joueurs) &&  $nb_joueurs> 1)
{
	echo '<div id="nb_resultats" class="resultats_recherche mh1">'.$nb_joueurs.' joueurs trouvés.</span>';
}
?>

<div id="controles_contact" class="bulle_daide absolu">
    <img id="voir_contact" class="img_40 ib" src="/webroot/img/icones/oeil.png">
    <img id="ajouter_contact" class="img_40 ib" src="/webroot/img/icones/plus.png">
    <img id="supprimer_contact" class="img_40 ib" src="/webroot/img/icones/refuser.png">
    <img id="envoyer_message" class="img_40 ib" src="/webroot/img/icones/messages_normal.png">
</div>

<div id="id_joueur_actif" class="cache"><?= $joueur->id(); ?></div>
<div id="info_input"></div>

<script src="/webroot/js/utils/autocomplete.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
<script src="/webroot/js/recherche.js?nvd_r=xxx"></script>
