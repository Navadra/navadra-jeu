<div id="screen_challenge">
    <span class="ib l100 pfun align_centre mh10 mb2">
    	<span id="challengeNotion" class="ib l100 p7 g"><?= $challenge->notion(); ?></span>
    	<span class="ib l100 p3 g i"><?= $challenge->getLevel_user($challenge->getCurrent_level(),$challenge->getTries()); ?></span>
    </span>
    <span class="ib l100 align_centre mb4"><img class="l5" src="<?= $challenge->element_img(); ?>" /></span>
    <span class="ib l100 align_centre p5 mb4" id="loading_msg"><?= $challenge->loading_msg(); ?></span>
    <span class="ib l100 align_centre"><img id="resume_challenge" title="C'est parti !" class="l8 cache" src="/webroot/img/icones/resume.png" /></span>
</div>

<div id="correctionScreen">
	<span class="align_middle l100">
    <span id="firstPart" class="l100 ib align_centre">
			<span id="currentQuestion" class="relatif"></span>
		</span>
		<span id="secondPart" class="col50 align_centre">
			<span class="ib l100 p7 g pfun mb2">Rappel du cours</span>
			<span id="explanations"></span>
    </span>
	</span>
	<span id="commandsChallenge" class="align_centre">
		<span id='showExplanations' class='actionGraph p2 g mh2 md2 ib align_centre'>Rappel cours</span>
	  <span id="hideCorrection" class='actionGraph p2 g ib align_centre'>Reprendre</span>
	</span>
</div>

<div id="exit_confirm" title="Quitter l'entraînement ?">Veux-tu vraiment arrêter l'entraînement et revenir à l'île ?</div>

<!-- Tuteur -->
<img alt="" id="tuteur_defi" src="<?= $mentor; ?>" />

<!-- Bulle du personnage si tuto non fini-->
<div id="bulle_defi" class="bulle">
  <!-- le texte dans la bulle -->
  <span id="txt_bulle"></span>
</div>


<div id="bouton_retour">
  <a href="<?= $return_page; ?>"><img title="Retour" alt="" src="/webroot/img/icones/btn_retour.png"/></a>
</div>

<!-- Parchment background -->
<div id="background_parchment">
<table cellspacing="0" cellpadding="0">
<tr>
<td>
<img src="/webroot/img/challenges/parchemin.jpg">
</td>
</tr>
</table>
</div>


<div class="frame_without_background l100 h100">

  <div class="title_challenges">
  	<span class="ib l5"><img class="l100" src="<?= $challenge->element_img(); ?>" /></span>
        <span class="ib md5 align_centre">
            <span class="ib l100"><?= $challenge->notion(); ?></span>
            <span class="ib l100 p1 i"><?= $challenge->getLevel_user($challenge->getCurrent_level(),$challenge->getTries()); ?></span>
    	</span>
    </span>
  </div>

  <div id="challenge_borders">
    <div id="challenge_content">
      <!-- JS inserts content inside this div -->
    </div>
  </div>


    <!-- Timer
    <span id="timer"></span> -->

      <!-- Score -->
    <div id="score_title">
        <span class="ib">Score :</span>
        <span class="ib g" id="score">0</span>
        <span class="ib mg4">Question n°</span>
        <span class="ib g" id="questionCount">0</span>
        <span class="ib" id="questionTot"></span>
    </div>

  <!-- Bonnes et mauvaises réponses -->
    <img alt="Bonne réponse" class="icone_reponse" src="/webroot/img/icones/correct.png"/>
    <div id="info_reponse">
    </div>

    <!-- Score & challengeId -->
    <form id="form_challenge" action="/app/controllers/entrainement.php" method="post">
        <input type="hidden" name="score" value="" />
        <input type="hidden" name="level" value="" />
        <input type="hidden" name="challenge_id" value="<?= $challenge->getId(); ?>"  />
        <input type="hidden" name="total_duration" value="" />
    </form>


    <div class="cache" id="challenge_element"><?= $challenge->getElement(); ?></div>
    <div class="cache" id="challenge_name"><?= $challenge->getName(); ?></div>
    <div class="cache" id="challenge_level"><?= $challenge->getCurrent_level(); ?></div>
    <div class="cache" id="challenge_tries"><?= $challenge->getTries(); ?></div>


    <!-- Sons -->
    <audio preload="auto" id="son_bonne_rep">
         <source src = "/webroot/sons/bonne_rep.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/bonne_rep.mp3" type="audio/mp3" />
    </audio>

    <audio preload="auto" id="son_mauvaise_rep">
         <source src = "/webroot/sons/mauvaise_rep.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/mauvaise_rep.mp3" type="audio/mp3" />
    </audio>

</div>

<!-- Améliorations / Bug reporting -->
<div id="feedback" class="bordure_sans_pos">
	<img id="fermer_feedback" src="/webroot/img/icones/refuser.png">

    <!-- Soumettre bug -->
    <div id="soumettre_bug" class="corps_scroll2 align_centre scroll_grand">
		<span class="ib mg10 l90 mh1 mb1 align_gauche p3">Descriptif :</span>
        <span class="ib mg10 l80 md10 align_gauche h60"><textarea class="zone_texte h100" autocomplete="off" name="descriptif_bug" title="Entre 20 et 2 000 caractères"></textarea></span>
        <span class="cache"><input type="hidden" name="page_courante" value="<?= $adresse; ?>" /></span>
        <span id="confirm_bug" class="ib mg5 l90 md5 vert g mh1"></span>
    </div>

    <!-- Boutons pour valider le formulaire -->
    <div id="valider_feedback">
    	<a class="blanc valider_feedback" href="#"><div class="bouton form_droite2">Envoyer</div></a>
		<a href="#" class="valider_feedback"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
    </div>

</div>

<script src="/webroot/js/challenge.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/challenge_choice.js?nvd_r=xxx"></script>
<script src="/webroot/js/challenge_generic.js?nvd_r=xxx"></script>
<script src="/webroot/js/entrainement.js?nvd_r=xxx"></script>

	<div id="bulle_daide" class="bulle_daide"><?= $adresse; ?></div>

    <audio preload="auto" id="son_bulles_daide">
         <source src = "/webroot/sons/tuto.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/tuto.mp3" type="audio/mp3" />
    </audio>

</body>
