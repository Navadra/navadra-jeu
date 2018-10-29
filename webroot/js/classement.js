// JavaScript Document

$(function(){

var type_classement = "individuel";
var classe = playerClasse;
if(classe == "6°" || classe == "5°" || classe == "4°" || classe == "3°"){
	classe = "Eleve";
}
$("#classe input").each(function(index, element){
	if(element.value == classe){
		element.click();
		return false;
	}
});
var classement;
var position_portrait = [];

$("#type_classement").buttonset();
$("#classe").buttonset();

$("input[name=type_classement]").on("click", function(){
	type_classement = $(this).val();
	recuperer_classement();
	$("#gif_chargement").show();
});

$("input[name=classe]").on("click", function(){
	classe = $(this).val();
	recuperer_classement();
	$("#gif_chargement").show();
});

//Comportement des lignes de joueurs qui se mettent en surbrillance et en gros quand on passe le curseur dessus
activer_lignes_scroll();
function activer_lignes_scroll()
{
	$(".corps_scroll div").on("mouseover",function(){
		mettre_en_evidence_ligne_scroll($(this));
	});

	$(".corps_scroll div").on("mouseout",function(){
		enlever_evidence_ligne_scroll($(this));
	});

	if($(".team_portraits").length)
	{
		$(".team_portraits").tooltip({
			show: {
				effect: "slideDown",
				delay: 250
			}
		});
	}
}

centrer_scroll();
function centrer_scroll()
{
	var rang = parseInt($("#position").text());
	var scrol = Math.max(0, 10 + 20 * (rang-2));
	$(".corps_scroll").scrollTop(scrol);
}

function changer_entete()
{
	if(type_classement != "equipe")
	{
		var entetes = '<span class="l8">Rang</span><span class="l15">Pseudo</span><span class="l15">Avatar</span><span id="col_prestige" class="l15 blanc">Prestige</span><span class="l8">Classe</span><span class="l25">Collège</span><span class="l10">Département</span>';
		$("#entete_classement").html(entetes);
	}
	else {
		var entetes = '<span class="l7">Rang</span><span class="l60">Équipe</span><span id="col_prestige" class="l30 blanc">Prestige</span>';
		$("#entete_classement").html(entetes);
	}
}

recuperer_classement();
function recuperer_classement() {
	$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: 'type_classement='+type_classement+'&classe='+classe,
			dataType: 'html',
			success: function(resultat, statut){
				classement = resultat; //On récupère le classement en HTML à afficher
				$("#contenu_classement").html(classement);
				activer_lignes_scroll();
				centrer_scroll();
				changer_entete();
				$("#gif_chargement").hide();
			},
			error: function(resultat, statut, erreur){
				//Erreur est une chaine de caractère à afficher au joueur
			},
			complete: function(resultat, statut){
			}
		});
}

$('<img id="gif_chargement" class="img_180" src="/webroot/img/icones/loading.gif"/>').appendTo("body");
var contenu_classement = $("#contenu_classement");
$("#gif_chargement").css("position", "absolute").css("top", contenu_classement.offset().top + 0.5*contenu_classement.height() - 0.5*$("#gif_chargement").height());
$("#gif_chargement").css("left", contenu_classement.offset().left + 0.5*contenu_classement.width() - 0.5*$("#gif_chargement").width()).hide();


});
