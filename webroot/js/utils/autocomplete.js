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

	$("#departement").on("keyup", function(){
		departement_valide($(this));
	});
	$("#departement").on("autocompleteclose", function(){
		departement_valide($(this));
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

	$("#nomCollege").on("input", function(){
		college_valide($("#departement"), $(this));
	});
	$("#nomCollege").on("autocompleteclose", function(){
		college_valide($("#departement"), $(this));
	});
}

function departement_valide(objet){
	valeur_champ = objet.val();
	var ok = listeDepartement.indexOf(valeur_champ);
	if(ok == -1 && valeur_champ != "")
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

function college_existant(objet){
	valeur_champ = objet.val();
	var ok = listeCollegeNames.indexOf(valeur_champ);
	if(ok == -1 && valeur_champ != "")
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

function college_valide(departementObj, collegeObj){
	var departement = departementObj.val();
	var college = collegeObj.val();
	var ok = false;
    listeCollege.forEach(function (valeur){
    	if (valeur.departement == departement && valeur.nom == college){
        	ok = true;
        }
    });
	var exceptions = ["Ecole à la maison"];
	if(!ok && college != "" && exceptions.indexOf(college) == -1 )
	{
		erreur_saisie(collegeObj);
		return false;
	}
	else
	{
		enlever_erreur(collegeObj);
		return true;
	}
}
