<img alt="Logo" id="logo" class="mh4 centre" src="/webroot/img/logo.png">



<!-- Boutons du menu -->
<a class="blanc" href="/app/controllers/inscription.php"><div class="bouton std">Inscription</div></a>
<a class="blanc" href="/app/controllers/connexion.php"><div class="bouton std">Connexion</div></a>
<a class="blanc" href="/app/controllers/presentation.php"><div class="bouton std">Présentation</div></a>
<div class="div_centrale">
<a href="/app/controllers/presentation.php"><img id="play_presentation" alt="" src="/webroot/img/icones/bouton_lecture.png"></a>
</div>

<div id="bouton_contact">Contacte nous</div>

<!-- Dialog de confirmation -->
<div id="info_contact" title="Contacte nous">Tu veux en savoir plus sur Navadra ? Tu as des suggestions ?<br />N'hésite plus : espritdenavadra@navadra.com</div>



<script> //Gestion du dialog
$(function(){
	
$("#info_contact").dialog({
	autoOpen: false,
})

$("#bouton_contact").on("click", function(){
	$("#info_contact").dialog("open");
});

$(".bouton:eq(2)").on("click", function(){
	//mixpanel.track("cliquer sur présentation");
});
	
	
});
</script>