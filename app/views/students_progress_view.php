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

  <div class="titleNoBack g">Suivi de mes élèves</div>
	<div class="pfun p3 mb1 g">Collège <?= $joueur->college(); ?> (<?= $joueur->departement(); ?>)</div>
	<div class="l90 centre">

		<div class="ib l100 mb1 p2">
			<label for="classroom" class="g ib md1">Classe :</label>
			<select name="classroom" id="classroom" class="champ ib l10 texte_centre">
				<?php foreach($classrooms as $classroom) {
					echo('<option value="'.$classroom->id().'" name="'.$classroom->name().'" >'.$classroom->name().'</option>');
				} ?>
			</select>
		</div>

		<div class="ib l100 mb1">
			<label class="g ib md1 p2">Légende :</label>
			<div class="masteryLegend mastery0 ib l12 texte_centre pb05 ph05">Jamais pratiquée</div>
			<div class="masteryLegend mastery1 ib l12 texte_centre pb05 ph05">En difficulté</div>
			<div class="masteryLegend mastery2 ib l12 texte_centre pb05 ph05">Fragile</div>
			<div class="masteryLegend mastery3 ib l12 texte_centre pb05 ph05">En consolidation</div>
			<div class="masteryLegend mastery4 ib l12 texte_centre pb05 ph05">Notion comprise</div>
			<div class="masteryLegend mastery5 ib l12 texte_centre pb05 ph05">Notion maîtrisée</div>
			<div class="masteryLegend mastery6 ib l12 texte_centre pb05 ph05">Sans faute</div>
		</div>

		<div class="ib l100 mb2 p1">
			<div class="ib"><img class="img_20" src="/webroot/img/icones/notebook.png" /></div>
			<div class="legendIcon ib texte_gauche pb05 md4"> : Nombre de fois où la notion a été pratiquée</div>
			<div class="ib"><img class="img_20" src="/webroot/img/icones/calendar.png" /></div>
			<div class="legendIcon ib texte_gauche pb05"> : Dernière fois où la notion a été pratiquée (en jours)</div>
		</div>

		<div id="contentFrame" class="relatif l100">
			<div id="content" class="l100 h100">
				<table id="tableProgress">
					<thead>
						<tr>
							<th class="titles" title="Si l'élève n'a pas renseigné son nom et son prénom, son pseudo figure à la place.">Elèves</th>
							<?php
								foreach($challenges as $challenge) {
									echo('<th class="titles" title="'.$challenge->title_element().'" element="'.$challenge->getElement().'" notion="'.$challenge->getName().'">'.$challenge->notion().'</th>');
								}
							?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($students as $student){
						$student = explode(";", $student);
						$idStudent = $student[0];
						$student = $student[1];
						echo('<tr id="student'.$idStudent.'">');
							echo ('<td class="nameStudent"><img dataName="'.$student.'" dataId="'.$idStudent.'" class="ib img_20 md2 titles deleteStudent" title="Supprimer l\'élève de cette classe" src="/webroot/img/icones/refuser.png" /><span class="titles ib" title="Si l\'élève n\'a pas renseigné son nom et son prénom, son pseudo figure à la place.">'.$student.'</span></td>');
							foreach($challenges as $challenge) {
								if(isset($challengesProgress[$student."_".$challenge->getName()])){
									$data = explode("_", $challengesProgress[$student."_".$challenge->getName()]);
									if($data[2] > 0){
										echo('<td class="mastery'.$data[0].' titles" title="Pratiqué '.$data[2].' fois (dernière fois il y a '.$data[1].')"><img class="img_20" src="/webroot/img/icones/notebook.png" /> '.$data[2].' - <img class="img_20" src="/webroot/img/icones/calendar.png" /> '.$data[1].'</td>');
									} else {
										echo('<td class="mastery0 titles" title="Pas encore pratiqué."> - </td>');
									}
								} else {
									echo('<td class="mastery0 titles" title="Pas encore pratiqué."> - </td>');
								}
							}
						echo('</tr>');
						}
						?>
					</tbody>
				</table>
			</div>
		</div>



	</div>
	<img id="loading" class="img_120 absolu" src="/webroot/img/icones/loading.gif" />

</div>
<div id="confirmDelete" title="Êtes-vous sûr(e) ?"></div>
<script type="text/javascript" src="/vendor/meetselva/js/fixed_table_rc.js"></script>
<script type="text/javascript" src="/vendor/meetselva/js/sortable_table.js"></script>
<script src="/webroot/js/students_progress.js?nvd_r=xxx"></script>

</body>
