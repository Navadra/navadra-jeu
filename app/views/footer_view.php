<!-- Bloc avatar -->
<img id="bloc_avatar" alt="" src="/webroot/img/icones/bloc_avatar.png">
<a href="<?= $link_profil; ?>">
	<div id="portrait_footer" class="portrait_container" title="<?= $titre_profil; ?>"></div>
</a>

<img id="fond_niveau_joueur" alt="" src="/webroot/img/icones/avatar_niveau.png">
<div id="niveau_joueur" class="g"><?= $joueur->niveau(); ?></div>

<div id="xp_joueur" class="cache" title="XP : <?= $pourcent_xp; ?>%"><?= $pourcent_xp; ?></div>

<!-- Barre menu gauche -->
<div id="barre_menu_gauche">
	<span id="pyrs_joueur">
	<!-- Pyrs de feu -->
    <span class="ib l8 md1 mh2">
			<a href="/app/controllers/grimoire.php?elem=feu">
        <span class="ib l100 align_centre"><img class="l60" alt="" src="/webroot/img/icones/pyrs_feu.png"></span>
        <span id="pyrs_feu" class="ib l100 mh15 g"><?= $joueur->pyrs_feu(); ?></span>
			</a>
    </span>
   	<!-- Pyrs d'eau -->
    <span class="ib l8 md1 mh2">
			<a href="/app/controllers/grimoire.php?elem=eau">
        <span class="ib l100 align_centre"><img class="l60" alt="" src="/webroot/img/icones/pyrs_eau.png"></span>
        <span id="pyrs_eau" class="ib l100 mh15 g"><?= $joueur->pyrs_eau(); ?></span>
			</a>
    </span>
    <!-- Pyrs du vent -->
    <span class="ib l8 md1 mh2">
			<a href="/app/controllers/grimoire.php?elem=vent">
        <span class="ib l100 align_centre"><img class="l60" alt="" src="/webroot/img/icones/pyrs_vent.png"></span>
        <span id="pyrs_vent" class="ib l100 mh15 g"><?= $joueur->pyrs_vent(); ?></span>
			</a>
    </span>
    <!-- Pyrs de terre -->
    <span class="ib l8 md1 mh2">
			<a href="/app/controllers/grimoire.php?elem=terre">
        <span class="ib l100 align_centre"><img class="l60" alt="" src="/webroot/img/icones/pyrs_terre.png"></span>
        <span id="pyrs_terre" class="ib l100 mh15 g"><?= $joueur->pyrs_terre(); ?></span>
			</a>
    </span>
    </span>
    <!-- Prestige -->
    <span id="prestige_joueur" class="ib l12 md15 mh2">
        <span class="ib l100"><a class="lien_menu" href="<?= $link_classement; ?>"><img id="icone_prestige" class="l80 align_centre" title="<?= $titre_classement; ?>" src="/webroot/img/icones/prestige.png"></a></span>
        <span id="prestige" class="ib l100 mh15 g"><?= $joueur->prestige(); ?></span>
    </span>
</div>

<!-- Barre menu droite -->
<div id="barre_menu_droite">
	<!-- Grimoire -->
    <span class="ib l10 md7 mh2">
        <span class="ib l100"><a id="link_grimoire" href="<?= $link_grimoire; ?>"><img id="icone_grimoire" class="l100" title="<?= $titre_grimoire; ?>" src="/webroot/img/icones/grimoire_normal<?=$nb; ?>.png"></a></span>
    </span>
    <!-- Liste combats -->
    <span class="ib l10 md7 mh2">
        <span class="ib l100"><a href="<?= $link_combats; ?>"><img id="icone_combats" class="l100" title="<?= $titre_combats; ?>" src="/webroot/img/icones/liste_combats_normal<?=$nb; ?>.png"></a></span>
    </span>
    <!-- Cinématiques Histoire
    <span class="ib l10 md4 mh2">
        <span class="ib l100"><a href="<?= $link_histoire; ?>"><img id="icone_histoires" class="l100" title="<?= $titre_histoire; ?>" src="/webroot/img/icones/histoires_normal<?=$nb; ?>.png"></a></span>
    </span>
    -->
    <!-- Messages -->
    <span class="ib l10 md7 mh2">
        <span class="ib l100"><a href="<?= $link_messages; ?>"><img id="icone_messages" class="l100" title="<?= $titre_messages; ?>" src="/webroot/img/icones/messages_normal<?=$nb; ?>.png"></a></span>
    </span>
    <!-- Contacts -->
    <span class="ib l10 md7 mh2">
        <span class="ib l100"><a href="<?= $link_contacts; ?>"><img id="icone_contacts" class="l100" title="<?= $titre_contacts; ?>" src="/webroot/img/icones/contacts_normal<?=$nb; ?>.png"></a></span>
    </span>
    <!-- Recherche -->
    <span class="ib l10 md7 mh2">
        <span class="ib l100"><a href="<?= $link_recherche; ?>"><img id="icone_recherche" class="l100" title="<?= $titre_recherche; ?>" src="/webroot/img/icones/recherche_normal<?=$nb; ?>.png"></span>
    </span>
</div>

<!-- Help -->
<?php if($joueur->tuto() == "fini" || $joueur->tuto() == "index_12"){ ?>
	<span id="div_boussole" class="ib l12">
		<img id="fond_boussole" class="l100" src="/webroot/img/icones/boussole_fond.png">
	    <a id="boussole" class="l6"><img class="l100" title="Aide" src="/webroot/img/icones/boussole.png"></a>
	</span>
<?php } ?>

<!-- Grey blur -->
<div id="warningBlur"></div>
<!-- Boites de dialogues -->
<div id="helpModeInfo" title="Mode Aide">Passe ta souris sur les éléments en couleur pour connaitre leur utilité.<br><br>Clique à nouveau sur la boussole pour fermer le Mode Aide.</div>
<div id="helpBubble"></div>

<!-- Notifications éventuelles -->
<?php if(isset($notif_parametres)){echo('<span class="petites_notifications cache" id="notif_parametres">'.$notif_parametres.'</span>');} ?>
<?php if(isset($notif_grimoire)){echo('<span class="notifications cache" id="notif_grimoire">'.$notif_grimoire.'</span>');} ?>
<?php if(isset($notif_combats)){echo('<span class="notifications cache" id="notif_combats">'.$notif_combats.'</span>');} ?>
<?php if(isset($notif_histoires)){echo('<span class="notifications cache" id="notif_histoires">'.$notif_histoires.'</span>');} ?>
<?php if(isset($notif_messages)){echo('<span class="notifications cache" id="notif_messages">'.$notif_messages.'</span>');} ?>
<?php if(isset($notif_ameliorations)){echo('<span class="notifications cache" id="notif_ameliorations">'.$notif_ameliorations.'</span>');} ?>

<?php if($joueur->tuto() == "fini"){ ?>
<!-- Améliorations / Bug reporting -->
<div id="feedback" class="bordure_sans_pos">
	<img id="fermer_feedback" src="/webroot/img/icones/refuser.png">

    <!-- Entete -->
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <div id="type_feedback" class="texte_centre">
              <input type="radio" id="ameliorations_realisees_btn" name="type_feedback"><label for="ameliorations_realisees_btn">Réponses de l'équipe Navadra</label>
              <input type="radio" id="ameliorations_en_cours_btn" name="type_feedback" checked="checked"><label for="ameliorations_en_cours_btn">Améliorations en attente</label>
              <input type="radio" id="soumettre_amelioration_btn" name="type_feedback"><label for="soumettre_amelioration_btn">Soumettre une amélioration</label>
              <input type="radio" id="soumettre_bug_btn" name="type_feedback"><label for="soumettre_bug_btn">Soumettre un bug</label>
            </div>
        </div>
    </div>

    <!-- Améliorations réalisées -->
    <div id="ameliorations_realisees" class="corps_scroll_feedback align_centre scroll_grand">
		<?php
		foreach($ameliorations_realisees as $amelioration) {
			$joueur_proposant = $manager->get($amelioration->id_joueur());
		?>
        <div class="ligne_scroll_feedback">
             <span id="votes_<?= $amelioration->id(); ?>" class="l10"><?= $amelioration->afficher_nb_votes(); ?></span>
             <span class="l70"><?= $joueur_proposant->pseudo()." : ".$amelioration->titre(); ?></span>
             <span class="l10"><img class="img_40" src="<?= $amelioration->iconAccepted(); ?>"/></span>
             <span class="cache"><?= $amelioration->descriptif(); ?></span>
         </div>
		 <?php
         }?>
    </div>

    <!-- Améliorations en cours -->
    <div id="ameliorations_en_cours" class="corps_scroll_feedback align_centre scroll_grand">
		<?php
		foreach($ameliorations_en_cours as $amelioration)
        {
			$joueur_proposant = $manager->get($amelioration->id_joueur());
			if(!in_array($joueur->id(), $amelioration->vues())) //Si le joueur n'a pas vu l'amélioration
				{$surbrillance = "fond_beige_clair";} //Met en évidence la ligne
			else
				{$surbrillance = "";}
			if($amelioration->votants() != "" && in_array($joueur->id(), $amelioration->votants())) //Si le joueur a déjà voté pour l'amélioration
				{$image = '<img id="'.$amelioration->id().'" class="img_40 img_vote vote" title="Tu as déjà voté pour cette idée." src="/webroot/img/icones/like_selec.png"/>';}
			elseif($joueur->id() == $amelioration->id_joueur()) //Si le joueur est celui qui a soumis l'amélioration
				{$image = '<img id="'.$amelioration->id().'" class="img_40 img_vote" title="Tu ne peux pas voter pour une idée que tu as soumise." src="/webroot/img/icones/like_pas_possible.png"/>';}
			else
				{$image = '<img id="'.$amelioration->id().'" class="img_40 img_vote vote" title="Tu peux voter pour cette idée." src="/webroot/img/icones/like.png"/>';}
		?>
        <div class="ligne_scroll_feedback <?= $surbrillance; ?>">
             <span id="votes_<?= $amelioration->id(); ?>" class="l10"><?= $amelioration->afficher_nb_votes(); ?></span>
             <span class="l70"><?= $joueur_proposant->pseudo()." : ".$amelioration->titre(); ?></span>
             <span class="l10"><?= $image; ?></span>
             <span class="cache"><?= $amelioration->descriptif(); ?></span>
         </div>
		 <?php
         }?>
    </div>

    <!-- Soumettre amélioration -->
    <div id="soumettre_amelioration" class="corps_scroll2 align_centre scroll_grand">
			<span class="ib mg10 l80 mh1 align_centre p1 g">Avant de poster une nouvelle idée, vérifie qu'elle ne se trouve pas déjà dans l'onglet "Améliorations en attentes" ou "Réponses de l'équipe Navadra".</span>
    	<span class="ib mg10 l20 mh1 mb1 align_gauche p3">Titre :</span>
      <span class="ib mg5 l50 md10"><input class="champ l100" type="text" autocomplete="off" name="titre_amelioration" title="Entre 10 et 50 caractères" value="" /></span>

			<span class="ib mg10 l90 mh1 mb1 align_gauche p3">Descriptif :</span>
      <span class="ib mg10 l80 md10 align_gauche h40"><textarea class="zone_texte h100" autocomplete="off" name="descriptif_amelioration" title="Entre 20 et 500 caractères"></textarea></span>

      <span id="confirm_amelioration" class="ib mg5 l90 md5 vert g mh1"></span>
    </div>

    <!-- Soumettre bug -->
    <div id="soumettre_bug" class="corps_scroll2 align_centre scroll_grand">
		<span class="ib mg10 l90 mh1 mb1 align_gauche p3">Descriptif :</span>
        <span class="ib mg10 l80 md10 align_gauche h60"><textarea class="zone_texte h100" autocomplete="off" name="descriptif_bug" title="Entre 20 et 2 000 caractères"></textarea></span>
        <span class="cache"><input type="hidden" name="page_courante" value="<?= $adresse; ?>" /></span>
        <span id="confirm_bug" class="ib mg5 l90 md5 g mh1"></span>
    </div>

    <!-- Boutons pour valider le formulaire -->
    <div id="valider_feedback">
    	<a class="blanc valider_feedback" href="#"><div class="bouton form_droite2">Envoyer</div></a>
		<a href="#" class="valider_feedback"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
    </div>

    <!-- Id joueur pour JS -->
    <div id="id_joueur" class="cache"><?= $joueur->id(); ?></div>
</div>


<?php if($joueur->gameLimitation() == 1) { ?>
<!-- End free period warning -->
<div id="warningMessage" class="bordure_sans_pos">

	<!-- Title -->
	<div class="mh2 g p4 ib l100 align_centre">Fin de la période illimitée</div>
	<div class="ib l100 align_centre mb1"><img class="img_80" src="/webroot/img/icones/fence.png" /></div>

	<!-- Body -->
  <div class="mg10 l80 mb10">
      <p>Tu as atteint le <span class='g'>niveau 5</span>, toutes nos félicitations ! Il semble que le jeu te plaise et on en est vraiment ravis :)</p>
			<p>Ton accès au jeu est à présent limité à <span class='g'>1 défi</span> et <span class='g'><?= $joueur->nbFightsRestricted(); ?> combat</span> chaque jour. Pour continuer à profiter pleinement du jeu, un "Pass Navadra" est nécessaire.</p>
			<p>Clique sur "Voir les Pass" pour voir les différents options disponibles.</p>
			<p class='i'>PS : Si tu as renseigné un email pour tes parents, ils ont automatiquement reçu un email les invitant à consulter tes progrès et les Pass Navadra.</p>
  </div>

	<!-- Hide buttons -->
	<a class="blanc hideMessage" href="#"><div class="bouton form_gauche">Plus tard</div></a>
	<a class="hideMessage" href="#"><img class="icone_form_gauche" src="/webroot/img/icones/play.png"></a>

  <!-- Open subscription formulas in a different tab -->
  <a class="blanc subscriptionFormulas" href="#"><div class="bouton form_droite2">Voir les Pass</div></a>
	<a href="#" class="subscriptionFormulas"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>

</div>

<?php }
} ?>

<audio preload="auto" id="son_clic">
	 <source src = "/webroot/sons/clic.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/clic.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_chargement">
	 <source src = "/webroot/sons/chargement.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/chargement.mp3" type="audio/mp3" />
</audio>

<script src="/webroot/js/utils/guidedTour.js?nvd_r=xxx"></script>

<script src="/webroot/js/footer.js?nvd_r=xxx"></script>

<?php if($joueur->tuto() != "fini") { ?>
    <audio preload="auto" id="son_tuto">
         <source src = "/webroot/sons/tuto.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/tuto.mp3" type="audio/mp3" />
    </audio>

    <script src="/webroot/js/tutoriel.js?nvd_r=xxx"></script>
<?php }
/*
if($joueur->bulles_daide_actives() == "oui" && $joueur->tuto() == "fini" && $joueur->nb_jours_fin_tuto() != 0) { ?>

	<div id="bulle_daide" class="bulle_daide"><?= $adresse; ?></div>

    <audio preload="auto" id="son_bulles_daide">
         <source src = "/webroot/sons/tuto.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/tuto.mp3" type="audio/mp3" />
    </audio>

    <script src="/webroot/js/bulles_daide.js?nvd_r=xxx"></script>

<?php } */ ?>
</body>
</html>
