<div id="bouton_retour2">
  <a href="/app/controllers/profil.php?id=<?= $joueur->id(); ?>&tab=teacher"><img title="Retour" alt="" src="/webroot/img/icones/btn_retour.png"/></a>
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

	<?php if($joueur->departement() == "" || $joueur->college() == ""){
		echo ('<div class="mg20 l60 texte_centre align_middle">');
		echo ('<div class="titleNoBack mb2 g">Renseignez d\'abord votre département et votre collège</div>');
		echo ('</div>');
		echo ('<img id="showParameters" class="absolu img_120" src="/webroot/img/icones/fleche4.png" />');
	} elseif($joueur->email_confirme() == 0){
		$joueur->sendEmailActivationLink();
		echo ('<div class="mg20 l60 texte_centre align_middle">');
		echo ('<div class="titleNoBack mb2 g">Un email vient de vous être envoyé pour confirmer votre adresse.<br><br>Une fois votre adresse email confirmée, vous pourrez accéder à l\'interface professeur.</div>');
		echo ('</div>');
	} else { ?>

  <div class="titleNoBack g">Gérer mes créneaux Navadra</div>
	<div class="pfun p3 mb1 g">Collège <?= $joueur->college(); ?> (<?= $joueur->departement(); ?>)</div>
	<div class="l90 centre">

		<div class="bordure l100 pb05">
			<div class="entetes_scroll p3 g mb1">
				<div id="actionTitle" class="">Ajouter un créneau</div>
			</div>

			<input autocomplete="off" type="hidden" id="timeslotId" name="timeslotId" value="0"/>

			<div class="ib l100">
				<label for="classroom" class="g ib md1">Classe :</label>
				<select name="classroom" id="classroom" class="champ ib l10 texte_centre">
					<?php foreach($classrooms as $classroom) {
						echo('<option value="'.$classroom->id().'" name="'.$classroom->name().'" >'.$classroom->name().'</option>');
					} ?>
				</select>

				<label class="g ib mg4 md1" for="day">Jour :</label>
				<input class="ib champ l8 texte_centre md4" type="text" id="day" name="day">

				<label class="g ib md1" for="startTime">Heure de début :</label>
				<input class="champ ib l8 texte_centre md4" autocomplete="off" type="text" title="Au format hh:mm<br>Fuseau horaire: France (GMT+1)" id="startTime" name="startTime"/>

				<label class="g ib md1" for="endTime">Heure de fin :</label>
				<div class="ib l8 md4 texte_centre titles" title="Les créneaux durent obligatoirement 1h." id="endTime"></div>
			</div>

			<div class="ib l100 mh1">
				<label for="notion1" class="g ib md1">Notion 1 :</label>
				<input notion="" class="champ ib l17 md4 texte_centre notionField" autocomplete="off" type="text" id="notion1" name="notion1"/>

				<label for="notion2" class="g ib md1">Notion 2 :</label>
				<input notion="" class="champ ib l17 md4 texte_centre notionField" autocomplete="off" type="text" id="notion2" name="notion2"/>

				<label for="notion3" class="g ib md1">Notion 3 :</label>
				<input notion="" class="champ ib l17 md4 texte_centre notionField" autocomplete="off" type="text" id="notion3" name="notion3"/>
			</div>

			<div class="l100 ib mh1">
				<img class="img_50" id="loading" src="/webroot/img/icones/loading.gif"/>
			</div>
			<input type="checkbox" id="cancel" class="ib"><label for="cancel">Annuler saisie</label>
			<div id='validate' class='actionGraph g mh1 p1'>Enregistrer</div>
			<input type="checkbox" id="delete" class="ib"><label for="delete">Supprimer créneau</label>
			<div class="l100 ib vert g centrer p1 mh1" id="confirmation"></div>
		</div>

		<div class="bordure l100 mh1 centre">
		    <div class="entetes_scroll p1">
		    	<div class="ligne_scroll">
		            <span class="l12">Classe</span>
		            <span class="l10">Jour</span>
		            <span class="l15">Horaire</span>
		            <span class="l20">Notion 1</span>
								<span class="l20">Notion 2</span>
								<span class="l20">Notion 3</span>
		        </div>
		    </div>
		    <div id="listTimeslots" class="corps_scroll align_centre scroll_moyenPlus p1">
				<?php
				if(count($timeslots) > 0) {
					foreach($timeslots as $timeslot) {
						$classroom = $classrooms_manager->getById($timeslot->idClassroom());
						$day = strftime('%d/%m/%Y', strtotime($timeslot->startTime()));
						$hour = "De ".strftime('%H:%M', strtotime($timeslot->startTime()))." à ".strftime('%H:%M', strtotime($timeslot->endTime()));
						$challenge1 = new Challenge(array("name" => $timeslot->notion1()));
						$challenge1Name = $challenge1->notion();
						$challenge2 = new Challenge(array("name" => $timeslot->notion2()));
						$challenge2Name = $challenge2->notion();
						$challenge3 = new Challenge(array("name" => $timeslot->notion3()));
						$challenge3Name = $challenge3->notion();
						?>
					 <div id="<?= $timeslot->id(); ?>" class="ligne_scroll timeslots pb05 ph05">
						 <span class="l12"><?= $classroom->name(); ?></span>
						 <span class="l10"><?= $day; ?></span>
						 <span class="l15"><?= $hour ?></span>
						 <span class="l20"><?= $challenge1Name; ?></span>
						 <span class="l20"><?= $challenge2Name; ?></span>
						 <span class="l20"><?= $challenge3Name; ?></span>
					 </div>
					 <?php }
				} else {
					echo '<span class="mh6 gris">Vous n\'avez pas de créneau Navadra à venir.</span>';
				}
				?>
		    </div>
		</div>

		<div class="ib l100 mh2 g p2">
			Le jeu est illimité pour vos élèves pendant les créneaux que vous avez définis.
    </div>

	</div>

	<ul id="menuChallenges">
	  <li><div>Nombres et Calculs</div>
	    <ul>
				<?php foreach($challengesFire as $challenge) {
					echo('<li><div notion="'.$challenge->getName().'" class="notion">'.$challenge->notion().'</div></li>');
				} ?>
	    </ul>
	  </li>
		<li><div>Gestion de données et Fonctions</div>
	    <ul>
				<?php foreach($challengesWater as $challenge) {
					echo('<li><div notion="'.$challenge->getName().'" class="notion">'.$challenge->notion().'</div></li>');
				} ?>
	    </ul>
	  </li>
		<li><div>Espace et Géométrie</div>
	    <ul>
				<?php foreach($challengesWind as $challenge) {
					echo('<li><div notion="'.$challenge->getName().'" class="notion">'.$challenge->notion().'</div></li>');
				} ?>
	    </ul>
	  </li>
		<li><div>Grandeurs et mesures</div>
	    <ul>
				<?php foreach($challengesEarth as $challenge) {
					echo('<li><div notion="'.$challenge->getName().'" class="notion">'.$challenge->notion().'</div></li>');
				} ?>
	    </ul>
	  </li>
	</ul>

	<?php } ?>

</div>


<div id="info_input"></div>
<div id="missingInfo" title="Information incorrecte"></div>
<div id="confirmDelete" title="Supprimer ce créneau ?"></div>
<script src="/webroot/js/manage_timeslots.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>

</body>
