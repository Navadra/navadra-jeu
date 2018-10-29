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

  <div class="titleNoBack g">Gérer mes classes</div>
	<div class="pfun p3 mb1 g">Collège <?= $joueur->college(); ?> (<?= $joueur->departement(); ?>)</div>
	<div class="l90 centre">

		<div class="bordure l100 pb05">
			<div class="entetes_scroll p3 g mb1">
				<div id="actionTitle" class="">Ajouter une classe</div>
			</div>

			<input autocomplete="off" type="hidden" id="classroomId" name="classroomId" value="0"/>

			<label for="name" class="g ib md1">Nom (ex: 6°2, 4°B) :</label>
			<input class="champ ib l15 md4 texte_centre" autocomplete="off" type="text" title="Entre 2 et 20 caractères" id="name" name="name"/>

			<label class="g ib md1">Niveau :</label>
			<div id="level" class="ib md4">
				<input type="radio" name="level" id="6°" value="6°"><label for="6°">6°</label>
		    <input type="radio" name="level" id="5°" value="5°"><label for="5°">5°</label>
		    <input type="radio" name="level" id="4°" value="4°"><label for="4°">4°</label>
		    <input type="radio" name="level" id="3°" value="3°"><label for="3°">3°</label>
		    <input type="radio" name="level" id="other" value="Autre"><label for="other">Autre</label>
			</div>

			<label for="maxStudents" class="g ib md1">Nbre maximum d'élèves :</label>
			<input class="champ ib l10 texte_centre" autocomplete="off" type="text" title="Nombre entre 2 et 40" id="maxStudents" name="maxStudents"/>

			<div class="l100 ib mh1">
				<img class="img_50" id="loading" src="/webroot/img/icones/loading.gif"/>
			</div>
			<input type="checkbox" id="cancel" class="ib"><label for="cancel">Annuler saisie</label>
			<div id='validate' class='actionGraph g mh1 p1'>Enregistrer</div>
			<input type="checkbox" id="delete" class="ib"><label for="delete">Supprimer classe</label>
			<div class="l100 ib vert g centrer p1 mh1" id="confirmation"></div>
		</div>

		<div class="bordure l100 mh1 centre">
		    <div class="entetes_scroll p3">
		    	<div class="ligne_scroll">
		            <span class="l30">Nom</span>
		            <span class="l8">Niveau</span>
		            <span class="l25">Nbre d'élèves</span>
		            <span class="l35">Code à donner aux élèves</span>
		        </div>
		    </div>
		    <div id="listClassrooms" class="corps_scroll align_centre scroll_grand p3">
				<?php
				if(count($classrooms) > 0) {
					foreach($classrooms as $classroom) { ?>
					 <div id="<?= $classroom->id(); ?>" class="ligne_scroll classrooms pb05 ph05">
						 <span class="l30"><?= $classroom->name(); ?></span>
						 <span class="l8"><?= $classroom->level(); ?></span>
						 <span class="l25"><?= $classroom->nbStudents(); ?> / <?= $classroom->maxStudents(); ?></span>
						 <span class="l35"><?= $classroom->code(); ?></span>
					 </div>
					 <?php }
				} else {
					echo '<span class="mh10 gris">Vous n\'avez pas encore créé de classe.</span>';
				}
				?>
		    </div>
		</div>

	</div>

	<?php } ?>

</div>


<div id="info_input"></div>
<div id="missingInfo" title="Information incorrecte"></div>
<div id="confirmDelete" title="Supprimer cette classe ?"></div>
<script src="/webroot/js/manage_classes.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>

</body>
