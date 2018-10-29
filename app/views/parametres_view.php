<!-- Fond contenant le formulaire -->
<div class="fond l70 mh2 prolonge_moyen">



<div class="titre_petit">
	<span class="ib align_centre">
    	<span class="ib l100">Paramètres</span>
      <span class="ib l100 p1 i <?= $joueur->colorCompletionProfile(); ?>">-- Profil complété à <?= $joueur->percentageCompletionProfile(); ?>% --</span>
  </span>
</div>

<form action="/app/controllers/parametres.php<?= $get; ?>" method="post">
<!-- Début première page formulaire -->
<div id="partie1">

<div class="col50">
	<p>
      <label class="label">Nom :</label>
      <input class="<?= $joueur->fieldRequired('nom'); ?> input" autocomplete="off" type="text" title="Entre 3 et 30 caractères sans chiffres." name="nom" value="<?= htmlspecialchars($_POST["nom"]); ?>"/>
      <?php if(isset($msg["nom"])){echo '<span class="msg_erreur">'.$msg["nom"].'</span>';}?>
    </p>
	<p>
      <label class="label">Prénom :</label>
      <input class="<?= $joueur->fieldRequired('prenom'); ?> input" autocomplete="off" type="text" title="Entre 3 et 30 caractères sans chiffres." name="prenom" value="<?= htmlspecialchars($_POST["prenom"]); ?>"/>
      <?php if(isset($msg["prenom"])){echo '<span class="msg_erreur">'.$msg["prenom"].'</span>';}?>
	</p>
    <p>
      <label class="label">Ancien mdp :</label>
      <input class="champ input" autocomplete="off" type="password" title="Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-)." name="mdp" value="<?= htmlspecialchars($_POST["mdp"]); ?>"/>
      <?php if(isset($msg["mdp"])){echo '<span class="msg_erreur">'.$msg["mdp"].'</span>';}?>
	</p>
    <p>
      <label class="label">Nouveau mdp :</label>
      <input class="champ input" autocomplete="off" type="password" title="Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-)." name="mdp_new" value="<?= htmlspecialchars($_POST["mdp_new"]); ?>"/>
      <?php if(isset($msg["mdp_new"])){echo '<span class="msg_erreur">'.$msg["mdp_new"].'</span>';}?>
	</p>
    <p>
      <label class="label">Nouveau mdp (confirmation) :</label>
      <input class="champ input" autocomplete="off" type="password" title="Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-)." name="mdp2_new" value="<?= htmlspecialchars($_POST["mdp2_new"]); ?>"/>
      <?php if(isset($msg["mdp2_new"])){echo '<span class="msg_erreur">'.$msg["mdp2_new"].'</span>';}?>
	</p>
    <p>
    	<input class="ib settings" type="checkbox" id="volume_music" <?= $volume_music; ?>><label for="volume_music">Musiques</label>
      <input class="ib settings" type="checkbox" id="volume_sound_effects" <?= $volume_sound_effects; ?>><label for="volume_sound_effects">Bruitages Jeu</label>
      <input class="ib settings" type="checkbox" id="volume_interface" <?= $volume_interface; ?>><label for="volume_interface">Sons Interface</label>
		</p>
		<p>
	    <!-- <input class="ib settings" type="checkbox" id="activer_bulles_daides" <?= $bulles_daide; ?>><label for="activer_bulles_daides">Bulles d'aide</label> -->
	    <input class="ib settings" type="checkbox" id="advanced_spell_description" <?= $advanced_description; ?>><label for="advanced_spell_description">Description avancée des sorts</label>
    </p>
</div>

<div class="col50">
	<!--
    <p>
    <div class="label">
      <label>Tu es en :</label>
    </div>
    <div id="classe" class="input">
      <input type="radio" name="classe" id="6" value="6°"< ?php if($_POST["classe"]=="6°"){echo 'checked="checked"';}?>><label for="6">6°</label>
      <input type="radio" name="classe" id="5" value="5°"< ?php if($_POST["classe"]=="5°"){echo 'checked="checked"';}?>><label for="5">5°</label>
      <input type="radio" name="classe" id="4" value="4°"< ?php if($_POST["classe"]=="4°"){echo 'checked="checked"';}?>><label for="4">4°</label>
      <input type="radio" name="classe" id="3" value="3°"< ?php if($_POST["classe"]=="3°"){echo 'checked="checked"';}?>><label for="3">3°</label>
			<input type="radio" name="classe" id="teacher" value="Prof"< ?php if($_POST["classe"]=="Prof"){echo 'checked="checked"';}?>><label for="teacher">Prof</label>
      <input type="radio" name="classe" id="other" value="Autre"< ?php if($_POST["classe"]=="Autre"){echo 'checked="checked"';}?>><label for="other">Autre</label>
    </div>
    < ?php if(isset($msg["classe"])){echo '<span class="msg_erreur">'.$msg["classe"].'</span>';}?>
	</p> -->
    <p>
    <label for="departement" class="label">Ton département :</label>
    <input class="<?= $joueur->fieldRequired('departement'); ?> input" autocomplete="off" type="text" title="Choisis un département dans la liste." id="departement" name="departement" value="<?= htmlspecialchars($_POST["departement"]); ?>"/>
			<?php if(isset($msg["departement"])){echo '<span class="msg_erreur">'.$msg["departement"].'</span>';}?>
    </p>
    <p>
    <label for="nomCollege" class="label">Nom de ton collège :</label>
    <input class="<?= $joueur->fieldRequired('college'); ?> input" autocomplete="off" type="text" title="Choisis un collège dans la liste." id="nomCollege" name="college" value="<?= htmlspecialchars($_POST["college"]); ?>"/>
    	<?php if(isset($msg["college"])){echo '<span class="msg_erreur">'.$msg["college"].'</span>';}?>
    </p>
    <p>
      <label class="label">Email :</label>
      <input class="<?= $joueur->fieldRequired('email'); ?> input" autocomplete="off" type="text" title="Au format adresse email classique." name="email" value="<?= htmlspecialchars($_POST["email"]); ?>"/>
      <?php if(isset($msg["email"])){echo '<span class="msg_erreur">'.$msg["email"].'</span>';}?>
	</p>

	<?php if($joueur->classe() != "Prof"){ ?>
    <p>
      <label class="label">Email d'un parent :</font></label>
      <input class="<?= $joueur->fieldRequired('email_parent'); ?> input mb2" autocomplete="off" type="text" title="Au format adresse email classique." name="email_parent" value="<?= htmlspecialchars($_POST["email_parent"]); ?>"/>
      <?php if(isset($msg["email_parent"])){echo '<span class="mb8 ib l100 msg_erreur">'.$msg["email_parent"].'</span>';}?>
			<input type="checkbox" id="sameEmail" <?= $sameEmail; ?>><label for="sameEmail">Adresses email identiques</label>
      <?php /*
		  if($joueur->gameLimitation() == 1 && $joueur->email_parent() != "") {
			  echo "<p id='msgEmailParent' class='msg_erreur g ib l80'>Envoie un email à tes parents pour les inviter à prendre un Pass Navadra et repasser en mode illimité.</p>";
				$msgEmailParent = "Envoyer un email à mes parents";
		  } elseif($joueur->echeance_email_parent() == Joueur::EMAIL_PARENT_OK) {
				echo "<p id='msgEmailParent' class='msg_conf g ib l80'>Clique sur le bouton ci-dessous pour envoyer tes progrès par email à tes parents.</p>";
				$msgEmailParent = "Envoyer mes progrès";
		  }
		  if(isset($msgEmailParent)){
				echo("<p><div id='send_email_parent' class='actionGraph g'>".$msgEmailParent."</div></p>");
				echo('<p><img class="img_30" id="send_email_parent_loading" src="/webroot/img/icones/loading.gif"/></p>');
			} */
			?>
	</p>
	<?php } ?>

</div>
<?php
if(isset($msg_conf) && !isset($_GET["tab"])){
	echo "<span class='msg_conf g'>".$msg_conf."</span>";
}
?>
<?php if(isset($msg["pseudo"])){echo '<span class="msg_erreur">'.$msg["pseudo"].'</span>';}?>
<!-- Bouton pour revenir à l'index -->
<a class="gris" href="<?= $href_tab1; ?>"><div class="bouton form_gauche">Retour</div></a>
<a href="<?= $href_tab1; ?>"><img class="icone_form_gauche" src="/webroot/img/icones/btn_retour.png"></a>

<!-- Bouton pour passer à la deuxième partie du formulaire -->
<a class="blanc suivant" href="#"><div class="bouton form_droite2">Suivant</div></a>
<a href="#"><img id="jouer_icone" class="icone_form_droite2 suivant" src="/webroot/img/icones/play.png"></a>

<!-- Fin première page formulaire -->
</div>

<!-- Début deuxième page formulaire -->
<div id="partie2">
<div class="col50">

	<p>
      <label class="label">Pseudo :</label>
      <input class="champ input" autocomplete="off" type="text" title="Entre 3 et 15 caractères : lettres, chiffres, espace et certains caractères spéciaux (' -@_)." name="pseudo" value="<?= htmlspecialchars($_POST["pseudo"]); ?>"/>
	</p>
    <p>
    <div class="label">Cheveux :</div>
    <div class="input">
    	<span class="case_coul" id="cheveux_blond"></span>
        <span class="case_coul" id="cheveux_roux"></span>
        <span class="case_coul" id="cheveux_brun"></span>
        <span class="case_coul" id="cheveux_noir"></span>
    </div>
    </p>
    <p>
    <div class="label">Yeux :</div>
    <div class="input">
    	<span class="case_coul" id="yeux_bleu"></span>
        <span class="case_coul" id="yeux_vert"></span>
        <span class="case_coul" id="yeux_marron"></span>
        <span class="case_coul" id="yeux_noir"></span>
    </div>
    </p>
    <p>
    <div class="label">Peau :</div>
    <div class="input">
    	<span class="case_coul" id="peau_occ"></span>
        <span class="case_coul" id="peau_asi"></span>
        <span class="case_coul" id="peau_met"></span>
        <span class="case_coul" id="peau_noi"></span>
    </div>
    </p>
</div>
<div class="col50 mb1">
    <div class="input">
      	<div class="ib l10 align_milieu">
        <img id="chevron1" src="/webroot/img/icones/chevron1.png">
        </div>
        <div class="ib l70 align_milieu">
        <img id="modele_avatar" src="<?= $_POST["avatar_entier"]; ?>">
        <img class="cache" id="icone_chargement" src="/webroot/img/icones/loading.gif" />
        </div>
        <div class="ib l10 align_milieu">
        <img id="chevron2" src="/webroot/img/icones/chevron2.png">
        </div>
    </div>
    <input type="hidden" name="avatar_tete" value="<?= $_POST["avatar_tete"]; ?>">
    <input type="hidden" name="avatar_entier" value="<?= $_POST["avatar_entier"]; ?>">
</div>

<?php
if(isset($msg_conf) && isset($_GET["tab"])){
	echo "<span class='msg_conf g'>".$msg_conf."</span>";
}
?>

<!-- Bouton pour revenir à la première partie du formulaire -->
<a class="gris precedent" href="<?= $href_tab2; ?>"><div class="bouton form_gauche">Retour</div></a>
<a href="<?= $href_tab2; ?>"><img class="icone_form_gauche precedent" src="/webroot/img/icones/btn_retour.png"></a>


<!-- Bouton pour valider le formulaire -->
<input class="bouton form_droite" type="submit" name="valider" value="Sauvegarder" />
<a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>


<!-- Fin deuxième page formulaire -->
</div>

</form>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin du fond -->
</div>

<div id="confirmationWindow" class="bordure_sans_pos">
	<img class="titles" title="Fermer" id="closeConfirmationWindow" src="/webroot/img/icones/refuser.png">
	<span class="mh4 ib mg5 l40 mh1 mb1 align_gauche">Confirme ton email :</span>
	<span class="ib mg5 l40 mg5"><input class="champ l100 align_centre" type="text" autocomplete="off" name="confirmEmail" title="L'adresse doit correspondre à l'email que tu viens de renseigner." value="" /></span>
	<span class="mh4 ib mg5 l40 mh1 mb1 align_gauche">Confirme l'email de ton parent :</span>
	<span class="ib mg5 l40 mg5"><input class="champ l100 align_centre" type="text" autocomplete="off" name="confirmEmailParent" title="L'adresse doit correspondre à l'email que tu viens de renseigner." value="" /></span>
	<!-- Boutons pour valider le formulaire -->
	<div class="mh8">
		<a class="blanc confirmEmails" href="#"><div class="bouton form_droite2">Confirmer</div></a>
		<a href="#" class="confirmEmails"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
	</div>
</div>

<div id="info_input"></div>
<div id="missing_info" title="Champ(s) manquant(s)">Tu n'aurais pas essayé d'effacer des champs obligatoires par hasard ?</div>
<div id="wrong_info" title="Champ(s) incorrect(s)">Il y a au moins un champ qui ne respecte pas le bon format (on te l'a entouré en rouge).<br>Tu peux le modifier s'il te plait ?</div>
<div id="sameEmailInfo" title="Tu es sûr(e) ?">Si tu cliques sur "Oui", nous considérerons que l'adresse email renseignée est celle de tes parents.</div>
<div id="get_parameters" class="cache" title=""><?php if(isset($_GET["tab"])){echo $_GET["tab"];}?></div>
<?php if ($joueur->requireProfileCompletion()) {
	//echo('<div id="completeProfile" title="On a besoin de ton aide...">Avant de continuer ton aventure sur Navadra, peux-tu <span class="g">compléter les champs en jaune</span> s\'il te plait ?</div>');
}
/* elseif ($joueur->requireEmailCompletion($classrooms_manager->hasTimeSlot($joueur)) && (!isset($ok) || $ok == 0)) {
	echo('<div id="completeEmail" title="Renseigne ton adresse email"><span class="g">Quelle adresse email veux-tu renseigner?</span></div>');
} elseif ($joueur->requireEmailConfirmation($classrooms_manager->hasTimeSlot($joueur)) && (!isset($ok) || $ok == 0)) {
	if(!isset($_SESSION["activationLinkSent"])){
		$joueur->sendEmailActivationLink();
		$_SESSION["activationLinkSent"] = 1;
	}
	echo('<div id="confirmEmail" title="Confirme ton adresse email">Nous t\'avons envoyé un email à l\'adresse <span class="g">'.$joueur->email().'</span>.<br><br>Clique sur le lien à l\'intérieur pour valider ton adresse.</div>');
	echo('<div id="dontReceiveEmail" title="Problème reporté">Un administrateur va te contacter sur ton adresse email (<span class="g">'.$joueur->email().'</span>) pour valider cette dernière manuellement.</div>');
} */ ?>

<script src="/webroot/js/utils/autocomplete.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
<script src="/webroot/js/parametres.js?nvd_r=xxx"></script>
