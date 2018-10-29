$("#challenges").buttonset();
$("#soloMonsters").buttonset();
$("#bigMonsters").buttonset();
$("#enormousMonsters").buttonset();
$("#legendaryMonsters").buttonset();
$("#debugFights").button();
$("#updatePlayer").button();
$("#deletePlayer").button();
$("#loading").hide();

$("#confirmDelete").dialog({
	autoOpen: false,
	resizable: false,
	modal: true,
	buttons: {
		"C'est mon dernier mot": function(){
			$(this).dialog("close");
			deletePLayer();
		},
		"Laisse moi réfléchir": function(){
			$(this).dialog("close");
		}
	}
});

$("img").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});


var listPlayers=[];

var accentMap = {
    "à": "a",
    "ç": "c",
    "é": "e",
    "è": "e",
    "ê": "e",
	"î": "i",
	"ï": "i",
	"ù": "u",
};

var normalize = function( term ) {
  var ret = "";
  for ( var i = 0; i < term.length; i++ ) {
    ret += accentMap[ term.charAt(i) ] || term.charAt(i);
  }
 return ret;
};

$.ajax({
	url: '/app/controllers/ajax_admin.php',
	type :'POST',
	data: 'getPlayerList=ok',
	dataType: 'json',
	success: function(result, statut){
		listPlayers = result;
		$( "#playerSelected" ).autocomplete({
			source: function( request, response ) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
				response( $.grep( listPlayers, function( value ) {
					value = value.label || value.value || value;
					return matcher.test( value ) || matcher.test( normalize( value ) );
				}) );
			}
		});
	},
	error: function(result, statut, erreur){
		console.log(erreur);
	},
	complete: function(result, statut){
	}
});

$("#validate").on("click", function(){
	var playerSelected = $("#playerSelected").val();
	var challenges = parseInt($("input[name=challenges]:checked").val());
	var soloMonsters = parseInt($("input[name=soloMonsters]:checked").val());
	var bigMonsters = parseInt($("input[name=bigMonsters]:checked").val());
	var enormousMonsters = parseInt($("input[name=enormousMonsters]:checked").val());
	var legendaryMonsters = parseInt($("input[name=legendaryMonsters]:checked").val());
	var ok = listPlayers.indexOf(playerSelected);
	if(ok != -1 && playerSelected != ""){
		$("#loading").show();
		$("#validate").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'player='+playerSelected+'&challenges='+challenges+'&soloMonsters='+soloMonsters+'&bigMonsters='+bigMonsters+'&enormousMonsters='+enormousMonsters+'&legendaryMonsters='+legendaryMonsters,
			dataType: 'html',
			success: function(result, statut){
				playerSelected = "";
				$("#playerSelected").val("");
				$("#confirmation").switchClass("rouge", "vert").html("C'est fait !");
				$("#loading").hide();
				$("#validate").show();
				console.log(result);
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				$("#loading").hide();
				$("#validate").show();
				console.log(erreur);
			},
			complete: function(result, statut){
			}
		});
	}
});

$("#deletePlayer").on("click", function(event){
	event.preventDefault();
	$("#confirmDelete").dialog("open");
});

function deletePLayer(){
	var playerSelected = $("#playerSelected").val();
	var ok = listPlayers.indexOf(playerSelected);
	if(ok != -1 && playerSelected != ""){
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'deletePlayer='+playerSelected,
			dataType: 'html',
			success: function(result, statut){
				$("#playerSelected").val("");
				$("#confirmation").switchClass("rouge", "vert").html(playerSelected + " a été supprimé !");
				playerSelected = "";
				$("#loading").hide();
				$("#validate").show();
				listPlayers.splice(ok, 1);
				console.log(result);
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				$("#loading").hide();
				$("#validate").show();
				console.log(erreur);
			},
			complete: function(result, statut){
			}
		});
	}
}


$("#debugFights").on("click", function(event){
	event.preventDefault();
	debugFights();
});

function debugFights(){
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'debugFights=get',
			dataType: 'html',
			success: function(result, statut){
				$("#confirmation").switchClass("rouge", "vert").html(parseInt(result) + " combat(s) ont été supprimés !");
				console.log(result);
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				console.log(erreur);
			},
			complete: function(result, statut){
			}
		});
}

$("#updatePlayer").on("click", function(event){
	event.preventDefault();
	updatePlayer();
});

function updatePlayer(){
		$("#loading").show();
		$("#validate").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'updatePlayer='+playerSelected,
			dataType: 'html',
			success: function(result, statut){
				$("#playerSelected").val("");
				$("#confirmation").switchClass("rouge", "vert").html("Les portraits ont été mis à jour !");
				playerSelected = "";
				$("#loading").hide();
				$("#validate").show();
				console.log(result);
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				$("#loading").hide();
				$("#validate").show();
				console.log(erreur);
			},
			complete: function(result, statut){
			}
		});
}

$(window).load(function(){
	$("#rideau_haut").hide("slide",{easing:"easeInExpo", direction: "up"}, "slow");
	$("#rideau_bas").hide("slide",{easing:"easeInExpo", direction: "down"}, "slow");
});
