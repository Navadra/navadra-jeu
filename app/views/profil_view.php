<!-- Fond -->
<div id="tabs_profile" class="fond_sans_bordures l70 mh1 extra_mh pb1 bordures_epaisses_<?= $joueur_profil->element_couleur(); ?>">
	<ul>
    	<li><a href="#general">Général</a></li>
    	<li><a href="#challenges">Défis</a></li>
    	<li><a href="#achievements">Trophées</a></li>
      <li><a href="#sponsoring">Parrainage</a></li>
			<?php
			if($modeAdmin) echo '<li><a href="#admin">Commandes Admin</a></li>';
			if($modeTeacher) echo '<li><a href="#teacher">Commandes Prof</a></li>';
			if($modeStudent) echo '<li><a href="#classroom">Ma Classe</a></li>';
			?>
	</ul>
	<div id="general">
        <!-- Titre -->
        <div class="titre_gauche">
            <span class="l10 ib align_centre"><img id="element_player_profile" class="h100" src="<?= $joueur_profil->element_img(); ?>"></span>
            <span class="ib p5 align_gauche md2"><?= $joueur_profil->pseudo(); ?></span>
            <?php if(isset($playerTitle)){ ?>
            <span class="ib p2 align_gauche">-- <?= $playerTitle->name(); ?> --</span>
            <?php } ?>
            <span class="ib l40 p0 align_centre">Dernière connexion : <?= $joueur_profil->derniere_connexion(); ?></span>
        </div>

        <!-- Image Avatar -->
        <div class="l20 mh1 ib relatif">
        	<?php if($joueur_profil->id() == $joueur->id()) { ?>
            <a href="/app/controllers/parametres.php?tab=avatar"><img id="avatar_profil" class="titles" title="Changer d'avatar" src="<?= $joueur_profil->avatar_entier(); ?>"></a>
            <?php } else { ?>
            <img id="avatar_profil" src="<?= $joueur_profil->avatar_entier(); ?>">
            <?php } ?>
            <img id="fond_avatar" src="<?= $joueur_profil->profile_background(); ?>">
        </div>


        <div id="level_player_profile" class="cache"><?= $joueur_profil->niveau(); ?></div>
        <div id="prestige_player_profile" class="cache"><?= $joueur_profil->prestige(); ?></div>

        <!-- PARTIE DROITE -->
        <div class="l70 mh1 ib align_haut">

            <!-- TROPHEES -->
            <div id="trophees_board" class="ib l100">
                <span class="sous_titre ib l100">Derniers trophées</span>
                <span class="block_profile l90">
									<?php
									$countTrophy = 0;
									foreach($trophy_player as $trophy) {
									$countTrophy ++;
									if($countTrophy <= 4){ ?>
										<span class="ib align_centre l15 mh2 mg4 md4">
											<img class="img_50 titles trophyIcon" src="<?= $trophy->icon(); ?>" title="<?= $trophy->getName().' : '.strtolower($trophy->dateObtention()); ?>" />
										</span>
									<?php }
									} ?>
                </span>
            </div>

            <!-- TABLEAU DE CHASSE -->
            <div id="hunt_board" class="ib l100 mh2">
                <span class="sous_titre ib l100">Derniers combats</span>
                <span class="block_profile l90">
                <?php foreach($last_fights as $fight) {
										$monstre = $fight->copie_monstre(); ?>
                    <span id="monster_<?= $monstre->id(); ?>" class="relatif ib align_centre l15 mh2 mg4 md4">
                    	<img class="monster_img l100 titles" title="<?= $monstre->nom(); ?> - Niv.<?= $monstre->niveau(); ?>" src="<?= $monstre->img_tete(); ?>"/>
                    	<img class="fond_niveau_monstre" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png" />
                    	<span class="ib niveau_monstre"><?= $monstre->niveau(); ?></span>
                    	<img class="absolu img_30 issue_icon" src="/webroot/img/icones/<?= $fight->issue(); ?>.png"/>
                    </span>
                <?php } ?>
                </span>
            </div>

            <!-- FAVORITES TEAMATES -->
            <div id="teamates_board" class="ib l100 mh2">
                <span class="sous_titre ib l100">Coéquipiers favoris</span>
                <span class="block_profile l90">
                <?php
								if(is_array($teamates)){
									foreach($teamates as $id_team_player => $number_fights) {
												$team_player = $manager->get($id_team_player); ?>
	                    	<a class="ib l10 mg2 md2" href='/app/controllers/profil.php?id=<?= $team_player->id(); ?>'>
													<img id="head<?= $team_player->id();?>" class="l100" title="<?= $team_player->pseudo();?> - Niv.<?= $team_player->niveau();?>" src="<?= $team_player->full_portrait();?>">
												</a>
	                <?php }
								}?>
                </span>
            </div>

        </div>

	</div>

	<div id="challenges">
    <?php
    if($joueur->id() == $joueur_profil->id())
    { ?>
    	<div id="elements_challenges" class="ib l100 align_centre pfun">
        	<span id="fire_challenges" class="ib l20">
            	<img class="ib l30" src="/webroot/img/elements/feu.png"/>
                <span class="ib l100 g rouge mh2">Nombres et<br />calculs</span>
            </span>
            <span id="water_challenges" class="ib l20">
            	<img class="ib l30" src="/webroot/img/elements/eau.png"/>
                <span class="ib l100 g bleu mh4">Gestion de données et<br />fonctions</span>
            </span>
            <span id="wind_challenges" class="ib l20">
            	<img class="ib l30" src="/webroot/img/elements/vent.png"/>
                <span class="ib l100 g jaune mh4">Espace et<br />géométrie</span>
            </span>
            <span id="earth_challenges" class="ib l20">
            	<img class="ib l30" src="/webroot/img/elements/terre.png"/>
                <span class="ib l100 g vert mh4">Grandeurs et<br />mesures</span>
            </span>
    	</div>

        <!-- Challenges display -->
        <div id="list_challenges" class="bordure centre l95 mh4">
            <div class="corps_scroll align_centre scroll_grand">
            		<div id="userHint" class="ligne_scroll vertical_align g p2 mh8">
                    	-- Clique sur un élément pour afficher ta progression --
                    </div>
                <?php
                foreach($challenges as $challenge)
                {
                    if($challenge->getTries() > 0){
											$link = "/app/controllers/entrainement.php?id=".$challenge->getId();
										} else {
											$link = "#";
										}
										?>
                    <a class="gris link_training" id="<?= $challenge->getId(); ?>" name="<?= $challenge->getName(); ?>" notion="<?= $challenge->notion(); ?>" element="<?= $challenge->getElement(); ?>" href="<?= $link; ?>">
                    <div class="ligne_scroll <?= $challenge->getElement(); ?>_challenges">
                         <span class="l35 p1 g <?= $challenge->element_class_color(); ?> pfun challenge_notions"><?= $challenge->notion(); ?></span>
                         <span class="l50 titles" title="<?= $challenge->level_title(); ?>">
													 	<?php
                            for($i=1;$i<=min(5,$challenge->getCurrent_level());$i++) {
                         			echo('<img class="img_30" src="/webroot/img/icones/medal.png"/>');
														}
														if($challenge->getCurrent_level() == 0){
															echo('<img class="img_40" src="/webroot/img/icones/question_mark.png"/>');
														}?>
                         </span>
                         <span class="l10"><img class="<?= $challenge->ultimate_img_size(); ?> ultimate_mastery titles" title="<?= $challenge->ultimate_title(); ?>" src="<?= $challenge->ultimate_img(); ?>"/></span>
                     </div>
                     </a>
                 <?php
                 } ?>
            </div>
        </div>
        <div class="l100 align_centre mh1 p0 g">-- Clique sur une ligne pour t'entrainer à ce défi --</div>

        <div id="challenge_progress_graph">
        	<div id="container_progress_graph"></div>
            <div id="options_progress_graph"></div>
        </div>
    <?php
    } else {
		echo('<div class="l100 align_centre mh8 p1 g">-- Tu ne peux pas voir les progrès des autres --</div>');
	} ?>
    </div>

  <div id="achievements">
    <div class="titre_petit"><?=$trophy_title?></div>

    <div id="liste_trophies" class="bordure centre l95 mh1">
      <div class="entetes_scroll">
        <div class="ligne_scroll">
          <!--span class="l10">Date</span-->
          <span class="l10">Type</span>
          <span class="l40">Nom</span>
          <span class="l45">Description</span>
        </div>
      </div>
      <div class="corps_scroll align_centre scroll_grand">
        <?php foreach($trophy_player as $trophy) { ?>
        <a class="gris" href="#">
          <div id="trophy_<?= $trophy->getId(); ?>" class="ligne_scroll ph05 pb05 g">
            <!--span class="l10">< ?= $trophy->getObtained(); ? ></span-->
            <span class="l10"><img class="img_50 titles" src="<?= $trophy->icon(); ?>" title="<?= $trophy->iconTitle(); ?>" /></span>
            <span class="l40 rouge_fonce p1 g titles" title="<?= $trophy->dateObtention(); ?>"><?= $trophy->getName(); ?></span>
            <span class="l45 align_gauche titles" title="<?= $trophy->dateObtention(); ?>"><?= $trophy->getDesc(); ?></span>
          </div>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>

  <div id="sponsoring">
        <?php
				if($joueur->admin() && $joueur->id() == $joueur_profil->id()){
					//SPONSORED BY
					echo('<div class="ib l100 mh2 p5 g pfun">'.$sponsor_title.'</div>');
			    echo('<div class="ib l100">');
						echo('<div id="categoryInvitation" class="mh2">');
							echo('<input name="categoryInvitation" type="radio" id="teacherSoloLabel" value="Prof"><label for="teacherSoloLabel">Prof</label>');
				    	echo('<input name="categoryInvitation" type="radio" id="teacherClassLabel" value="Classe d\'un prof"><label for="teacherClassLabel">Classe d\'un prof</label>');
				      echo('<input name="categoryInvitation" type="radio" id="parentLabel" value="Parent"><label for="parentLabel">Parent</label>');
							echo('<input name="categoryInvitation" type="radio" id="studentLabel" value="Collégien"><label for="studentLabel">Collégien</label>');
							echo('<input name="categoryInvitation" type="radio" id="curiousLabel" value="Curieux"><label for="curiousLabel">Curieux</label>');
							echo('<input name="categoryInvitation" type="radio" id="facebookLabel" value="Facebook"><label for="facebookLabel">Facebook</label>');
							echo('<input name="categoryInvitation" type="radio" id="twitterLabel" value="Twitter"><label for="twitterLabel">Twitter</label>');
							echo('<input name="categoryInvitation" type="radio" id="exhibitionLabel" value="Salon"><label for="exhibitionLabel">Salon</label>');
							echo('<input name="categoryInvitation" type="radio" id="otherLabel" value="Autre"><label for="otherLabel">Autre</label>');
						echo('</div>');
						echo('<div class="mh2 mg20 align_gauche">');
							echo('<label class="ib g p3 md2">Nombre d\'invités max :</label>');
			      	echo('<input id="maxInvitations" class="champ ib l10 titles align_centre" autocomplete="off" type="text" title="Juste un nombre..." name="maxInvitations"/>');
						echo('</div>');
						echo('<div class="mh2 mg20 align_gauche">');
							echo('<label class="ib g p3 md2">Description :</label>');
							echo('<input id="descriptionInvitation" class="champ ib l40 titles align_gauche" autocomplete="off" type="text" title="Entre 5 et 100 caractères." name="descriptionInvitation"/>');
						echo('</div>');
						echo("<div id='generateCode' class='actionGraph g mh2 p2'>Créer un code</div>");
					echo('</div>');
					//INVITEES
					echo('<div class="ib l100 mh4 p5 g pfun">'.$sponsored.':</div>');
		      echo('<div class="ib l100">');
						echo('<span id="newCode" class="l100 g p7 mh2 mb6 ib">...</span>');
					echo('</div>');
			    echo('<div id="invitees_sentence" class="l100 ib g p4 mh2 '.$color_sentence.'">'.$invitees_sentence.'</div>');
			} else { ?>
				<div id="sponsorFlexbox" class="ib l100 mh2">
					<div class="ib l23">
						<div class="ib l100 p5 g pfun mb2">Parrain :</div>
						<?php if(is_string($player_sponsor)){
								echo('<img id="sponsor_avatar" class="img_100 titles" title="Invité'.$ep.' par l\'équipe de Navadra : la grande classe !" src="/webroot/img/logo.png">');
						} else {
								echo('<a href="/app/controllers/profil.php?id='.$player_sponsor->id().'"><img id="sponsor_avatar" class="img_100 titles" title="'.$player_sponsor->pseudo().' - Niv.'.$player_sponsor->niveau().'" src="'.$player_sponsor->full_portrait().'"></a>');
						} ?>
					</div>
					<div class="ib l50">
						<div class="ib l100 p7 g pfun">Parrainage</div>
						<div class="ib l100 p5 g pfun mb2"><span class="<?= $color_sentence; ?>"><?= $invitees_left; ?></span> invitation(s) restante(s)</div>
						<div class="relatif">
							<img id="treasureMapProfile" class="l60" src="/webroot/img/icones/treasureMap.png">
							<div id="invitees" class="absolu l100">
								<?php foreach($player_invitees as $invitee){
									echo('<a class="mg2 ib md2 playerIcons" href="/app/controllers/profil.php?id='.$invitee->id().'"><img class="img_100 titles ib" title="'.$invitee->pseudo().' - Niv.'.$invitee->niveau().'" src="'.$invitee->full_portrait().'"></a>');
								}
								for($i=1;$i<=$invitees_left;$i++) {
									echo('<img class="img_100 titles mg2 ib md2 playerIcons" title="Futur(e) aventurier(e)" src="/webroot/img/cadres_avatars/cadres_ronds/unknown.png">');
								} ?>
							</div>
						</div>
						<?php if($joueur->id() == $joueur_profil->id() && $invitees_left > 0){ ?>
							<div id='inviteFriend' class='actionGraph g mh4 p2'>Invite un ami</div>
							<div id="inviteWindow" class="bordure_sans_pos">
								<img id="closeInviteWindow" src="/webroot/img/icones/refuser.png">
								<span class="mh4 ib mg5 l40 mh1 mb1 align_gauche p3">Email de ton ami :</span>
					      <span class="ib mg5 l40 mg5"><input class="champ l100" type="text" autocomplete="off" name="emailFriend" title="Au format email classique" value="" /></span>
								<span class="ib l100"><img id="loadingInvitation" class="img_50" src="/webroot/img/icones/loading.gif"></span>
								<span id="confirmationInvite" class="mh2 ib l80 mg10 g p1"></span>
								<!-- Boutons pour valider le formulaire -->
						    <div id="sendInvitation" class="mh8">
						    	<a class="blanc sendInvitationBtn" href="#"><div class="bouton form_droite2">Envoyer</div></a>
									<a href="#" class="sendInvitationBtn"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
						    </div>
							</div>
						<?php } elseif($joueur->id() == $joueur_profil->id() && $codes_manager->rewardObtained($joueur) == 0) {
							echo("<div id='discoverMonster' class='actionGraph g mh4 p2'>Monstre repéré !</div>");
							echo("<div id='confirmationMonster' class='mh4 p1'></div>");
						}?>
					</div>
					<div id="invitees_sentence" class="ib l23 p5 pfun g"><?= $invitees_sentence; ?></div>
				</div>
			<?php } ?>
  </div>

	<?php	if($modeAdmin){ ?>
	<div id="admin">
  	<div class="ib l100 mh2 p5 g pfun mb6">Commandes Admin</div>
		<div class="ib l100">
			<a href="/app/controllers/admin.php"><div class='actionGraph g p2'>Interface Admin</div></a>
		</div>
		<div class="ib l100 mh4">
			<a href="/app/controllers/dashboard.php"><div class='actionGraph g p2'>Dashboard</div></a>
    </div>
		<div class="ib l100 mh4">
			<a href="/app/controllers/colleges.php"><div class='actionGraph g p2'>Liste collèges</div></a>
    </div>
	</div>
	<?php } if($modeTeacher){ ?>
	<div id="teacher">
		<div class="ib l100 mh2 p5 g pfun mb2">Commandes Prof</div>
		<div class="ib l100 mh2 g p2">
			Créez vos classes pour jouer en illimité avec vos élèves durant les heures de cours.
    </div>
		<div class="ib l100 mh2">
			<a class="ib md20 l17" href="/app/controllers/manage_classes.php"><div class='actionGraph g p2'>Gérer mes classes</div></a>
			<a class="ib l17" href="/app/controllers/manage_timeslots.php"><div class='actionGraph g p2'>Gérer mes créneaux</div></a>
		</div>
		<div class="ib l100 mh8 g p2">
			Suivez ensuite les progrès et la régularité de vos élèves grâce à une interface dédiée.
    </div>
		<div class="ib l100 mh2">
			<a class="ib l17" href="/app/controllers/students_progress.php"><div class='actionGraph g p2'>Suivi de mes élèves</div></a>
		</div>
	</div>
	<?php } elseif($modeStudent) {
	echo('<div id="classroom">');
	if($classroom == "NoClassroom"){
		echo('<div class="ib l100 mh2 p5 g pfun mb6">Rejoindre une classe</div>');
		echo('<div class="mg20 align_gauche">');
			echo('<label class="ib g p3 md2">Code (donné par ton professeur) :</label>');
			echo('<input id="classroomCode" class="champ ib l20 titles align_centre" autocomplete="off" type="text" title="Demande son code à ton prof." name="classroomCode"/>');
		echo('</div>');
		echo("<div id='joinClassroom' class='actionGraph g mh4 p2'>Rejoindre la classe</div>");
		echo("<img id='loadingClassroom' class='img_120 mh4' src='/webroot/img/icones/loading.gif' />");
		echo("<div id='joinClassroomMsg' class='l100 ib g p4 mh4 orange'>Si ton professeur ne connaît pas encore Navadra,<br>il est temps de lui en parler ! <img class='img_30' src='/webroot/img/icones/wink.png' /></div>");
	} else {
		$teacherTemp = $manager->get($classroom->idTeacher());
		echo('<div class="ib l100 mh1 p4 g pfun">'.$classroom->name().' du Collège '.$joueur->college().' ('.$joueur->departement().')</div>');
		echo('<div class="ib l100 p3 g pfun"><a href="/app/controllers/profil.php?id='.$teacherTemp->id().'"><img class="l8 team_portraits" title="Professeur : '.$teacherTemp->pseudo().' - Niv.'.$teacherTemp->niveau().'" src="'.$teacherTemp->full_portrait().'" /></a></div>');
		echo('<div class="ib l100 p3 g pfun mh1 mb1">Joueurs de ta classe</div>');
		echo('<div class="bordure l95 centre align_centre">');
		    echo('<div class="entetes_scroll">');
		    	echo('<div class="ligne_scroll p1">');
		            echo('<span class="l10">Portrait</span>');
		            echo('<span class="l23">Pseudo</span>');
								echo('<span class="l23">Prénom</span>');
		            echo('<span class="l17">Niveau</span>');
		            echo('<span class="l17">Prestige</span>');
		      echo('</div>');
		    echo('</div>');
		    echo('<div id="listClassrooms" class="corps_scroll align_centre scroll_moyenPlus p2">');
				foreach($classroom->idStudents() as $idStudent) {
					$studentTemp = $manager->get($idStudent);
					echo('<a href="/app/controllers/profil.php?id='.$idStudent.'">');
					echo('<div class="ligne_scroll students">');
					 echo('<span class="l10"><img class="l60 team_portraits" title="'.$studentTemp->pseudo().' - Niv.'.$studentTemp->niveau().'" src="'.$studentTemp->full_portrait().'" /></span>');
					 echo('<span class="l23">'.$studentTemp->pseudo().'</span>');
					 echo('<span class="l23">'.$studentTemp->prenom().'</span>');
					 echo('<span class="l17">'.$studentTemp->niveau().'</span>');
					 echo('<span class="l17">'.$studentTemp->prestige().'</span>');
				 echo('</div>');
			 	 echo('</a>');
			   }
		    echo('</div>');
		echo('</div>');
	}
	echo('</div>');
	} ?>
<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin du fond -->
</div>

<!-- Tableau Elementaire -->
<table border="0" id="elem_descr" class="cache bulle_daide">
	<tr>
		<th scope="col" class="p1 l50">Élément du monstre</th>
		<th scope="col" class="p1 l50">Dégâts reçus</th>
	</tr>
	<tr>
		<td><img class="img_30" src="/webroot/img/elements/feu.png"/></td>
		<td><?= $joueur_profil->surligner_bonus_elem($joueur_profil->facteur_elem_def("feu"), "def"); ?></td>
	</tr>
	<tr>
		<td><img class="img_30" src="/webroot/img/elements/eau.png"/></td>
		<td><?= $joueur_profil->surligner_bonus_elem($joueur_profil->facteur_elem_def("eau"), "def"); ?></td>
	</tr>
	<tr>
		<td><img class="img_30" src="/webroot/img/elements/vent.png"/></td>
		<td><?= $joueur_profil->surligner_bonus_elem($joueur_profil->facteur_elem_def("vent"), "def"); ?></td>
	</tr>
	<tr>
		<td><img class="img_30" src="/webroot/img/elements/terre.png"/></td>
		<td><?= $joueur_profil->surligner_bonus_elem($joueur_profil->facteur_elem_def("terre"), "def"); ?></td>
	</tr>
</table>

<div id="error_training" title="Patience...">Tu pourras commencer à t'entrainer à ce défi une fois que <?= $joueur->tuteur(); ?> te l'aura présenté.</div>
<div id="confirm_mastery" title="Tu veux tenter ta chance ?">Pour réussir la Maîtrise Ultime, tu ne devras faire AUCUNE faute sur les 10 questions du défi.<br />Tu n'as le droit qu'à un seul essai par jour.<br />Tu confirmes que tu es prêt<?= $feminin; ?> ?</div>
<div id="confirm_training" title="Tu veux t'entraîner ?">Un peu d'entraînement permet d'être affuté<?= $feminin; ?> pour les vraies épreuves.<br />On y va ?</div>

 <?php
if(isset($trophees))
{
	echo('<div id="trophees">');
	if($trophees["nb_coupes_or"] > 0)
	{
		echo('<img id="coupe_or" class="img_60" alt="" src="/webroot/img/icones/victoire.png">');
	}
	if($trophees["nb_coupes_argent"] > 0)
	{
		echo('<img id="coupe_argent" class="img_60" alt="" src="/webroot/img/icones/victoire_argent.png">');
	}
	if($trophees["nb_coupes_bronze"] > 0)
	{
		echo('<img id="coupe_bronze" class="img_60" alt="" src="/webroot/img/icones/victoire_bronze.png">');
	}
	if($trophees["meilleur_classement"] > 3)
	{
		echo('<img id="fond_meilleur_classement" class="img_60" alt="" src="/webroot/img/icones/fond_classement.png">');
	}
	echo('</div>');
	if($trophees["nb_coupes_or"] > 0)
	{
		echo('<span class="g blanc absolu" id="nb_or">'.$trophees["nb_coupes_or"].'</span>');
		echo('<span class="bulle_daide absolu" id="descriptif_or">'.$trophees["descriptif_or"].'</span>');
	}
	if($trophees["nb_coupes_argent"] > 0)
	{
		echo('<span class="g blanc absolu" id="nb_argent">'.$trophees["nb_coupes_argent"].'</span>');
		echo('<span class="bulle_daide absolu" id="descriptif_argent">'.$trophees["descriptif_argent"].'</span>');
	}
	if($trophees["nb_coupes_bronze"] > 0)
	{
		echo('<span class="g blanc absolu" id="nb_bronze">'.$trophees["nb_coupes_bronze"].'</span>');
		echo('<span class="bulle_daide absolu" id="descriptif_bronze">'.$trophees["descriptif_bronze"].'</span>');
	}
	if($trophees["meilleur_classement"] > 3)
	{
		echo('<span class="g blanc absolu p2" id="meilleur_classement">'.$trophees["meilleur_classement"].'°</span>');
		echo('<span class="bulle_daide absolu" id="descriptif_meilleur_classement">'.$trophees["descriptif_classement"].'</span>');
	}
}
?>

<!-- Div utilisées par JS -->
<div class="cache" id="elem1"><?= $elem1; ?></div>
<div class="cache" id="elem2"><?= $elem2; ?></div>
<div class="cache" id="elem3"><?= $elem3; ?></div>
<div class="cache" id="elem4"><?= $elem4; ?></div>
<div class="cache" id="id_joueur_profil"><?= $joueur_profil->id(); ?></div>
<div class="cache" id="landing_tab"><?php if(isset($_GET["tab"])){echo($_GET["tab"]);}?></div>
<div id="info_input"></div>
<!-- Icones pour ajouter à ses contacts ou envoyer un message si ce n'est pas le profil du joueur connecté -->
<?php
if($joueur->id() != $joueur_profil->id())
{
	echo('<a href="/app/controllers/messages.php?id='.$joueur_profil->id().'"><img id="envoi_msg" class="absolu cache img_70" title="Envoyer un message" src="/webroot/img/icones/message_joueur.png"/></a>');
	if($joueur->contacts() == "" || !in_array($joueur_profil->id(), $joueur->contacts())) //Si le joueur du profil n'est pas dans les contacts du joueur actif
	{
		echo('<img id="ajouter_contact" class="absolu cache img_70" title="Ajouter aux contacts" src="/webroot/img/icones/ajouter_contact.png"/>');
	}
}
?>

<audio preload="auto" id="sound_challenge_ultime_mastery">
	 <source src = "/webroot/sons/challenge_ultime_mastery.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/challenge_ultime_mastery.mp3" type="audio/mp3" />
</audio>

<script src="/webroot/js/utils/math.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/spells.js?nvd_r=xxx"></script>
<script src="/webroot/js/profil.js?nvd_r=xxx"></script>
