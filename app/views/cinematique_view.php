<body>

<div id="rideau_haut"></div>
<div id="rideau_bas"></div>

<!-- Carte en fond d'écran -->
<div id="carte">
<table cellspacing="0" cellpadding="0">
<tr>
<td>
<img alt="Navadra" src="/webroot/img/carte.jpg">
</td>
</tr>
</table>
</div>

<iframe id="cinematique" frameborder="0" src="<?= $histoire->lien(); ?>?api=1&player_id=cinematique&autoplay=1" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

<div id="impressions" class="bordure_sans_pos">

	<div class="mh2 g p1 ib l100 align_centre mb4">On a besoin de ton avis !</div>
    <div class="label mb8">
      <label>Tu as trouvé la vidéo :</label>
    </div>
    <div id="length" class="input mh2 mb8">
      <input type="radio" name="length" id="bof" value="Bof"><label for="bof">Bof</label>
      <input type="radio" name="length" id="sympa" value="Sympa"><label for="sympa">Sympa</label>
      <input type="radio" name="length" id="tropBien" value="Trop bien"><label for="tropBien">Trop bien</label>
    </div>

    <input type="hidden" name="video" value="<?= $histoire->nom(); ?>" />
    <div id="validate_impressions">
    	<a class="blanc validate_impressions" href="#"><div class="bouton form_droite2">Envoyer</div></a>
		<a href="#" class="validate_impressions"><img class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
    </div>

</div>

<div id="error_impressions" title="Pas si vite !">Peux-tu répondre à notre question s'il te plaît?<br /><br />C'est important pour nous permettre d'améliorer Navadra.</div>


<a class="blanc" href="/app/controllers/index.php"><div id="sortir_intro" class="bouton std">Passer la vidéo</div></a>

<script src="/vendor/froogaloop/froogaloop.js?nvd_r=xxx"></script>
<script src="/webroot/js/cinematique.js?nvd_r=xxx"></script>
