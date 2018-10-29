<img id="logo" src="/webroot/img/logo.png">

<img id="titre" src="/webroot/img/maquette/titre.png">

<img id="smartphone" src="/webroot/img/maquette/smartphone.png">
<img id="ecran" src="/webroot/img/maquette/1_fille.png">
<img id="ecran_chargement" src="/webroot/img/maquette/loading.gif">
<img id="btn_precedent" src="/webroot/img/maquette/btn_precedent.png">
<img id="btn_suivant" src="/webroot/img/maquette/btn_suivant.png">

<img id="namuka" src="/webroot/img/personnages/namuka.png">
<img id="sivem" src="/webroot/img/personnages/sivem.png">
<img id="leorn" src="/webroot/img/personnages/leorn.png">
<img id="katillys" src="/webroot/img/personnages/katillys.png">

<img id="apple_store" src="/webroot/img/maquette/apple_store.png">
<div class="div_centrale"><div id="inscrire" class="bouton std"><a class="blanc" href="#">Inscris-toi !</a></div></div>
<img id="play_store" src="/webroot/img/maquette/play_store.png">

<img id="fleche_bas" src="/webroot/img/icones/fleche3.png">

<audio preload="auto" id="son_demo">
	 <source src = "/webroot/sons/chargement_bis.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/chargement_bis.mp3" type="audio/mp3" />
</audio>


<!-- Fond contenant le formulaire -->
<div class="fond l50 mh8 pb3 align_gauche">

<div class="titre">Inscris-toi</div>

<?php
if(isset($_GET["utm_g"]) && ($_GET["utm_g"] == "f" || $_GET["utm_g"] == "g"))
{
	$destination = "/app/controllers/demo.php?utm_g=".$_GET["utm_g"];
}
else
{
	$destination = "/app/controllers/demo.php";
}
?>
<form action=<?= $destination; ?> method="post">
	<p>
    Navadra t'intéresse ?
    </p>
    <p>
    Inscris ton email dans le champ ci-dessous et dès que l'application sera disponible, nous t'enverrons immédiatement une invitation !
    </p>
    <p>
    A bientôt !
    </p>
    <p>
      <label class="label">Email :</label>
      <input class="champ input" autocomplete="off" type="text" title="Au format adresse email classique." name="email" value="<?= htmlspecialchars($_POST["email"]); ?>"/>
	</p>
    <?php if(isset($msg_err)){echo '<span class="msg_erreur g">'.$msg_err.'</span>';}?>
    <?php if(isset($msg_conf)){echo '<span class="msg_conf g">'.$msg_conf.'</span>';}?>
    
<!-- Boutons pour valider le formulaire et fermer le pop-up-->
<div class="bouton form_gauche"><a class="gris" href="#">Fermer</a></div>
<a href="#"><img class="icone_form_gauche" src="/webroot/img/icones/btn_retour.png"></a>

<input class="bouton form_droite" type="submit" name="valider" value="S'inscrire" />
<a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>


</form>
<!-- Fin du fond contenant le formulaire -->
</div>

<script> 
$(function(){
	
<?php
if(isset($_GET["utm_g"]))
{
	if($_GET["utm_g"] == "f")
	{
		echo('var gender = "fille";');
		echo('var source = "facebook";');
	}
	elseif($_GET["utm_g"] == "g")
	{
		echo('var gender = "gars";');
		echo('var source = "facebook";');
	}
	else
	{
		echo('var gender = "inconnu";');
		echo('var source = "inconnu";');
	}
}
else
{
	echo('var gender = "inconnu";');
	echo('var source = "inconnu";');
}
?>
mixpanel.register({
        "gender": gender,
        "source": source
});

//Détermination du sexe de la personne pour l'affichage des slides
if(gender != "inconnu")
{
	var sexe = gender;
}
else
{
	var sexe = "gars"; //Si pas d'info, on affiche la prez des gars
}


var largeur_ecran = $(window).width();
var hauteur_ecran = $(window).height();

//Positionnement personnages
$("#namuka").css("width", 0.18*largeur_ecran).css("position", "absolute").css("left", 0.02*largeur_ecran).css("top", 0);
$("#sivem").css("width", 0.18*largeur_ecran).css("position", "absolute").css("left", 0.08*largeur_ecran).css("bottom", 0);
$("#leorn").css("width", 0.18*largeur_ecran).css("position", "absolute").css("right", 0.02*largeur_ecran).css("top", 0);
$("#katillys").css("width", 0.18*largeur_ecran).css("position", "absolute").css("right", 0.08*largeur_ecran).css("bottom", 0);


//Positionnement logo
$("#logo").css("width", 0.2*largeur_ecran).css("position", "absolute").css("left", 0.4*largeur_ecran).css("top", 0.01*hauteur_ecran);

//Positionnement titre
$("#titre").css("width", 0.34*largeur_ecran).css("position", "absolute").css("left", 0.33*largeur_ecran).css("bottom", 0.15*hauteur_ecran);

//Positionnement smartphone et boutons suivants/prec
$("#smartphone").css("width", 0.40*largeur_ecran).css("position", "absolute").css("left", 0.3*largeur_ecran).css("bottom", 0.33*hauteur_ecran);
$("#btn_precedent").css("width", 0.05*largeur_ecran).css("position", "absolute").css("left", 0.25*largeur_ecran).css("bottom", 0.5*hauteur_ecran);
$("#btn_suivant").css("width", 0.05*largeur_ecran).css("position", "absolute").css("right", 0.25*largeur_ecran).css("bottom", 0.5*hauteur_ecran);


//Positionnement écran
$("#ecran").css("width", 0.4*largeur_ecran).css("position", "absolute").css("left", 0.3*largeur_ecran).css("bottom", 0.33*hauteur_ecran);
$("#ecran_chargement").css("width", 0.1*largeur_ecran).css("position", "absolute").css("left", 0.45*largeur_ecran).css("bottom", 0.43*hauteur_ecran).hide();

//Positionnement icones Apple Store et bouton inscrire
$("#apple_store").css("width", 0.1*largeur_ecran).css("position", "absolute").css("left", 0.3*largeur_ecran).css("bottom", 0.02*hauteur_ecran);
$("#play_store").css("width", 0.1*largeur_ecran).css("position", "absolute").css("right", 0.3*largeur_ecran).css("bottom", 0.02*hauteur_ecran);
$("#inscrire").css("width", 0.15*largeur_ecran);
$(".div_centrale").css("position", "absolute").css("bottom", 0.02*hauteur_ecran);

//Positionnement flèche bas
$("#fleche_bas").css("width", 0.16*largeur_ecran).css("position", "absolute").css("left", 0.42*largeur_ecran).css("bottom", 0.10*hauteur_ecran).hide();

$("#btn_precedent").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/maquette/btn_precedent_selec.png");
	$(this).css("cursor", "pointer");
});

$("#btn_precedent").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/maquette/btn_precedent.png");
});

$("#btn_suivant").on("mouseover", function(){
	$(this).attr("src", "/webroot/img/maquette/btn_suivant_selec.png");
	$(this).css("cursor", "pointer");
});

$("#btn_suivant").on("mouseout", function(){
	$(this).attr("src", "/webroot/img/maquette/btn_suivant.png");
});

var page = 1;
var derniere_page = 16;

$("#btn_suivant").on("click", function(){
	if(page < derniere_page)
	{
		page++;
		mixpanel.track("slide "+ page);
		changer_page();
	}
});

$("#btn_precedent").on("click", function(){
	if(page > 1)
	{
		page--;
		mixpanel.track("slide "+ page);
		changer_page();
	}
});

$("#ecran").attr("src", "/webroot/img/maquette/1_" + sexe + ".png");

function changer_page()
{
	$("#son_demo").trigger("play");
	$("#ecran").hide("drop", {easing: "swing"}, 200, function(){
		$("#ecran_chargement").show();
		$("#ecran").attr("src", "/webroot/img/maquette/" + page + "_" + sexe + ".png").load(function() {
					$("#ecran_chargement").hide();
					$("#ecran").show("drop", {easing: "swing"}, 200, function(){
						if(page == derniere_page)
						{
							$("#fleche_bas").show("blind", {easing : "swing"}, 500);
						}
						else
						{
							$("#fleche_bas").hide();
						}
					});
				});
	});
}


$("input[name=email]").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

//Fonctions de contrôle du format des données
function email_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$/);
	if(ok == null)
	{
		erreur_saisie(objet);
		return false;
	}
	else
	{
		enlever_erreur(objet);
		return true;
	}
}

//Fonctions de formatage des champs pour gérer les erreurs
function erreur_saisie(objet){
	objet.css("border", "2px solid #f00");
}

function enlever_erreur(objet){
	objet.css("border", "2px solid #1c9500");
}

$("input[name=email]").on("keyup",function(){
	email_valide($(this));
	var email = $(this).val();
	$(this).val(email.toLowerCase());
});



<?php if(!isset($email)){ ?>
$(".fond").hide();
mixpanel.track("page d'accueil");
<?php } ?>
//Controle de tous les champs lorsque l'utilisateur clique sur "Valider"
$("input[name=valider]").on("click",function(event){
	event.preventDefault();
	//Vérification que tous les champs soit corrects et que les boutons radios ont été sélectionnés
	var ok = email_valide($("input[name=email]"));
	if(ok!=0)
	{
		var submitForm = function(){$("form").submit();}
		mixpanel.track("inscription", null, submitForm);
	}
	else
	{
		mixpanel.track("email non valide");
	}
});

$(".icone_form_droite").on("click",function(event){
	event.preventDefault();
	//Vérification que tous les champs soit corrects et que les boutons radios ont été sélectionnés
	var ok = email_valide($("input[name=email]"));
	if(ok!=0)
	{
		var submitForm = function(){$("form").submit();}
		mixpanel.track("inscription", null, submitForm);
	}
	else
	{
		mixpanel.track("email non valide");
	}
});

$(".form_gauche").on("click",function(event){
	event.preventDefault();
	$(".fond").hide();
	mixpanel.track("fermer formulaire");
});

$(".icone_form_gauche").on("click",function(event){
	event.preventDefault();
	$(".fond").hide();
	mixpanel.track("fermer formulaire");
});

$("#inscrire").on("click",function(event){
	$(".fond").show();
	mixpanel.track("ouvrir formulaire");
});


});
</script>