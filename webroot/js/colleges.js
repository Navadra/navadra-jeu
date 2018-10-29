$("#loading").hide();

var dataCollegeString=[];                     //Autocomplete initialization
var listeCollege=[];
var listeCollegeNames=[];
var listeDepartement=[];

var accentMap = {                              //gestion des accents dans l'autocomplete
    "à": "a",
    "ç": "c",
    "é": "e",
    "è": "e",
    "ê": "e",
	"î": "i",
	"ï": "i",
	"ù": "u",
};
var normalize = function( term ) {              //gestion des accents dans l'autocomplete
  var ret = "";
  for ( var i = 0; i < term.length; i++ ) {
    ret += accentMap[ term.charAt(i) ] || term.charAt(i);
  }
 return ret;
};

$.ajax({
	url: '/app/controllers/ajax.php',
	type :'POST',
	data: 'getCollegeList=ok',
	dataType: 'json',
	success: function(result, statut){
		listeCollege = result;
	},
	error: function(result, statut, erreur){
		console.log(erreur);
	},
	complete: function(result, statut){
		loadVariables();
	}
});

function loadVariables() {
	listeCollege.forEach(function (valeur){         //Departement Array for departement field autocomplete
		if (listeDepartement.indexOf(valeur.departement)==-1){
			listeDepartement.push(valeur.departement);
		};
		if (listeCollegeNames.indexOf(valeur.nom)==-1){
			listeCollegeNames.push(valeur.nom);
		};
	})

	$( "#departement" ).autocomplete({
		source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
			response( $.grep( listeDepartement, function( value ) {
				value = value.label || value.value || value;
				return matcher.test( value ) || matcher.test( normalize( value ) );
			}) );
		}
	});

	$("#nomCollege").on("focus", function(){               //Autocomplete on name of college according to the departement selected
		var departementSelectionne=$("#departement").val();
		var listeCollegeAutocomplete=["Ecole à la maison"];
		if(departementSelectionne == ""){
			listeCollege.forEach(function (valeur){             //Departement Array for departement field autocomplete
				if (listeCollegeAutocomplete.indexOf(valeur.nom)== -1){
					listeCollegeAutocomplete.push(valeur.nom);
				}
			});
		} else {
			listeCollege.forEach(function (valeur){             //Departement Array for departement field autocomplete
				if (departementSelectionne==valeur.departement){
					listeCollegeAutocomplete.push(valeur.nom);
				}
			});
		}

		$("#nomCollege").autocomplete({                    //Autocomplete on the college name field
			source: function( request, response ) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
				response( $.grep( listeCollegeAutocomplete, function( value ) {
					value = value.label || value.value || value;
					return matcher.test( value ) || matcher.test( normalize( value ) );
				}) );
			}
		});
	});
}

var newCollege = {};
$("#validate").on("click", function(){
	newCollege.name = $("#nomCollege").val();
	newCollege.departement = $("#departement").val();
	if(newCollege.name != "" && newCollege.departement != ""){
		$("#loading").show();
		$("#validate").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'newCollegeName='+newCollege.name+'&newCollegeDepartement='+newCollege.departement,
			dataType: 'html',
			success: function(result, statut){
				if(result == "collegeAdded"){
					$("#confirmation").switchClass("rouge", "vert").html("C'est fait !");
					$("#departement").val("");
					$("#nomCollege").val("");
				} else {
					$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !");
				}
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


$(window).load(function(){
	$("#rideau_haut").hide("slide",{easing:"easeInExpo", direction: "up"}, "slow");
	$("#rideau_bas").hide("slide",{easing:"easeInExpo", direction: "down"}, "slow");
});
