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

  <div class="titleNoBack mb2 g">Dashboard</div>
	<div class="l60 centre">
		<div class="ib l100">
			<label class="g ib md2" for="periodStart">Début Période :</label><input class="ib champ md8 texte_centre" type="text" id="periodStart" name="periodStart">
			<label class="g ib md2" for="periodEnd">Fin Période :</label><input class="ib champ texte_centre" type="text" id="periodEnd" name="periodEnd">
		</div>
		<div class="ib l100 mh2 mb2">
			<label class ="ib g md2">Analyse :</label>
			<div id="typeAnalysis" class="ib">
				<input type="radio" name="typeAnalysis" id="playersPerDay" value="playersPerDay"><label for="playersPerDay">Joueurs par jour</label>
				<input type="radio" name="typeAnalysis" id="addiction" value="addiction"><label for="addiction">Addiction</label>
				<input type="radio" name="typeAnalysis" id="retention" value="retention"><label for="retention">Retention</label>
				<input type="radio" name="typeAnalysis" id="userNetFlow" value="userNetFlow"><label for="userNetFlow">User Net Flow</label>
				<input type="radio" name="typeAnalysis" id="conversion" value="conversion"><label for="conversion">Conversion</label>
				<input type="radio" name="typeAnalysis" id="AARRR" value="AARRR"><label for="AARRR">AARRR</label>
				<input type="radio" name="typeAnalysis" id="acquisition" value="acquisition"><label for="acquisition">Acquisition</label>
			</div>
		</div>
		<div class="l100 ib">
			<img class="img_50" id="loading" src="/webroot/img/icones/loading.gif"/>
		</div>
		<div id='displayAnalysis' class='actionGraph g mb2'>Afficher</div>
		<div class="l100 ib vert g centrer p3 mb2" id="confirmation"></div>

		<div id="dashboardBorders">
	    <div id="challenge_content">
	      <!-- JS inserts content inside this div -->
	    </div>
	  </div>

	</div>
</div>


<script src="/webroot/js/dashboard.js?nvd_r=xxx"></script>

</body>
