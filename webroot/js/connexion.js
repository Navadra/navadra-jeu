// JavaScript Document

$(function(){

//Fonctions de contrôle du format des données
function pseudo_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[a-zàéèïîëçù'A-Z0-9 -@_]{3,15}$/);
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

function mdp_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\wàéèïîëçù.!?_-]{6,30}$/);
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


//Déclencheurs lorsque l'utilisateur remplit les champs de texte
$("input[name=pseudo]").on("keyup",function(){
	pseudo_valide($(this));
});

$("input[name=email]").on("keyup",function(){
	email_valide($(this));
});

$("input[type=password]").on("keyup",function(){
	mdp_valide($(this));
});

//Controle de tous les champs lorsque l'utilisateur clique sur "Valider"
$(".connect").on("click",function(event){
	event.preventDefault();
	$("input[name=pseudo]").val($("input[name=pseudo]").val().substr(0,1).toUpperCase() + $("input[name=pseudo]").val().substr(1,$("input[name=pseudo]").val().length).toLowerCase());

	if(pseudo_valide($("input[name=pseudo]")) && mdp_valide($("input[name=mdp]")) ){
		$("#connexionForm").submit();
	}
});


//Déclencheur quand l'utilisateur clique sur "J'ai oublié mon mot de passe
$("#oubli").click(function(event){
	event.preventDefault();
	window.open("/app/controllers/oubli_mdp.php","Mot de passe oublié ?","width=1000");
});

$("#loading").hide();
function sendReminder(){
	$("#mobileDisplay .label").hide();
	$("#mobileDisplay .input").hide();
	$("#loading").show();
	$.ajax({
			url: '/app/controllers/ajax_disconnected.php',
			type :'POST',
			data: 'sendReminder=' + $("#mobileDisplay input[name=email]").val(),
			dataType: 'html',
			success: function(result, statut){
				if(result == "emailSent"){
					$("#loading").hide();
					$("#confirmReminder").removeClass("rouge vert").addClass("vert").html("Email envoyé !");
					$(".sendReminder").hide();
				} else if(result == "wrongEmail"){
					$("#loading").hide();
					$("#confirmReminder").removeClass("rouge vert").addClass("rouge").html("Désolé mais cet email n'est pas valide.");
				}
			},
			error: function(resultat, statut, erreur){
				$("#loading").hide();
				$("#confirmReminder").removeClass("rouge vert").addClass("rouge").html("Désolé, problème de connexion Internet !<br>Réessaie dans quelques instants...");
			},
			complete: function(resultat, statut){
			}
	});
}

$(".sendReminder").on("click",function(event){
	event.preventDefault();
	if(email_valide($("input[name=email]"))){
		sendReminder();
	}
});

$(".externalLink").css("text-decoration", "underline");
$(".externalLink").on("click",function(event){
	event.preventDefault();
	window.open($(this).attr("href"));
});

/*
if(typeof(mixpanel) != "undefined" && window.location.search.match(/[&]?mixid=.+[&]?/)){
	mixpanel.identify(window.location.search.match(/[&]?mixid=(.+)[&]?/)[1]);
	var utm_source = "-";
	var utm_campaign = "-";
	var utm_content = "-";
	if(window.location.search.match(/[&]?utm_source=\w+[&]?/)) {
	  utm_source = window.location.search.match(/[&]?utm_source=(\w+)[&]?/)[1];
  }
  if(window.location.search.match(/[&]?utm_campaign=\w+[&]?/)) {
    utm_campaign = window.location.search.match(/[&]?utm_campaign=(\w+)[&]?/)[1];
  }
  if(window.location.search.match(/[&]?utm_content=\w+[&]?/)) {
    utm_content = window.location.search.match(/[&]?utm_content=(\w+)[&]?/)[1];
  }
	mixpanel.register({
  	"utm_source": utm_source,
    "utm_campaign": utm_campaign,
    "utm_content": utm_content
  });
	mixpanel.init('c8e37f4493d75b03cd0c1b3c22880d49', {
    loaded: function(mixpanel) {
			if(mixpanel.get_property('utm_source') != "-" && mixpanel.get_property('utm_campaign') != "-"){
				mixpanel.track("landingGame");
			}
    }
	});
} */

});
