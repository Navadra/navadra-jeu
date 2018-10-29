<div id="bouton_retour">
  <a href="/app/controllers/profil.php?id=<?= $joueur->id(); ?>&tab=admin"><img title="Retour" alt="" src="/webroot/img/icones/btn_retour.png"/></a>
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

  <div class="titleNoBack mb2 g">Ajouter un collège</div>
	<div class="l60 centre">

		<label for="departement" class="label g">Département :</label>
		<input class="input champ titles" autocomplete="off" type="text" title="Choisis un département dans la liste ou créé en un nouveau." id="departement" name="departement"/>

		<label for="nomCollege" class="label g mh4">Nom du collège :</label>
		<input class="input champ mh4 titles" autocomplete="off" type="text" title="Ecris le nom du collège" id="nomCollege" name="nomCollege"/>

		<div class="l100 ib mh2">
			<img class="img_50" id="loading" src="/webroot/img/icones/loading.gif"/>
		</div>
		<div id='validate' class='actionGraph g mh2 p2'>Valider</div>

		<div class="l100 ib vert g centrer p5 mh4" id="confirmation"></div>

	</div>
</div>


<script src="/webroot/js/colleges.js?nvd_r=xxx"></script>

</body>
