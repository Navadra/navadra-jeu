// JavaScript Document

$(function(){

//Attribution de leurs caractéristiques aux widgets jQuery

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

//Déclencheurs lorsque l'utilisateur remplit les champs de texte
$("input[name=email]").on("keyup",function(){
	email_valide($(this));
});

//Controle de tous les champs lorsque l'utilisateur clique sur "Valider"
$("input[name=valider]").on("click",function(event){
	event.preventDefault();
	//Reformatage des zones de texte
	$("input[name=email]").val($("input[name=email]").val().toLowerCase());

	//Vérification que tous les champs soit corrects et que les boutons radios ont été sélectionnés
	var ok = email_valide($("input[name=email]"));
	if(ok!=0)
	{
		$("form").submit();
	}
});

$(".icone_form_droite").on("click",function(event){
	event.preventDefault();
	//Reformatage des zones de texte
	$("input[name=email]").val($("input[name=email]").val().toLowerCase());

	//Vérification que tous les champs soit corrects et que les boutons radios ont été sélectionnés
	var ok = email_valide($("input[name=email]"));
	if(ok!=0)
	{
		$("form").submit();
	}
});

});
