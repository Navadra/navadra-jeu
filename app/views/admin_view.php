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

  <div class="titleNoBack mb2 g">Admin Mode</div>
	<div class="l60 centre">

		<label for="playerSelected" class="label g">Joueur (pseudo) :</label>
		<input class="input champ" autocomplete="off" type="text" title="Choisis un joueur dans la liste." id="playerSelected" name="playerSelected"/>

		<div class="label mh2 g">
			<label>Défis en plus :</label>
		</div>
		<div id="challenges" class="input mh2">
			<input type="radio" name="challenges" id="challenges0" checked="checked" value="0"><label for="challenges0">0</label>
			<input type="radio" name="challenges" id="challenges1" value="1"><label for="challenges1">1</label>
			<input type="radio" name="challenges" id="challenges2" value="2"><label for="challenges2">2</label>
			<input type="radio" name="challenges" id="challenges3" value="3"><label for="challenges3">3</label>
			<input type="radio" name="challenges" id="challenges4" value="4"><label for="challenges4">4</label>
			<input type="radio" name="challenges" id="challenges5" value="5"><label for="challenges5">5</label>
			<input type="radio" name="challenges" id="challenges6" value="6"><label for="challenges6">6</label>
			<input type="radio" name="challenges" id="challenges7" value="7"><label for="challenges7">7</label>
			<input type="radio" name="challenges" id="challenges8" value="8"><label for="challenges8">8</label>
			<input type="radio" name="challenges" id="challenges9" value="9"><label for="challenges9">9</label>
			<input type="radio" name="challenges" id="challenges10" value="10"><label for="challenges10">10</label>
		</div>

		<div class="label mh2 g">
			<label>Monstres solo en plus :</label>
		</div>
		<div id="soloMonsters" class="input mh2">
			<input type="radio" name="soloMonsters" id="soloMonsters0" checked="checked" value="0"><label for="soloMonsters0">0</label>
			<input type="radio" name="soloMonsters" id="soloMonsters1" value="1"><label for="soloMonsters1">1</label>
			<input type="radio" name="soloMonsters" id="soloMonsters2" value="2"><label for="soloMonsters2">2</label>
			<input type="radio" name="soloMonsters" id="soloMonsters3" value="3"><label for="soloMonsters3">3</label>
			<input type="radio" name="soloMonsters" id="soloMonsters4" value="4"><label for="soloMonsters4">4</label>
			<input type="radio" name="soloMonsters" id="soloMonsters5" value="5"><label for="soloMonsters5">5</label>
			<input type="radio" name="soloMonsters" id="soloMonsters6" value="6"><label for="soloMonsters6">6</label>
			<input type="radio" name="soloMonsters" id="soloMonsters7" value="7"><label for="soloMonsters7">7</label>
			<input type="radio" name="soloMonsters" id="soloMonsters8" value="8"><label for="soloMonsters8">8</label>
			<input type="radio" name="soloMonsters" id="soloMonsters9" value="9"><label for="soloMonsters9">9</label>
			<input type="radio" name="soloMonsters" id="soloMonsters10" value="10"><label for="soloMonsters10">10</label>
		</div>

		<div class="label mh2 g">
			<label>Gros monstres en plus :</label>
		</div>
		<div id="bigMonsters" class="input mh2">
			<input type="radio" name="bigMonsters" id="bigMonsters0" checked="checked" value="0"><label for="bigMonsters0">0</label>
			<input type="radio" name="bigMonsters" id="bigMonsters1" value="1"><label for="bigMonsters1">1</label>
			<input type="radio" name="bigMonsters" id="bigMonsters2" value="2"><label for="bigMonsters2">2</label>
			<input type="radio" name="bigMonsters" id="bigMonsters3" value="3"><label for="bigMonsters3">3</label>
			<input type="radio" name="bigMonsters" id="bigMonsters4" value="4"><label for="bigMonsters4">4</label>
			<input type="radio" name="bigMonsters" id="bigMonsters5" value="5"><label for="bigMonsters5">5</label>
			<input type="radio" name="bigMonsters" id="bigMonsters6" value="6"><label for="bigMonsters6">6</label>
			<input type="radio" name="bigMonsters" id="bigMonsters7" value="7"><label for="bigMonsters7">7</label>
			<input type="radio" name="bigMonsters" id="bigMonsters8" value="8"><label for="bigMonsters8">8</label>
			<input type="radio" name="bigMonsters" id="bigMonsters9" value="9"><label for="bigMonsters9">9</label>
			<input type="radio" name="bigMonsters" id="bigMonsters10" value="10"><label for="bigMonsters10">10</label>
		</div>

		<div class="label mh2 g">
			<label>Enormes monstres en plus :</label>
		</div>
		<div id="enormousMonsters" class="input mh2">
			<input type="radio" name="enormousMonsters" id="enormousMonsters0" checked="checked" value="0"><label for="enormousMonsters0">0</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters1" value="1"><label for="enormousMonsters1">1</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters2" value="2"><label for="enormousMonsters2">2</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters3" value="3"><label for="enormousMonsters3">3</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters4" value="4"><label for="enormousMonsters4">4</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters5" value="5"><label for="enormousMonsters5">5</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters6" value="6"><label for="enormousMonsters6">6</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters7" value="7"><label for="enormousMonsters7">7</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters8" value="8"><label for="enormousMonsters8">8</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters9" value="9"><label for="enormousMonsters9">9</label>
			<input type="radio" name="enormousMonsters" id="enormousMonsters10" value="10"><label for="enormousMonsters10">10</label>
		</div>

		<div class="label mh2 g">
			<label>Monstres légendaires en plus :</label>
		</div>
		<div id="legendaryMonsters" class="input mh2">
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters0" checked="checked" value="0"><label for="legendaryMonsters0">0</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters1" value="1"><label for="legendaryMonsters1">1</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters2" value="2"><label for="legendaryMonsters2">2</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters3" value="3"><label for="legendaryMonsters3">3</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters4" value="4"><label for="legendaryMonsters4">4</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters5" value="5"><label for="legendaryMonsters5">5</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters6" value="6"><label for="legendaryMonsters6">6</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters7" value="7"><label for="legendaryMonsters7">7</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters8" value="8"><label for="legendaryMonsters8">8</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters9" value="9"><label for="legendaryMonsters9">9</label>
			<input type="radio" name="legendaryMonsters" id="legendaryMonsters10" value="10"><label for="legendaryMonsters10">10</label>
		</div>
		<div class="l100 ib mh2">
			<img class="img_50" id="loading" src="/webroot/img/icones/loading.gif"/>
		</div>
		<div id='validate' class='actionGraph g mh2 p2'>Valider</div>
		<div class="l100 ib mh2 mb2">
			<!-- <input type="checkbox" id="debugFights" class="ib md2"><label for="debugFights">Débugger combats</label> -->
			<!-- <input type="checkbox" id="updatePlayer" class="ib md2"><label for="updatePlayer">MAJ portraits joueurs</label> -->
			<input type="checkbox" id="deletePlayer" class="ib"><label for="deletePlayer">Supprimer le joueur</label>
		</div>
		<div class="l100 ib vert g centrer p5" id="confirmation"></div>

	</div>
</div>



<div id="confirmDelete" title="Supprimer ce joueur ?">Toutes les données liées au joueur seront définitivement effacées.</div>
<script src="/webroot/js/admin.js?nvd_r=xxx"></script>

</body>
