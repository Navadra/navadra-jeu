<!-- Fond contenant le formulaire -->
<div id="connexionDiv" class="hiddenSmall fond l50 mh2 prolonge_moyen">

    <div class="titre">Connexion</div>
    <form id="connexionForm" action="/index.php" class="" method="post">
    <div class="col80 mh1">
				<span class="l100 ib p1 mb1">Uniquement pour ceux qui ont <span class="g">déjà</span> un compte.<br>Si tu n'en as pas, clique sur "S'inscrire".</span>
        <p>
          <label class="label pfun">Pseudo :</label>
          <input class="champ input" autocomplete="off" type="text" title="Entre 3 et 15 caractères." name="pseudo" value="<?= htmlspecialchars($_POST["pseudo"]); ?>"/>
        </p>
        <p>
          <label class="label pfun">Mot de passe :</label>
          <input class="champ input" autocomplete="off" type="password" title="Entre 6 et 30 caractères." name="mdp" value="<?= htmlspecialchars($_POST["mdp"]); ?>"/>
        </p>

        <?php if(isset($msg)){echo '<span class="msg_erreur">'.$msg.'</span>';}?>
        <p>
        <a href="/app/controllers/oubli_mdp.php" class="lien_souligne marron1" id="oubli">Je crois bien que j'ai oublié mon mot de passe</a>
        </p>
    </div>

    <!-- Boutons pour valider le formulaire -->
    <a class="gris" href="http://www.navadra.com/"><div class="bouton form_gauche">Retour</div></a>
    <a href="http://www.navadra.com/"><img class="icone_form_gauche" src="/webroot/img/icones/btn_retour.png"></a>

    <input class="bouton form_droite connect" type="submit" name="valider" value="C'est parti !" />
    <a href="#"><img class="icone_form_droite connect" src="/webroot/img/icones/play.png"></a>

    </form>

<!-- Fin du fond-->
</div>

<div id="mobileDisplay" class="visibleSmall fond l80 mh15 mbSmall prolonge_moyen">
	<div class="titre">Désolé...</div>
	<div class="col80 mh1">
			<span class="l100 ib p1 mb1"><span class="ib l100 g mb2">Navadra n'est pas encore disponible sur mobile et tablette !</span>Saisie ton email ci-dessous pour recevoir un rappel t'invitant à jouer quand tu seras sur un <span class="g">ordinateur</span>.</span>
			<p>
				<label class="label pfun">Email :</label>
				<input class="champ input mb4" autocomplete="off" type="text" title="Au format adresse email classique (sans espaces)." name="email" value=""/>
				<img id="loading" class="img_80" src="/webroot/img/icones/loading.gif" />
			</p>
			<span id="confirmReminder" class="g p1"></span>
	</div>

  <input class="bouton form_droite sendReminder" type="submit" name="valider" value="Envoyer" />
  <a href="#"><img class="icone_form_droite sendReminder" src="/webroot/img/icones/play.png"></a>
</div>

<div id="info_input"></div>

<a class="hiddenSmall blanc mh2" href="/app/controllers/inscription.php" id="sigsnUp"><div class="bouton std">S'inscrire</div></a>


<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
<script src="/webroot/js/connexion.js?nvd_r=xxx"></script>
