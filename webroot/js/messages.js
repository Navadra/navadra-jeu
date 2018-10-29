// JavaScript Document

$(function(){

//Variables de couleurs
var gris = "#666";
var rouge = "#FF0000";
var bleu = "#0080FF";
var jaune = "#B7B700";
var vert = "#00A452";
var violet = "#7171FF";
var violet_clair = "#AAF";
var blanc = "#FFF";

var pseudo_contact = $(".corps_scroll:eq(0) span:eq(0)").html();
var id_contact = parseInt($("#destinataire_initial").html());
var contenu_conversation = $("#conversation").html();
var id_conversation = parseInt($(".corps_scroll:eq(0) .cache:eq(2)").html());

var resize = false;
var contacts_ouverts = false;

//Comportement des lignes qui se mettent en surbrillance et en gros quand on passe le curseur dessus
$(".corps_scroll div").on("mouseover",function(){
	mettre_en_evidence_ligne_scroll($(this));
});

$(".corps_scroll div").on("mouseout",function(){
	enlever_evidence_ligne_scroll($(this));
});

//Clic pour charger une conversation
$(".corps_scroll div").on("click",function(){
	if(!$(this).hasClass("contact")) //Permet de s'assurer que c'est bien une conversation
	{
		pseudo_contact = $(this).children("span:eq(0)").html();
		contenu_conversation = $(this).children("span:eq(1)").html();
		id_contact = parseInt($(this).children("span:eq(2)").html());
		id_conversation = parseInt($(this).children("span:eq(3)").html());
		$(".entetes_scroll:eq(1)").children().children("span").html("Conversation avec " + pseudo_contact); //Change le nom du contact
		$("#message").val(""); //On réinitialise la zone de message
		$("#conversation").html(contenu_conversation); //On charge la zone de conversation

		if($(this).hasClass("fond_beige_clair")) //Si c'était une conversation non lu enlève le surlignage et la notif
		{
			$(this).removeClass("fond_beige_clair");
			var notif = parseInt($("#notif_messages").html()) - 1;
			if(notif==0)
			{
				$("#notif_messages").remove();
				$("#notif_messages_ico").remove();
			}
			else
			{
				$("#notif_messages").html(notif);
			}
			conversation_lue(id_conversation); //On considère la conversation comme lue
		}
	}
});

//Permet de considérer une conversation comme lue
function conversation_lue(id_conv)
{
	$.ajax({
			url: '/app/controllers/ajax.php',
			type :'POST',
			data: 'id_conv=' + id_conv,
			dataType: 'html',
			success: function(code_html, statut){
				//On utilise le code html sur notre page
			},
			error: function(resultat, statut, erreur){
				//Erreur est une chaine de caractère à afficher au joueur
			},
			complete: function(resultat, statut){
				//Une fois l'ajax fini
			}
		});
}

//Attribution de ses propriétés à l'icone de nouvelle conversation et affichage en haut à droite
$("#nouvelle_conv").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});



$("#nouvelle_conv").on("mouseover",function(){
	$(this).css("height", $(this).height() + 10).css("cursor", "pointer");
});

$("#nouvelle_conv").on("mouseout",function(){
	$(this).css("height", $(this).height() - 10).css("cursor", "");
});

$("#nouvelle_conv").on("mouseup",function(){
	$("#liste_contacts_conv").hide();
	if($("#liste_contacts_conv").hasClass("cache")){$("#liste_contacts_conv").removeClass("cache")};
	$("#liste_contacts_conv").css("top", 0.5*$(window).height() - 0.5*$("#liste_contacts_conv").height()).css("left", 0.5*$(window).width() - 0.5*$("#liste_contacts_conv").width());
	$("#liste_contacts_conv").show("clip", 500, "swing");
	setTimeout(function(){
		contacts_ouverts = true;
	}, 500);
});

/* Fonction qui faisait trop ramer donc annulée
$("body").on("click", function(e){ //Permet de fermer la fenêtre de contacts si l'utilisateur clique en dehors
	e = e || window.event;
	if(contacts_ouverts == true)
	{
		var x_min = $(".bordure:eq(2)").offset().left;
		var x_max = x_min + $(".bordure:eq(2)").width();
		var y_min = $(".bordure:eq(2)").offset().top;
		var y_max = y_min + $(".bordure:eq(2)").height();
		if( e.pageX < x_min || e.pageX > x_max || e.pageY < y_min || e.pageY > y_max )
		{
			disparaitre($(".bordure:eq(2)"));
			contacts_ouverts = false;
		}
	}
});
*/

$("#masquer_contacts").on("click", function(){ //Permet de fermer la fenêtre de contacts en cliquant sur la croix
	if(contacts_ouverts == true)	{
			disparaitre($("#liste_contacts_conv"));
			contacts_ouverts = false;
	}
});

$("#masquer_contacts").on("mouseover", function(){ //Permet de fermer la fenêtre de contacts en cliquant sur la croix
	$(this).css("height", $(this).height() + 10).css("cursor", "pointer");
});

$("#masquer_contacts").on("mouseout", function(){ //Permet de fermer la fenêtre de contacts en cliquant sur la croix
	$(this).css("height", $(this).height() - 10).css("cursor", "");
});


//Script pour appliquer un padding à la textarea sans modifier ses dimensions
$("textarea").on("focus", function(){
	if(resize==false)
	{
		resize = true;
		$(this).css("padding", "5px 10px"); //On rajoute 2px en haut et en bas et 5 à gauche et à droite
		$(this).css("width", $(this).width() - 20); //On diminue sa largeur de 10px
		$(this).css("height", $(this).height() - 10); //On diminue sa hauteur de 4px
	}
});


//Gestion de l'évènement quand l'utilisateur clique sur un contact
$(".contact").on("click", function(){
	pseudo_contact = $(this).children("span:eq(0)").html();
	id_contact = parseInt($(this).children("span:eq(1)").attr("id"));
	disparaitre($("#liste_contacts_conv"));
	contacts_ouverts = false;
	$(".entetes_scroll:eq(1)").children().children("span").html("Conversation avec " + pseudo_contact);
	//On parcours les conversations affichées pour être sûr qu'il n'y avait pas déjà une conversation en cours
	var liste_id_correspondants = $("#liste_id_correspondants").html().split(",");
	if($.inArray(id_contact.toString(), liste_id_correspondants)!= -1) //Si c'est le cas on charge la conversation existante
	{
		contenu_conversation=$("#contact_" + id_contact).children("span:eq(1)").html();
		id_conversation=$("#contact_" + id_contact).children("span:eq(3)").html();
		$("#message").val(""); //On réinitialise la zone de message
		$("#conversation").html(contenu_conversation); //On charge la zone de conversation
		if($("#contact_" + id_contact).hasClass("fond_beige_clair")) //Si c'était une conversation non lu enlève le surlignage et la notif
		{
			$("#contact_" + id_contact).removeClass("fond_beige_clair");
			var notif = parseInt($("#notif_messages").html()) - 1;
			if(notif==0)
				{$("#notif_messages").remove();}
			else
				{$("#notif_messages").html(notif);}
			conversation_lue(id_conversation); //On considère la conversation comme lue
		}
	}
	else //Sinon on réinitialise juste les champs de conversation
	{
		$("#message").val(""); //On réinitialise la zone de message
		$("#conversation").html(""); //On réinitialise la zone de conversation
	}
});

//Gestion du click sur le bouton valider
$("input[name=valider]").on("click", function(event){
	event.preventDefault();
	if(id_contact != "")
	{
		$("input[name=id_destinataire]").val(id_contact);
		if(msg_valide($("#message")) && id_contact != 0) //On soumet seulement si la textarea comprend entre 2 et 800 caractères et qu'un contact est sélectionné
		{
			$("form").submit();
		}
	}
});

$(".icone_form_droite").on("click", function(event){
	event.preventDefault();
	if(id_contact != "")
	{
		$("input[name=id_destinataire]").val(id_contact);
		if(msg_valide($("#message")) && id_contact != 0) //On soumet seulement si la textarea comprend entre 2 et 800 caractères
		{
			$("form").submit();
		}
	}
});

function afficher(objet)
{
	objet.show("clip", 500);
}

function disparaitre(objet)
{
	objet.hide("clip", 500, "swing", function(){
	});
}

//Déclencheurs lorsque l'utilisateur remplit les champs de texte
$("#message").on("keyup",function(){
	msg_valide($(this));
});

//Fonctions de contrôle du format des données
function msg_valide(objet){
	valeur_champ = objet.val();
	var ok = valeur_champ.match(/^[\s\S\r]{2,800}$/);
	if(ok == null && valeur_champ== "")
	{
		enlever_erreur(objet);
		return false;
	}
	else if(ok == null)
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


});

$(window).load(function(){
	positionner_haut_gauche($("#nouvelle_conv"));

	function positionner_haut_gauche(objet)
	{
	objet.hide();
	objet.removeClass("cache");
	var pos = $(".corps_scroll2").offset();
	var largeur_fond = $(".corps_scroll2").width();
	var largeur_img = objet.width();
	objet.css("top", pos.top).css("left", pos.left + largeur_fond - largeur_img -5);
	objet.show();
	}
});
