<!-- Fond contenant le formulaire -->
<div class="fond l70 mh8 pb4">

<div class="titre">Oubli de mot de passe</div>
<form action="/app/controllers/oubli_mdp.php" method="post">
	<p>
      <label class="label mh4">Email :</label>
      <input class="champ input mh4" autocomplete="off" type="text" title="Au format adresse email classique." name="email" value="<?= htmlspecialchars($_POST["email"]); ?>"/>
	</p>
    <?php if(isset($msg_err)){echo '<span class="msg_erreur g">'.$msg_err.'</span>';}?>
    <?php if(isset($msg_conf)){echo '<span class="msg_conf g">'.$msg_conf.'</span>';}?>



<!-- Boutons pour valider le formulaire seulement si un mot de passe n'a pas été attribué-->
<?php if(!isset($msg_conf)) { ?>
<input class="bouton form_droite" type="submit" name="valider" value="Et zou !" />
<a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>
<?php } ?>

</form>
<!-- Fin du fond contenant le formulaire -->
</div>

<div id="info_input"></div>

<script src="/webroot/js/oubli_mdp.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
