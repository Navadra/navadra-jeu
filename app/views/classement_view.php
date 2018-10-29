<!-- Fond -->
<div class="fond l70 mh2 pb1">

	<div class="titre">Classement</div>

	<div id="type_classement" class="mg2 ib">
		  <input type="radio" name="type_classement" id="individuel" value="individuel" checked="checked"><label for="individuel">Individuel</label>
	      <input type="radio" name="type_classement" id="meilleur_coequipier" value="meilleur_coequipier"><label for="meilleur_coequipier">Meilleur Coéquipier</label>
	      <input type="radio" name="type_classement" id="equipe" value="equipe"><label for="equipe">Équipe</label>
	</div>

	<div id="classe" class="mg2 ib">
	      <input type="radio" name="classe" id="student" value="Eleve"><label for="student">Élèves</label>
				<input type="radio" name="classe" id="teacher" value="Prof"><label for="teacher">Profs</label>
	      <input type="radio" name="classe" id="other" value="Autre"><label for="other">Autres</label>
	      <input type="radio" name="classe" id="all" value="Toutes"><label for="all">Toutes</label>
	</div>

	<div class="bordure l95 centre mh1">
	    <div class="entetes_scroll">
	    	<div id="entete_classement" class="ligne_scroll">
	        </div>
	    </div>
	    <div id="contenu_classement" class="corps_scroll align_centre scroll_grand">
	    </div>
	</div>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
</div>
<!-- Fin du fond -->

<?php if(isset($_GET["period"])){
		echo('<div id="period" class="cache">'.$_GET["period"].'</div>');
} ?>

<script src="/webroot/js/classement.js?nvd_r=xxx"></script>
