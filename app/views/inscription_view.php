<!-- Fond contenant le formulaire -->
<div class="fond l70 mh2 prolonge_moyen hiddenSmall">

<div class="titre">Inscription</div>

<form action="/app/controllers/inscription.php<?= $parametersForm; ?>" method="post">
<!-- Début première page formulaire -->
<div id="partie1">

<div class="col50">
	 <p>
      <label class="label">Pseudo :</label>
      <input class="champ input" autocomplete="off" type="text" title="Entre 3 et 15 caractères : lettres, chiffres, espace et certains caractères spéciaux (' -@_)"
             name="pseudo" value="<?= htmlspecialchars($_POST["pseudo"]); ?>"/>
	 </p>
    <p>
      <label class="label">Mot de passe :</label>
      <input class="champ input" autocomplete="off" type="text" title="Entre 6 et 30 caractères : lettres, chiffres et ponctuation (.!?_-)."
             name="mdp" value="<?= htmlspecialchars($_POST["mdp"]); ?>"/>
      <?php if(isset($msg["mdp"])){echo '<span class="msg_erreur">'.$msg["mdp"].'</span>';}?>
	</p>

</div>

<div class="col50">
    <p>
    <div class="label">
      <label>Tu es en :</label>
    </div>
    <div id="classe" class="input">
      <input type="radio" name="classe" id="6" value="6°"<?php if($_POST["classe"]=="6°"){echo 'checked="checked"';}?>><label for="6">6°</label>
      <input type="radio" name="classe" id="5" value="5°"<?php if($_POST["classe"]=="5°"){echo 'checked="checked"';}?>><label for="5">5°</label>
      <input type="radio" name="classe" id="4" value="4°"<?php if($_POST["classe"]=="4°"){echo 'checked="checked"';}?>><label for="4">4°</label>
      <input type="radio" name="classe" id="3" value="3°"<?php if($_POST["classe"]=="3°"){echo 'checked="checked"';}?>><label for="3">3°</label>
			<input type="radio" name="classe" id="teacher" value="Prof"<?php if($_POST["classe"]=="Prof"){echo 'checked="checked"';}?>><label for="teacher">Prof</label>
    </div>
    <?php if(isset($msg["classe"])){echo '<span class="msg_erreur">'.$msg["classe"].'</span>';}?>
	</p>
  <p>
      <label class="label">Code Prof :</label>
      <input class="champ input" autocomplete="off" type="text" title="Demandez le à votre webmaster." name="code_prof" value="<?= htmlspecialchars($_POST["code_prof"]); ?>"/>
      <?php if(isset($msg["code_prof"])){echo '<span class="msg_erreur">'.$msg["code_prof"].'</span>';}?>
	</p>
  <p>
      <label class="label">Email :</label>
      <input class="champ input" autocomplete="off" type="text" title="Au format adresse email classique (sans espaces)." name="email" value="<?= htmlspecialchars($_POST["email"]); ?>"/>
      <?php if(isset($msg["email"])){echo '<span class="msg_erreur">'.$msg["email"].'</span>';}?>
	</p>

  <p>
    <label class="label">Code classe :</label>
    <?php
    if(!isset($codeClassroom)) $codeClassroom = "";
		if(!isset($codeSponsor)) $codeSponsor = "";
		if(!isset($codeSponsor)) $codeSponsor = "";
    ?>
    <input class="champ input" autocomplete="off" type="text" title="Les 4 caractères en majuscule du code de ta classe" name="codeClassroom" value="<?= $codeClassroom ?>"/>
    <?php if(isset($msg["codeClassroom"])){echo '<span class="msg_erreur">'.$msg["codeClassroom"].'</span>';}?>
  </p>
  <p>
    <input type="checkbox" name="noCode" id="noCode" checked="checked"><label for="noCode">J'ai le code de ma classe</label>
	</p>

	<input type="hidden" name="codeSponsor" value="<?= $codeSponsor; ?>"/>
</div>

<?php if(isset($msg["pseudo"])){echo '<span class="msg_erreur g">'.$msg["pseudo"].'</span>';}?>

<!-- Bouton pour revenir à l'index -->
<a class="gris" href="/index.php"><div class="bouton form_gauche">Retour</div></a>
<a href="/index.php"><img class="icone_form_gauche" src="/webroot/img/icones/btn_retour.png"/></a>

<!-- Bouton pour passer à la deuxième partie du formulaire -->
<a class="blanc suivant" href="#"><div class="bouton form_droite2">Suivant</div></a>
<a href="#"><img id="jouer_icone" class="icone_form_droite2 suivant" src="/webroot/img/icones/play.png"/></a>

<!-- Fin première page formulaire -->
</div>


<!-- Début deuxième page formulaire -->
<div id="partie2">
<div class="col50">
    <p>
    <div class="label">
      <label>Tu es :</label>
    </div>
    <div id="sexe" class="input">
      <input type="radio" name="sexe" id="fille" value="fille"<?php if($_POST["sexe"]=="fille"){echo 'checked="checked"';}?>><label for="fille">une fille</label>
      <input type="radio" name="sexe" id="gars" value="gars"<?php if($_POST["sexe"]=="gars"){echo 'checked="checked"';}?>><label for="gars">un gars</label>
    </div>
    <?php if(isset($msg["sexe"])){echo '<span class="msg_erreur">'.$msg["sexe"].'</span>';}?>
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
<div class="col50">
    <div class="input">
      	<div class="ib l10 align_milieu devant">
        <img id="chevron1" src="/webroot/img/icones/chevron1.png"/>
        </div>
        <div class="ib l70 align_milieu derriere">
        <img id="modele_avatar" src="<?= $_POST["avatar_entier"]; ?>"/>
        <img class="cache" id="icone_chargement" src="/webroot/img/icones/loading.gif" />
        </div>
        <div class="ib l10 align_milieu devant">
        <img id="chevron2" src="/webroot/img/icones/chevron2.png"/>
        </div>
    </div>
    <input type="hidden" name="avatar_tete" value="<?= $_POST["avatar_tete"]; ?>">
    <input type="hidden" name="avatar_entier" value="<?= $_POST["avatar_entier"]; ?>">
</div>

<!-- Bouton pour revenir à la première partie du formulaire -->
<a class="gris precedent" href="#"><div class="bouton form_gauche">Retour</div></a>
<a href="#"><img class="icone_form_gauche precedent" src="/webroot/img/icones/btn_retour.png"></a>


<!-- Bouton pour valider le formulaire -->
<input class="bouton form_droite" type="submit" name="valider" value="C'est parti !" />
<a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>


<!-- Fin deuxième page formulaire -->
</div>


</form>

<!-- Fin du fond -->
</div>

<!-- Grey blur -->
<div id="warningBlur"></div>

<div id="confirmationWindow" class="bordure_sans_pos">
	<img class="titles" title="Fermer" id="closeConfirmationWindow" src="/webroot/img/icones/refuser.png">
	<span class="mh4 ib mg5 l40 mh1 mb1 align_gauche">Confirme ton email :</span>
	<span class="ib mg5 l40 mg5"><input class="champ l100 align_centre" type="text" autocomplete="off" name="confirmEmail" title="L'adresse doit correspondre à l'email que tu viens de renseigner." value="" /></span>
	<!-- Boutons pour valider le formulaire -->
	<div class="mh8">
		<a class="blanc confirmEmails" href="#"><div class="bouton form_droite2">Confirmer</div></a>
		<a href="#" class="confirmEmails"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
	</div>
</div>

<div id="mobileDisplay" class="visibleSmall fond l80 mh15 mb15 prolonge_moyen">
	<div class="titre">Désolé...</div>
	<div class="col80 mh1">
			<span class="l100 ib p1 mb1"><span class="ib l100 g mb2">Navadra n'est pas encore disponible sur mobile et tablette !</span>Saisie ton email ci-dessous pour recevoir un rappel t'invitant à jouer quand tu seras sur un <span class="g">ordinateur</span>.</span>
			<p>
				<label class="label pfun">Email :</label>
				<input class="champ input mb4" autocomplete="off" type="text" title="Au format adresse email classique (sans espaces)." name="emailReminder" value=""/>
				<img id="loading" class="img_80" src="/webroot/img/icones/loading.gif" />
			</p>
			<span id="confirmReminder" class="g p1"></span>
	</div>

  <input class="bouton form_droite sendReminder" type="submit" name="valider" value="Envoyer" />
  <a href="#"><img class="icone_form_droite sendReminder" src="/webroot/img/icones/play.png"></a>
</div>

<div id="info_input"></div>

<!-- Dialog de confirmation -->
<div id="confirm_question_secrete" title="Tu es sûr(e) ?" class="cache"></div>
<div id="missing_info" title="Champ(s) manquant(s)">Promis, on a essayé de mettre le moins de champs à remplir possible.<br>En revanche, on a vraiment besoin que tu remplisses tous ceux là !</div>
<div id="missing_class" title="Et ta classe ?">On dirait que tu as oublié de renseigner ta classe. Tu peux remédier à ça s'il te plait ?</div>
<div id="missing_sex" title="Garçon ou Fille ?">On dirait que tu as oublié de renseigner si t'étais un garçon ou une fille. Tu peux remédier à ça s'il te plait ?</div>
<div id="wrong_info" title="Champ(s) incorrect(s)">Il y a au moins un champ qui ne respecte pas le bon format (on te l'a entouré en rouge).<br>Tu peux le modifier s'il te plait ?</div>

<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
<script src="/webroot/js/inscription.js?nvd_r=xxx"></script>
