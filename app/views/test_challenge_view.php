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

<div id="exit_confirm" title="Abandonner le défi en cours ?">Si tu quittes le défi en cours tu n'auras aucune récompense et tu ne pourras pas non plus le recommencer aujourd'hui.</div>

<!-- Tuteur -->
<img alt="" id="tuteur_defi" src="<?= $joueur->portrait_tuteur(); ?>" />

<!-- Bulle du personnage si tuto non fini-->
<div id="bulle_defi" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"></span>
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
    <span class="ib l100">Défi : <?= $challenge->notion(); ?></span><span class="ib p1 i"><?= $challenge->getLevel_user($challenge->getCurrent_level(),$challenge->getTries()); ?></span>
  </div>

<div id="challenge_borders">
	<div id="challenge_content">
    	<!-- JS inserts content inside this div -->
    </div>
</div>


<!-- Timer -->
<span id="timer"></span>

<!-- Score -->
<div id="score_title">
    <span class="ib">Score :</span>
    <span class="ib g" id="score">0</span>
</div>

	<!-- Score & challengeId -->
    <form id="form_challenge" action="/app/controllers/test_challenge.php" method="post">
        <input type="hidden" name="score" value="" />
        <input type="hidden" name="level" value="" />
        <input type="hidden" name="challenge_id" value="<?= $challenge->getId(); ?>"  />
    </form>

<!-- Bonnes et mauvaises réponses -->
<img alt="Bonne réponse" class="icone_reponse" src="/webroot/img/icones/correct.png"/>
<div id="info_reponse">
</div>

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


<div id="challenge_choice">
    <select id="element" class="champ l100">
    	<option value="fire" selected="selected">Feu</option>
        <option value="water">Eau</option>
        <option value="wind">Vent</option>
        <option value="earth">Terre</option>
    </select>
    <select id="notion" class="champ l100">
    		<option class="fire" value="integers" selected="selected">Entiers</option>
        <option class="fire" value="decimals">Décimaux</option>
        <option class="fire" value="multiples">Multiples & Diviseurs</option>
        <option class="fire" value="fractions">Fractions</option>
        <option class="fire" value="greatNumbers">Les Grands Nombres</option>
        <option class="fire" value="divisions">Divisions Euclidiennes</option>
        <option class="fire" value="problemInterpretation">Interprétation de problèmes</option>

        <option class="water" value="proportionality">Proportionnalité</option>
        <option class="water" value="percentages">Pourcentages</option>
        <option class="water" value="tables">Tableaux</option>
        <option class="water" value="graphs">Graphiques</option>
        <option class="water" value="radars">Diagrammes radar</option>
        <option class="water" value="bars">Diagrammes en barres</option>
        <option class="water" value="circulars">Diagrammes circulaires</option>

        <option class="wind" value="lines">Droites, Segments et Demi-droites</option>
        <option class="wind" value="angles">Angles</option>
        <option class="wind" value="circles">Cercles</option>
        <option class="wind" value="quadrilaterals">Quadrilatères</option>
        <option class="wind" value="triangles">Triangles</option>
        <option class="wind" value="bisectors">Médiatrices et Hauteurs</option>
        <option class="wind" value="symmetries">Symétrie axiale</option>

        <option class="earth" value="lengths">Longueurs</option>
        <option class="earth" value="weights">Masses</option>
        <option class="earth" value="durations">Durées</option>
        <option class="earth" value="prices">Prix</option>
        <option class="earth" value="perimeters">Périmètres</option>
        <option class="earth" value="areas">Aires</option>
        <option class="earth" value="volumes">Volumes</option>
    </select>
    <select id="level" class="champ l100">
    	<option value="1" selected="selected">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>
    <select id="exercise" class="champ l100">
    	<option value="1" selected="selected">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
    </select>

    <div class="cache" id="challenge_element"><?= $challenge->getElement(); ?></div>
    <div class="cache" id="challenge_name"><?= $challenge->getName(); ?></div>
    <div class="cache" id="challenge_level"><?= $challenge->getCurrent_level(); ?></div>
    <div class="cache" id="challenge_tries"><?= $challenge->getTries(); ?></div>

</div>

<script src="/webroot/js/challenge.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/challenge_choice.js?nvd_r=xxx"></script>
<script src="/webroot/js/challenge_generic.js?nvd_r=xxx"></script>
<script src="/webroot/js/test_challenge.js?nvd_r=xxx"></script>

</body>
