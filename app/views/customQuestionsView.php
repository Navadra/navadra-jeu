<div id="screen_challenge">
    <span class="ib l100 pfun align_centre mh10 mb2">
    	<span class="ib l100 p7 g">Questions personnalisées</span>
    	<span class="ib l100 p3 g i">-- Niveau Secret --</span>
    </span>
    <span class="ib l100 align_centre mb4"><img class="l5" src="/webroot/img/elements/4_elements.png" /></span>
    <span class="ib l100 align_centre p5 mb4" id="loading_msg">Exploration d'un passage secret...</span>
    <span class="ib l100 align_centre"><img id="resume_challenge" title="C'est parti !" class="l8 cache titles" src="/webroot/img/icones/resume.png" /></span>
</div>
<div id="exit_confirm" title="Quitter le niveau secret ?">N'oublie pas l'URL de cette page si tu veux y accéder à nouveau...</div>

<!-- Tuteur -->
<img alt="" id="tuteur_defi" src="<?= $joueur->portrait_tuteur(); ?>" />

<!-- Bulle du personnage si tuto non fini-->
<div id="bulle_defi" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle">Tiens, c'est quoi cet endroit ? Ça à l'air cool !</span>
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

<!-- Full screen div -->
<div class="frame_without_background l100 h100">

  <div class="title_challenges">
    <span class="ib l100">Questions personnalisées</span><span class="ib p1 i">-- Niveau Secret --</span>
  </div>

	<div id="challenge_borders">
		<div id="challenge_content">
			<!-- Div for question creation -->
			<div id="selectMode" class="mg30">
				<div id='selectCreation' class='actionButtonLarge align_centre g mh4 p1 relatif'>Créer tes propres<br>questions</div>
				<div id='selectTest' class='actionButtonLarge align_centre g mh8 p1 relatif'>Répondre aux questions d'autres joueurs</div>
			</div>

			<!-- Div for question creation -->
			<div id="questionCreation">
				<label for="categoryChoice" class="p2 g md2">Choisis une catégorie pour ta question :</label>
				<select name="categoryChoice" id="categoryChoice" class="l20 p3">
						<option>Maths</option>
						<option>Physique-Chimie</option>
						<option>SVT</option>
						<option>Techno-Info</option>
						<option>Histoire-Géo</option>
						<option>Français</option>
						<option>Anglais</option>
						<option>Espagnol</option>
						<option>Allemand</option>
						<option>Sports</option>
						<option>Musique</option>
						<option>Loisirs</option>
				</select>
				<div class="mh4 align_gauche mg20">
					<label class="ib g p3 md2">Question :</label>
					<textarea id="question" autocomplete="off" class="champ ib titles l50" name="question" title="Entre 3 et 200 caractères"></textarea>
				</div>
				<div class="mh2 align_gauche mg20">
					<label class="ib g p2 md2 vert">Choix n°1 (juste) :</label>
					<input id="answer" class="champ ib align_gauche titles choice" autocomplete="off" type="text" title="Entre 1 et 50 caractères." name="answer"/>
				</div>
				<div class="mh2 align_gauche mg20">
					<label class="ib g p2 md2 jaune">Choix n°2 (faux) :</label>
					<input id="choice2" class="champ ib align_gauche titles choice" autocomplete="off" type="text" title="Entre 1 et 50 caractères." name="answer"/>
				</div>
				<div class="mh2 align_gauche mg20">
					<label class="ib g p2 md2 jaune">Choix n°3 (faux) :</label>
					<input id="choice3" class="champ ib align_gauche titles choice" autocomplete="off" type="text" title="Entre 1 et 50 caractères." name="answer"/>
				</div>
				<div class="mh2 align_gauche mg20">
					<label class="ib g p2 md2 jaune">Choix n°4 (faux) :</label>
					<input id="choice4" class="champ ib align_gauche titles choice" autocomplete="off" type="text" title="Entre 1 et 50 caractères." name="answer"/>
				</div>
				<div id='createQuestion' class='actionGraph ib g mh4 p2'>Créer la question</div>
				<div id='switchTest' class='actionGraph ib i mh4'>Passer en mode 'Test'</div>
				<img id="loadingCreation" class="img_80 mh2" src="/webroot/img/icones/loading.gif"/>
				<div id="confirmationMsg" class="mh2 g p4"></div>
			</div>

			<!-- Div for question creation -->
			<div class='mh2 p2 questionTest'>
				<label for="categoryChoiceTest" class="p2 g md2">Choisis une catégorie de questions :</label>
				<select name="categoryChoiceTest" id="categoryChoiceTest" class="l20 p3">
						<option>--</option>
						<option>Toutes</option>
						<option>Maths</option>
						<option>Physique-Chimie</option>
						<option>SVT</option>
						<option>Techno-Info</option>
						<option>Histoire-Géo</option>
						<option>Français</option>
						<option>Anglais</option>
						<option>Espagnol</option>
						<option>Allemand</option>
						<option>Sports</option>
						<option>Musique</option>
						<option>Loisirs</option>
				</select>

				<div id="showMoreTest">
					<div class='mh2'>
						<span class="ib p1 i">Question de :</span>
						<span id='authorQuestion' class="ib p3"></span>
						<span id='authorPortrait' class="ib"></span>
					</div>

					<div id='newQuestion' class='p4 g mh2'></div>

					<div id='challenge_buttons' class='mh4'>
						<span id='answer1' class='p2 md6 g'></span>
						<span id='answer2' class='p2 md6 g'></span>
						<span id='answer3' class='p2 md6 g'></span>
						<span id='answer4' class='p2 g'></span>
					</div>
				</div>

				<div id='switchCreation' class='actionGraph ib i mh6 p0'>Passer en mode 'Création'</div>
			</div>


	  </div>
	</div>

	<div class="questionTest">
		<!-- Timer -->
		<span id="timer"></span>

		<!-- Score -->
		<div id="score_title">
				<span class="ib">Score :</span>
				<span class="ib g" id="score">0</span>
		</div>

		<!-- Bonnes et mauvaises réponses -->
		<img alt="Bonne réponse" class="icone_reponse" src="/webroot/img/icones/correct.png"/>
		<div id="info_reponse"></div>
	</div>

</div>


<div id="incorrectQuestion" title="Pas si vite...">Il y a au moins un champ qui n'est pas rempli correctement (on te l'a entouré en rouge).</div>
<div id="emptyCategory" title="Désolé...">Personne n'a encore créé de question dans cette catégorie.<br>Pourquoi ne créerais-tu pas la première ?</div>

<!-- Sons -->
<audio preload="auto" id="son_bonne_rep">
	 <source src = "/webroot/sons/bonne_rep.ogg" type="audio/ogg" />
		 <source src = "/webroot/sons/bonne_rep.mp3" type="audio/mp3" />
</audio>

<audio preload="auto" id="son_mauvaise_rep">
	 <source src = "/webroot/sons/mauvaise_rep.ogg" type="audio/ogg" />
		 <source src = "/webroot/sons/mauvaise_rep.mp3" type="audio/mp3" />
</audio>

<script src="/webroot/js/customQuestions.js?nvd_r=xxx"></script>

</body>
