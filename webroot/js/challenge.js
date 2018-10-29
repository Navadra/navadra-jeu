// JavaScript Document

//GLOBAL VARIABLES
var challenge; //Contains all the challenge data
var user_answer; //Answer given by user
var true_answer; //True answer
var values = {}; //Variables interpretated and evaluated
var expressions = {}; //Variables interpretated but not evaluated
var answer_submitted; //Avoid double submission by user
var inputs_count; //Number of inputs for direct typing
var slots_count; //Number of slots for drag & drop
var board; //Variable for the graph box
var diagram_temp; //Variable to stock user interaction with diagram
var geometric_types = ["point", "glider", "segment", "halfline", "line", "curve", "angle", "arc", "circle", "triangle", "isoRightAngleTriangle", "isoTriangle", "rightAngleTriangle", "equiTriangle", "obtuseTriangle", "acuteTriangle", "quadrilateral", "square", "rectangle", "lozenge", "parallelogram",  "labelGeometry", "valueGeometry"]; //Allows to identify a geometrical variable
var historic_timing = {};
var exercises_record = [];
var diagram = null;
var selectedDiagram = null;

//COLOR VARIABLES
var black = "#000";
var grey = "#666";
var dark_grey = "#352c2d";
var brown = "#ce9d62";
var red = "#FF0000";
var blue = "#0080FF";
var yellow = "#B7B700";
var green = "#00A452";
var purple = "#7171FF";
var light_purple = "#AAF";
var dark_red = "#c80505";
var light_red = "#c80505";
var beige = "#ffe4bd";
var white = "#FFF";
var pastel_yellow = "#FFFFC6";
var pastel_red = "#ac090a";
var pastel_blue = "#05a2d9";
var pastel_yellow = "#FEE996";
var pastel_green = "#38bd48";

// Executed at launch to get the average answer time for the 3 last challenges of each type
$.ajax({
   url: '/app/controllers/ajax_challenge.php',
   type: 'POST',
   data: 'historicAnswerTime=get',
   dataType: 'json',
   success: function (result, status) {
     historic_timing = result;
   },
   error: function (result, status, error) {
     //Erreur est une chaine de caractère à afficher au joueur
   },
   complete: function (result, status) {
   }
});


// Callback to start_exercise()
function get_challenge_json(codeChallenge){
	$("#challenge_content").empty();
	$("<img class='img_160' src='/webroot/img/icones/loading.gif' />").appendTo("#challenge_content"); //Loading pour montrer que ça charge
	/*
	var elementChallengeTemp = codeChallenge.match(/(\w+)_\w+_\d+_\d+/)[1];
	if(elementChallengeTemp != "base"){
		var levelChallengeTemp = parseInt(codeChallenge.match(/\w+_\w+_(\d+)_\d+/)[1]);
		levelChallengeTemp = math.ceil(levelChallengeTemp/2);
		if(levelChallengeTemp == 1){
			var numChallengeTemp = math.randomInt(1,7);
		} else if (levelChallengeTemp == 2){
			var numChallengeTemp = math.randomInt(1,5);
		}
		codeChallenge = "fire_integers_"+levelChallengeTemp+"_"+numChallengeTemp;
	} */
	$.ajax({
    url: '/app/controllers/ajax_challenge.php',
    type: 'POST',
    data: 'codeChallenge='+codeChallenge,
    dataType: 'json',
    success: function (result, status) {
      challenge = result;
	  start_exercise(codeChallenge); // Callback
    },
    error: function(request, status, err) {
		/*
		if(exercises_record.length > 0){
			console.log("Function get_challenge failed with previous challenge in stock. Status: "+status+" - Error: "+err);
			var last_exercise = exercises_record[exercises_record.length - 1];
			codeChallenge = last_exercise.element + "_" + last_exercise.challengeName + "_" + last_exercise.level + "_" + last_exercise.number;
			start_exercise(codeChallenge);
		} else {
			console.log("Function get_challenge failed with no challenge in stock. Status: "+status+" - Error: "+err);
			get_challenge_json(codeChallenge);
		} */
		console.log("Function get_challenge failed. Status: "+status+" - Error: "+err);
		//get_challenge_json(codeChallenge);
	},
	timeout: 10000
  });
}

function display_challenge_bug_report(bugSituation, error_info){
	if(!$("#challenge_bug_report").length){
		$("<div id='challenge_bug_report' class='bulle_daide'></div>").appendTo("body").hide();
		$("#challenge_bug_report").css("position", "absolute").css("width", "30%").css("left", "35%").css("top", "20%").css("cursor", "pointer");
		$("#challenge_bug_report").html("<span class='ib l100 g mb2'><img class='img_100' src='/webroot/img/icones/dommage.png' /></span><span class='ib l100 g mb2'>Désolé, le jeu a planté !</span><span class='ib l100 mb2'>On est vraiment désolés mais tu ne pourras pas finir ce "+bugSituation+". On t'avait prévenu, le jeu est encore en Bêta et il reste encore quelque bugs.</span><span class='ib l100'>En revanche, si tu cliques sur cette bulle, ça nous enverra un rapport d'erreur pour nous permettre de régler le problème.</span><span class='ib l100 p0 g mh2'>-- Clique ici pour fermer cette bulle et revenir à l'accueil. --</span>");
		$("#challenge_bug_report").show("clip", {easing: "swing"}, 1000, function(){
			$("#challenge_bug_report").on("click", function(){
				report_challenge_bug(bugSituation, error_info);
			});
		});
	}
}

function report_challenge_bug(bugSituation, error_info)
{
	$.ajax({
		url: '/app/controllers/envoi_demails.php',
		type :'POST',
		data: 'bugChallenge=' + codeChallenge + '&situation=' + bugSituation  + '&playerId=' + playerId + '&error_info=' + error_info,
		dataType: 'json',
		success: function(json, statut){

		},
		error: function(resultat, statut, erreur){
			$(location).attr('href', "/index.php");
		},
		complete: function(resultat, statut){
			$("#challenge_bug_report").hide("clip", {easing: "swing"}, 1000, function(){
				$(location).attr('href', "/index.php");
			});
		}
	});
}

//VARIOUS FUNCTIONS
//Function to reorganize randomly an array
function shuffleArray(array)
{
    for (var i = array.length - 1; i > 0; i--)
	{
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}

//Function to compare 2 arrays
function compareArrays(array1, array2)
{
	if (array1.length != array2.length)
	{
        return false;
	}
    for (var i = 0; i < array1.length; i++)
	{
        // Check if we have nested arrays
        if (array1[i] instanceof Array && array2[i] instanceof Array)
		{
            // recurse into the nested arrays
            if (!array1[i].equals(array2[i]))
			{
                return false;
			}
        }
        else if (array1[i] != array2[i])
		{
            return false;
        }
    }
    return true;
}

//Transforms a float with a dot into a float with a comma and put a space every 10^3
function convert_EngToFr(nb)
{
	var nb = nb.toString();
	nb = nb.replace(/(\d+)\.(\d+)/, '$1,$2');
	nb = nb.replace(/^([0-9]{0,3})([0-9]{3})([0-9]{3}),/, '$1 $2 $3,'); //Pour la séparation entre centaines et milliers et millions et centaines de milliers
	nb = nb.replace(/^([0-9]{0,3})([0-9]{3})([0-9]{3})$/, '$1 $2 $3'); //Pour la séparation entre centaines et milliers et millions et centaines de milliers
	nb = nb.replace(/^([0-9]{1,3})([0-9]{3}),/, '$1 $2,'); //Pour la séparation entre centaines et milliers
	nb = nb.replace(/^([0-9]{1,3})([0-9]{3})$/, '$1 $2'); //Pour la séparation entre centaines et milliers
	return nb;
}

//Transforms a float with a comma into a float with a dot
function convert_FrToEng(nb)
{
	var nb = nb.toString();
	while(nb.match(/\d+,\d+/))
	{
		nb = nb.replace(/(\d+),(\d+)/, '$1.$2');
	}
	while(nb.match(/\s\d/))
	{
		nb = nb.replace(/\s(\d)/, '$1');
	}
	return nb;
}


//FUNCTION USED FOR INTERPRETATION AND EVALUATION OF VARIABLES AND CONDITIONS
function stringInterpretation(expression_temp, type)
{
	if(typeof(expression_temp) == "string")
	{
		//Interpretation of regex expression to generate strings
		while(expression_temp.match(/\[\w*\d*\]{\d+,\d+}/))
		{
			var pattern_temp = expression_temp.match(/\[(\w*\d*)\]{\d+,\d+}/)[1];
			var repetition_min_temp = parseInt(expression_temp.match(/\[\w*\d*\]{(\d+),\d+}/)[1]);
			var repetition_max_temp = parseInt(expression_temp.match(/\[\w*\d*\]{\d+,(\d+)}/)[1]);
			var repetition_temp = math.randomInt(repetition_min_temp, repetition_max_temp+1);
			var string_generated = "";
			for(i=1;i<=repetition_temp;i++)
			{
				string_generated += pattern_temp;
			}
			expression_temp = expression_temp.replace(/\[\w*\d*\]{\d+,\d+}/, string_generated);
		}
		//Replacement of variable between brackets
		while(expression_temp.match(/{\w+}/))
		{
			if(expression_temp.match(/{img_\w+}/))
			{
				expression_temp=imgGeneration(expression_temp);
			}
			else
			{
				var placeholder = expression_temp.match(/{(\w+)}/)[1];
				if(typeof(type) != 'undefined' && type == "view")
				{
					expression_temp = expression_temp.replace(/{\w+}/, convert_EngToFr(values[placeholder]));
				}
				else
				{
					expression_temp = expression_temp.replace(/{\w+}/, values[placeholder]);
				}
			}
		}
		//Length() method
		while(expression_temp.match(/length\(-*\d*[\.,]*\d*\w*\)/))
		{
			var variable_temp = expression_temp.match(/length\((-*\d*[\.,]*\d*\w*)\)/)[1];
			if(typeof(values[variable_temp]) != "undefined")
			{
				variable_temp = math.eval(variable_temp, values)
			}
			var length_temp = String(variable_temp).length;
			expression_temp = expression_temp.replace(/length\(-*\d*[\.,]*\d*\w*\)/, length_temp);
		}
		//indexOf method
		while(expression_temp.match(/indexOf\(.+,.+\)/))
		{
			var array_temp = expression_temp.match(/indexOf\((.+),.+\)/)[1];
			if(isEvaluable(array_temp))
			{
				array_temp = math.eval(array_temp, values)
			}
			var value_temp = expression_temp.match(/indexOf\(.+,(.+)\)/)[1];
			if(isEvaluable(value_temp))
			{
				value_temp = math.eval(value_temp, values)
			}
			var index_temp = array_temp.indexOf(value_temp);
			expression_temp = expression_temp.replace(/indexOf\(.+,.+\)/, index_temp);
		}
		//slice(serie,start,length)
		while(expression_temp.match(/slice\(.+,.+,.+\)/))
		{
			var array_temp = expression_temp.match(/slice\((.+),.+,.+\)/)[1];
			if(isEvaluable(array_temp))
			{
				array_temp = math.eval(array_temp, values);
			}
			var start_temp = expression_temp.match(/slice\(.+,(.+),.+\)/)[1];
			if(isEvaluable(start_temp))
			{
				start_temp = math.eval(start_temp, values) - 1;
			}
			var length_temp = expression_temp.match(/slice\(.+,.+,(.+)\)/)[1];
			if(isEvaluable(length_temp))
			{
				length_temp = math.eval(length_temp, values);
			}
			var array_sliced = array_temp.slice(0);
			var array_sliced = array_sliced.slice(start_temp,length_temp);
			values["sliceTemp"] = array_sliced;
			expression_temp = expression_temp.replace(/slice\(.+,.+,.+\)/, "sliceTemp");
		}
		//shuffle(array)
		while(expression_temp.match(/shuffle\(\w+\)/))
		{
			var array_temp = expression_temp.match(/shuffle\((\w+)\)/)[1];
			array_temp = values[array_temp];
			var array_shuffled = array_temp.slice(0);
			array_shuffled = shuffleArray(array_shuffled);
			values["shuffleTemp"] = array_shuffled;
			expression_temp = expression_temp.replace(/shuffle\(\w+\)/, "shuffleTemp");
		}
	}
	return expression_temp;
}

function geometryInterpretation(expression_temp, variable_name)
{
	if(typeof(expression_temp) == "string")
	{
		var variable_type = challenge.var[variable_name].type;
		values[variable_name] = expression_temp;
		var ok = 1;
		while(ok > 0) //For conditions check
		{
			ok = 0;
			var expression_copy = expression_temp;
			//Points
			if(variable_type=="point")
			{
				if(expression_copy.match(/^[A-Z]$/))
				{
					var name_point1 = expression_copy.match(/^([A-Z])$/)[1];
					var point1 = coordinates(values[name_point1]);
					values[variable_name] = point1;
				}
			}
			//Gliders
			if(variable_type=="glider")
			{
				if(expression_copy.match(/^[A-Z][A-Z]$/)) //If the glider is a segment
				{
					var name_point1 = expression_copy.match(/([A-Z])[A-Z]/)[1];
					var name_point2 = expression_copy.match(/[A-Z]([A-Z])/)[1];
					var point1 = values[name_point1];
					var point2 = values[name_point2];
					var random_point = random_point_segment([point1, point2]);
					var segment;
					//Determination of the segment name which is based on these 2 points
					$.each(challenge.var, function(index, value){
						if(value.type == "segment" && value.expression == name_point1 + name_point2)
						{
							segment = values[index];
							return false;
						}

					});
					values[variable_name] = [random_point[0], random_point[1], segment];
				}
				else if(expression_copy.match(/^\w+$/)) //If it is a variable
				{
					var name_figure = expression_copy.match(/^(\w+)$/)[1];
					var figure = values[name_figure];
					var random_point = random_point_segment([figure.point1, figure.point2]);
					values[variable_name] = [random_point[0], random_point[1], figure];
				}
			}
			//Lines, halflines, segments
			if(variable_type=="line" || variable_type=="halfline" || variable_type=="segment")
			{
				if(expression_copy.match(/^[A-Z][A-Z]$/))
				{
					var name_point1 = expression_copy.match(/([A-Z])[A-Z]/)[1];
					var name_point2 = expression_copy.match(/[A-Z]([A-Z])/)[1];
					var point1 = values[name_point1];
					var point2 = values[name_point2];
					values[variable_name] = [point1, point2];
				}
			}
			//Angles
			if(variable_type=="angle")
			{
				if(expression_copy.match(/^[A-Z][A-Z][A-Z]$/))
				{
					var name_point1 = expression_copy.match(/([A-Z])[A-Z][A-Z]/)[1];
					var name_point2 = expression_copy.match(/[A-Z]([A-Z])[A-Z]/)[1];
					var name_point3 = expression_copy.match(/[A-Z][A-Z]([A-Z])/)[1];
					var point1 = values[name_point1];
					var point2 = values[name_point2];
					var point3 = values[name_point3];
					values[variable_name] = [point1, point2, point3];
				}
				if(expression_copy.match(/^\w+,\w+$/))
				{
					var name_line1 = expression_copy.match(/^(\w+),\w+$/)[1];
					var name_line2 = expression_copy.match(/^\w+,(\w+)$/)[1];
					var line1 = values[name_line1];
					var line2 = values[name_line2];
					values[variable_name] = [line1, line2, 1, 1];
				}
			}
			//Triangles
			if(variable_type=="triangle" || variable_type=="isoTriangle" || variable_type=="equiTriangle" || variable_type=="rightAngleTriangle" || variable_type=="isoRightAngleTriangle" || variable_type=="obtuseTriangle" || variable_type=="acuteTriangle")
			{
				if(expression_copy.match(/^[A-Z][A-Z][A-Z]$/))
				{
					var name_point1 = expression_copy.match(/([A-Z])[A-Z][A-Z]/)[1];
					var name_point2 = expression_copy.match(/[A-Z]([A-Z])[A-Z]/)[1];
					var name_point3 = expression_copy.match(/[A-Z][A-Z]([A-Z])/)[1];
					var point1 = values[name_point1];
					var point2 = values[name_point2];
					var point3 = values[name_point3];
					values[variable_name] = [point1, point2, point3];
				}
			}
			//Quadrilaterals
			if((variable_type=="quadrilateral" || variable_type=="square" || variable_type=="rectangle" || variable_type=="lozenge" || variable_type=="parallelogram"))
			{
				if(expression_copy.match(/^[A-Z][A-Z][A-Z][A-Z]$/))
				{
					var name_point1 = expression_copy.match(/([A-Z])[A-Z][A-Z][A-Z]/)[1];
					var name_point2 = expression_copy.match(/[A-Z]([A-Z])[A-Z][A-Z]/)[1];
					var name_point3 = expression_copy.match(/[A-Z][A-Z]([A-Z])[A-Z]/)[1];
					var name_point4 = expression_copy.match(/[A-Z][A-Z][A-Z]([A-Z])/)[1];
					var point1 = values[name_point1];
					var point2 = values[name_point2];
					var point3 = values[name_point3];
					var point4 = values[name_point4];
					values[variable_name] = [point1, point2, point3, point4];
				}
			}
			//Circles
			if(variable_type=="circle")
			{
				if(expression_copy.match(/radius\(([A-Z]),\d+\.?\d*\)/))
				{
					var name_center = expression_copy.match(/radius\(([A-Z]),\d+\.?\d*\)/)[1];
					var center = values[name_center];
					var radius = expression_copy.match(/radius\([A-Z],(\d+\.?\d*)\)/)[1];
					radius = parseFloat(radius);
					values[variable_name] = [center, radius];
				}
				else if(expression_copy.match(/radius\(([A-Z]),\w+\)/))
				{
					var name_center = expression_copy.match(/radius\(([A-Z]),\w+\)/)[1];
					var center = values[name_center];
					var name_radius = expression_copy.match(/radius\([A-Z],(\w+)\)/)[1];
					radius = values[name_radius];
					values[variable_name] = [center, radius];
				}
				else if(expression_copy.match(/radius\(([A-Z]),[A-Z]\)/))
				{
					var name_center = expression_copy.match(/radius\(([A-Z]),[A-Z]\)/)[1];
					var center = values[name_center];
					var name_radius = expression_copy.match(/radius\([A-Z],([A-Z])\)/)[1];
					var radius = values[name_radius];
					values[variable_name] = [center, radius];
				}
				else if(expression_copy.match(/diameter\([A-Z]{2}\)/))
				{
					var name_point1 = expression_copy.match(/diameter\(([A-Z])[A-Z]\)/)[1];
					var name_point2 = expression_copy.match(/diameter\([A-Z]([A-Z])\)/)[1];
					var point1 = values[name_point1];
					var point2 = values[name_point2];
					var center = middle_segment([point1, point2]);
					var radius = length_segment([point1, point2])/2;
					values[variable_name] = [center, radius];
				}
			}
			//Arc circles
			if(variable_type=="arc")
			{
				if(expression_copy.match(/[A-Z][A-Z][A-Z]/))
				{
					var name_center = expression_copy.match(/([A-Z])[A-Z][A-Z]/)[1];
					var center = values[name_center];
					var name_point1 = expression_copy.match(/[A-Z]([A-Z])[A-Z]/)[1];
					var point1 = values[name_point1];
					var name_point2 = expression_copy.match(/[A-Z][A-Z]([A-Z])/)[1];
					var point2 = values[name_point2];
					values[variable_name] = [center, point1, point2];
				}
			}
			//parallel(line,point)
			while(expression_copy.match(/parallel\(\w+,.+\)/))
			{
				var line_name = expression_copy.match(/parallel\((\w+),.+\)/)[1];
				if(expression_copy.match(/parallel\(\w+,\w+\)/)==null)
				{
					var line_name = expression_copy.match(/parallel\((\w+),.+\)/)[1];
					var line = values[line_name];
					var box_temp = challenge.view.geometry;
					var point = [math.randomInt(0.5*box_temp[0], 0.5*box_temp[2]), math.randomInt(0.5*box_temp[3], 0.5*box_temp[1])];
				}
				else if(expression_copy.match(/parallel\([A-Z][A-Z],[A-Z]\)/))
				{
					var point1_name = expression_copy.match(/parallel\(([A-Z])[A-Z],[A-Z]\)/)[1];
					var point2_name = expression_copy.match(/parallel\([A-Z]([A-Z]),[A-Z]\)/)[1];
					var point1 = values[point1_name];
					var point2 = values[point2_name];
					var line = obtention_equation([point1, point2]);
					var point_name = expression_copy.match(/parallel\([A-Z][A-Z],([A-Z])\)/)[1];
					var point = values[point_name];
				}
				else
				{
					var line_name = expression_copy.match(/parallel\((\w+),.+\)/)[1];
					var line = values[line_name];
					var point_name = expression_copy.match(/parallel\(\w+,(\w+)\)/)[1];
					var point = values[point_name];
				}
				var parallel_line = parallel(line, point);
				values[variable_name] = parallel_line;
				expression_copy = expression_copy.replace(/parallel\(\w+,.+\)/, variable_name);
			}
			//perpendicular(line,point)
			while(expression_copy.match(/perpendicular\(\w+,.+\)/))
			{
				if(expression_copy.match(/perpendicular\(\w+,\w+\)/)==null)
				{
					var line_name = expression_copy.match(/perpendicular\((\w+),.+\)/)[1];
					var line = values[line_name];
					var box_temp = challenge.view.geometry;
					var point = [math.randomInt(0.5*box_temp[0], 0.5*box_temp[2]), math.randomInt(0.5*box_temp[3], 0.5*box_temp[1])];
				}
				else if(expression_copy.match(/perpendicular\([A-Z][A-Z],[A-Z]\)/))
				{
					var point1_name = expression_copy.match(/perpendicular\(([A-Z])[A-Z],[A-Z]\)/)[1];
					var point2_name = expression_copy.match(/perpendicular\([A-Z]([A-Z]),[A-Z]\)/)[1];
					var point1 = values[point1_name];
					var point2 = values[point2_name];
					var line = obtention_equation([point1, point2]);
					var point_name = expression_copy.match(/perpendicular\([A-Z][A-Z],([A-Z])\)/)[1];
					var point = values[point_name];
				}
				else
				{
					var line_name = expression_copy.match(/perpendicular\((\w+),.+\)/)[1];
					var line = values[line_name];
					var point_name = expression_copy.match(/perpendicular\(\w+,(\w+)\)/)[1];
					var point = values[point_name];
				}
				var perpendicular_line = perpendicular(line, point);
				values[variable_name] = perpendicular_line;
				expression_copy = expression_copy.replace(/perpendicular\(\w+,.+\)/, variable_name);
			}
			//bisector(AB)
			while(expression_copy.match(/bisector\([A-Z][A-Z]\)/))
			{
				var point1_name = expression_copy.match(/bisector\(([A-Z])[A-Z]\)/)[1];
				var point2_name = expression_copy.match(/bisector\([A-Z]([A-Z])\)/)[1];
				var point1 = values[point1_name];
				var point2 = values[point2_name];
				var middle = middle_segment([point1, point2]);
				var bisector = perpendicular([point1, point2],middle);
				values[variable_name] = bisector;
				expression_copy = expression_copy.replace(/bisector\([A-Z][A-Z]\)/, variable_name);
			}
			//altitude(AB,C)
			while(expression_copy.match(/altitude\([A-Z][A-Z],[A-Z]\)/))
			{
				var point1_name = expression_copy.match(/altitude\(([A-Z])[A-Z],[A-Z]\)/)[1];
				var point2_name = expression_copy.match(/altitude\([A-Z]([A-Z]),[A-Z]\)/)[1];
				var point3_name = expression_copy.match(/altitude\([A-Z][A-Z],([A-Z])\)/)[1];
				var point1 = values[point1_name];
				var point2 = values[point2_name];
				var point3 = values[point3_name];
				var altitude = perpendicular([point1, point2],point3);
				values[variable_name] = altitude;
				expression_copy = expression_copy.replace(/altitude\([A-Z][A-Z],[A-Z]\)/, variable_name);
			}
			//intersection(object1,object2)
			while(expression_copy.match(/intersection\(\w+,\w+,?\d?\)/))
			{
				var variable1_name = expression_copy.match(/intersection\((\w+),\w+,?\d?\)/)[1];
				var variable2_name = expression_copy.match(/intersection\(\w+,(\w+),?\d?\)/)[1];
				if(expression_copy.match(/intersection\(\w+,\w+,\d+\)/))
				{
					var option = parseInt(expression_copy.match(/intersection\(\w+,\w+,(\d)\)/)[1]);
				}
				var type_variable1 = challenge.var[variable1_name].type;
				var type_variable2 = challenge.var[variable2_name].type;
				var variable1 = values[variable1_name];
				var variable2 = values[variable2_name];
				if(type_variable1 == "line" && type_variable2 == "line")
				{
					var intersection = intersection_lines (variable1, variable2);
				}
				if(type_variable1 == "circle" && type_variable2 == "line")
				{
					var intersection = intersection_circle_line (variable1, variable2, option);
				}
				if(type_variable1 == "circle" && type_variable2 == "circle")
				{
					var intersection = intersection_circle_circle (variable1, variable2, option);
				}
				values[variable_name] = intersection;
				expression_copy = expression_copy.replace(/intersection\(\w+,\w+,?\d?\)/, variable_name);
			}
			//belongsTo(object1)
			while(expression_copy.match(/belongsTo\(\w+\)/))
			{
				var target_name = expression_copy.match(/belongsTo\((\w+)\)/)[1];
				var target_type = challenge.var[target_name].type;
				var target = values[target_name];
				if(target_type == "line")
				{
					var point = random_point_line(target);
				}
				if(target_type == "segment")
				{
					var point = random_point_segment(target);
				}
				values[variable_name] = point;
				expression_copy = expression_copy.replace(/belongsTo\(\w+\)/, variable_name);
			}
			//middle(AB)
			while(expression_copy.match(/middle\([A-Z][A-Z]\)/))
			{
				var point1_name = expression_copy.match(/middle\(([A-Z])[A-Z]\)/)[1];
				var point2_name = expression_copy.match(/middle\([A-Z]([A-Z])\)/)[1];
				var point1 = values[point1_name];
				var point2 = values[point2_name];
				var middle = middle_segment([point1, point2]);
				values[variable_name] = middle;
				expression_copy = expression_copy.replace(/middle\([A-Z][A-Z]\)/, variable_name);
			}
			//lengthSegment(AB)
			while(expression_copy.match(/lengthSegment\([A-Z][A-Z]\)/))
			{
				var name1 = expression_copy.match(/lengthSegment\(([A-Z])[A-Z]\)/)[1];
				var name2 = expression_copy.match(/lengthSegment\([A-Z]([A-Z])\)/)[1];
				var point1 = values[name1];
				var point2 = values[name2];
				var length = length_segment([point1, point2]);
				var precision = 1;
				if(challenge.var[variable_name].precision != undefined)
				{
					precision = challenge.var[variable_name].precision;
				}
				length = math.round(precision*math.round(length/precision),4);
				values[variable_name] = length;
				expression_copy = expression_copy.replace(/lengthSegment\([A-Z][A-Z]\)/, variable_name);
			}
			//perimeter(ABCD)
			while(expression_copy.match(/perimeter\([A-Z][A-Z][A-Z][A-Z]\)/))
			{
				var name1 = expression_copy.match(/perimeter\(([A-Z])[A-Z][A-Z][A-Z]\)/)[1];
				var name2 = expression_copy.match(/perimeter\([A-Z]([A-Z])[A-Z][A-Z]\)/)[1];
				var name3 = expression_copy.match(/perimeter\([A-Z][A-Z]([A-Z])[A-Z]\)/)[1];
				var name4 = expression_copy.match(/perimeter\([A-Z][A-Z][A-Z]([A-Z])\)/)[1];
				var point1 = values[name1];
				var point2 = values[name2];
				var point3 = values[name3];
				var point4 = values[name4];
				var perimeter = length_segment([point1, point2]) + length_segment([point2, point3]) + length_segment([point3, point4])  + length_segment([point4, point1]);
				var precision = 1;
				if(challenge.var[variable_name].precision != undefined)
				{
					precision = challenge.var[variable_name].precision;
				}
				perimeter = math.round(precision*math.round(perimeter/precision),4);
				values[variable_name] = perimeter;
				expression_copy = expression_copy.replace(/perimeter\([A-Z][A-Z][A-Z][A-Z]\)/, variable_name);
			}

			var conditions = challenge.var[variable_name].conditions;
			if(conditions != undefined && !conditionsVerified(variable_name, conditions))
			{
				ok ++;
			}
		}
		challenge.var[variable_name].value = values[variable_name];
	}
}

//Function to interprate an expression or a variable (type is an optionnal argument that can be set to "view" to convert figures in French)
function interpretation(variable_name, variable, type)
{
	if(values[variable_name] == undefined) //If there is no previous value for this variable
	{
		values[variable_name] = challenge.var[variable_name].value;
	}
	//If an expression is provided
	if(variable.expression != undefined)
	{
		var interpretation_temp = variable.expression;
		expressions[variable_name] = variable.expression;
	}
	//Else if a value is provided
	if(variable.value != undefined)
	{
		var interpretation_temp = values[variable_name];
	}
	if(typeof(interpretation_temp) == "string")
	{
		interpretation_temp = stringInterpretation(interpretation_temp, type);
		if(geometric_types.indexOf(challenge.var[variable_name].type) != -1) //If geometric object
		{
			geometryInterpretation(interpretation_temp, variable_name); //Result stored in the global variable values{}
		}
		else
		{
			values[variable_name] = interpretation_temp;
			challenge.var[variable_name].value = values[variable_name];
		}
	}
	else if($.isArray(interpretation_temp))
	{
		$.each(interpretation_temp, function(index, value){
			interpretation_temp[index] = stringInterpretation(interpretation_temp[index], type);
			//No geometry interpretation for arrays
		});
	}
	//We set the values if not set by previous interpretations
	if(values[variable_name] == undefined)
	{
		values[variable_name] = expressions[variable_name];
	}
	challenge.var[variable_name].value = values[variable_name];
	expressions[variable_name] = challenge.var[variable_name].expression
}

//Function which evaluate elements for user display
function InterprateView(expression_temp)
{
	expression_temp = brGeneration(expression_temp);
	expression_temp = imgGeneration(expression_temp);
	expression_temp = stringInterpretation(expression_temp, "view");
	return expression_temp;
}

//Function which evaluate elements for script control over the results
function InterprateCalculate(expression_temp)
{
	expression_temp = input_eval(expression_temp);
	expression_temp = slot_eval(expression_temp);
	expression_temp = stringInterpretation(expression_temp);
	return expression_temp;
}

//Function to determine if the math.js evaluation will return an error
function isEvaluable(expression)
{
	try
	{
		// evaluate the expression
		math.eval(expression, values);
	}
	catch (e)
	{
		// return the error
		var err = e;
	}
	if(err == undefined)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Function to evaluate variables and to verify conditions
function evaluate(variableValue, variableName, conditions)
{
	var valueTemp;
	if(geometric_types.indexOf(challenge.var[variableName].type) != -1) //If geometric object
	{
		var ok = 1;
		while(ok > 0)
		{
			ok = 0;
			if($.isArray(variableValue))
			{
				valueTemp = variableValue.slice(0);
				$.each(valueTemp, function(index, value){
					valueTemp[index] = math.eval(valueTemp[index], values);
				});
				values[variableName] = valueTemp;
			}
			else
			{
				valueTemp = variableValue;
				valueTemp = math.eval(valueTemp, values);
				values[variableName] = valueTemp;
			}
			if(conditions != undefined && !conditionsVerified(variableName, conditions))
			{
				ok ++;
			}
		}
		variableValue = valueTemp;
	}
	else if($.isArray(variableValue) && (conditions == undefined || conditions.match(/differentAll\(.+\)/)==null) )
	{
		for(var index = 0;index < variableValue.length;index++){
			value = variableValue[index];
			if($.isArray(value))
			{
				for(var i=0;i < value.length;i++){
					variableValue[index][i] = math.eval(variableValue[index][i], values);
				}
			}
			else if(conditions != undefined)
			{
				var ok = 1;
				while(ok > 0)
				{
					ok = 0;
					valueTemp = math.eval(variableValue[index], values);
					if(!conditionsVerified(valueTemp, conditions))
					{
						ok ++;
					}
				}
				variableValue[index] = valueTemp;
			}
			else if(conditions == undefined)
			{
				variableValue[index] = math.eval(variableValue[index], values);
			}
		}
	}
	else if($.isArray(variableValue) && conditions != undefined && conditions.match(/differentAll\(.+\)/)!=null )
	{
		var ok = 1;
		while(ok > 0)
		{
			ok = 0;
			valueTemp = variableValue.slice(0);
			values[variableName] = valueTemp.slice(0);
			$.each(valueTemp, function(index, value){
				valueTemp[index] = math.eval(valueTemp[index], values);
				if(!conditionsVerified(valueTemp[index], conditions))
				{
					ok ++;
				}
				values[variableName] = valueTemp.slice(0);
			});
		}
		variableValue = valueTemp;
	}
	else
	{
		if(conditions != undefined)
		{
			var ok = 1;
			while(ok > 0)
			{
				ok = 0;
				valueTemp = math.eval(variableValue, values);
				values[variableName] = valueTemp;
				if(!conditionsVerified(valueTemp, conditions))
				{
					ok ++;
				}
			}
			variableValue = valueTemp;
		}
		else
		{
			variableValue = math.eval(variableValue, values);
		}
	}
	//If the outcome is the name of an existing variable, takes its value
	if(typeof(variableValue) == "string" && challenge.var[variableName].type != "string" && values[variableValue] != undefined)
	{
		variableValue = coordinates(values[variableValue]);
	}
	return variableValue;
}

//Function to check conditions for variables generated randomly
function conditionsVerified(value_checked, conditions)
{
	var condition_error = 0;
	if($.isArray(conditions))
	{
		$.each(conditions, function(index, value){
			if(math.eval(value, values) == false)
			{
				condition_error++;
			}
		});
	}
	else if(typeof(conditions) == "string" && conditions.match(/different\(.+\)/))
	{
		var array_differents = conditions.match(/different\((.+)\)/)[1];
		var array_differents = array_differents.split(",");
		$.each(array_differents, function(index, value){
			var array_temp = values[value];
			if(array_temp.indexOf(value_checked) != -1)
			{
				condition_error++;
			}
		});
	}
	else if(typeof(conditions) == "string" && conditions.match(/differentAll\(.+\)/))
	{
		var array_differents = conditions.match(/differentAll\((.+)\)/)[1];
		var array_differents = array_differents.split(",");
		$.each(array_differents, function(index, value){
			var array_temp = values[value];
			if(array_temp.indexOf(value_checked) != -1)
			{
				condition_error++;
			}
		});
	}
	else if(typeof(conditions) == "string" && conditions.match(/commonValues\(.+\)/))
	{
		var arrays = conditions.match(/commonValues\((.+)\)/)[1];
		var arrays = arrays.split(",");
		$.each(arrays, function(index, value){
			var array_temp = values[value];
			if(array_temp.indexOf(value_checked) == -1)
			{
				condition_error++;
			}
		});
	}
	//far(object1,object2,objects3) => for geometrical objects
	else if(typeof(conditions) == "string" && conditions.match(/far\(.+\)/))
	{
		var current_object = value_checked;
		var type_object = challenge.var[current_object].type;
		var other_objects = conditions.match(/far\((.+)\)/)[1];
		var other_objects = other_objects.split(",");
		$.each(other_objects, function(index, value){
			var type_other_object = challenge.var[value].type
			if(type_object == "line" && type_other_object == "line")
			{
				if(distance_line_line(values[current_object], coordinates(values[value])) < 2)
				{
					condition_error++;
				}
			}
			else if(type_object == "point" && type_other_object == "point")
			{
				if(length_segment([values[current_object], coordinates(values[value])]) < 2)
				{
					condition_error++;
				}
			}
			else if(type_object == "point" && type_other_object == "line")
			{
				if(distance_point_line(values[current_object], coordinates(values[value])) < 2)
				{
					condition_error++;
				}
			}
		});
	}
	//abscissa < X => for points
	else if(typeof(conditions) == "string" && conditions.match(/abscissa\s[<>=]{1,2}\s-?\d+[\.,]?\d?/))
	{
		var current_object = coordinates(values[value_checked]);
		var criteria = conditions.match(/abscissa\s([<>=]{1,2})\s-?\d+[\.,]?\d?/)[1];
		var abscissa = parseFloat(conditions.match(/abscissa\s[<>=]{1,2}\s(-?\d+[\.,]?\d?)/)[1]);
		values["criteriaTemp"] = criteria;
		if(!math.eval("" + current_object[0] + criteria + abscissa, values))
		{
			condition_error++;
		}
	}
	if(condition_error == 0){
		return true;
	} else {
		return false;
	}
}

function magnet_movable_points(){
	$.each(challenge.answer, function(index, value){
		if(value.match(/placePoint\(\w+,\w+\)/)){
			var movable_point = value.match(/placePoint\((\w+),\w+\)/)[1];
			var destination_point = value.match(/placePoint\(\w+,(\w+)\)/)[1];
			if(challenge.var[movable_point].magnetic != false){
				movable_point = values[movable_point];
				destination_point = values[destination_point];
				var scope = challenge.view.geometry;
				var sensitivity = (math.abs(scope[1]) + math.abs(scope[3]))/15;
				movable_point.setProperty({attractors:[destination_point]});
				movable_point.setProperty({attractorDistance:sensitivity});
				movable_point.setProperty({snatchDistance:2*sensitivity});
			}
		}
	});
}

function line_label_positionning(){
	//For each line
	$.each(values, function(index, value){
		if(value.elType == "line" && value.hasLabel){
			//Avoid labels off the frame
			var coords_temp = value.getLabelAnchor().scrCoords;
			var x_offset = 0;
			if(coords_temp[1] < 20){
				var x_offset = 20 - coords_temp[1];
			} else if(coords_temp[1] > $("#graph_box").width() - 20){
				var x_offset = $("#graph_box").width() - 20 - coords_temp[1];
			}
			var y_offset = 0;
			if(coords_temp[2] < 20){
				var y_offset = 20 - coords_temp[2];
			} else if(coords_temp[2] > $("#graph_box").height() - 20){
				var y_offset = $("#graph_box").height() - 20 - coords_temp[2];
			}
			value.setLabelRelativeCoords([x_offset,-y_offset]);
		}
	});
	board.fullUpdate();
	//For each line
	$.each(values, function(index, value){
		if(value.elType == "line" && value.hasLabel){
			//Avoid overlapping labels (with other line labels)
			var coords1 = value.label.coords.scrCoords;
			$.each(values, function(i, v){
				if(v.elType == "line" && v.hasLabel && v.name != value.name){
					var coords2 = v.label.coords.scrCoords;
					if(math.abs(coords1[1] - coords2[1]) < 40 && math.abs(coords1[2] - coords2[2]) < 20){
						var x_offset = 40 - math.abs(coords2[1] - coords1[1]);
						var y_offset = 20 - math.abs(coords2[2] - coords1[2]);
						if(coords2[1] >= coords1[1] && coords2[1] + x_offset > 20 && coords2[1] + x_offset < $("#graph_box").width() - 20){
							var previous_relative_coords = v.label.relativeCoords.scrCoords;
							v.setLabelRelativeCoords([previous_relative_coords[1] + x_offset, -previous_relative_coords[2]]);
							board.fullUpdate();
						} else if(coords2[1] < coords1[1] && coords2[1] + x_offset > 20 && coords2[1] + x_offset < $("#graph_box").width() - 20){
							var previous_relative_coords = value.label.relativeCoords.scrCoords;
							value.setLabelRelativeCoords([previous_relative_coords[1] + x_offset, -previous_relative_coords[2]]);
							board.fullUpdate();
						} else if(coords2[2] >= coords1[2] && coords2[2] - y_offset > 20 && coords2[2] - y_offset < $("#graph_box").height() - 20){
							var previous_relative_coords = v.label.relativeCoords.scrCoords;
							v.setLabelRelativeCoords([previous_relative_coords[1],-previous_relative_coords[2]-y_offset]);
							board.fullUpdate();
						} else if(coords2[2] < coords1[2] && coords2[2] - y_offset > 20 && coords2[2] - y_offset < $("#graph_box").height() - 20){
							var previous_relative_coords = value.label.relativeCoords.scrCoords;
							value.setLabelRelativeCoords([previous_relative_coords[1],-previous_relative_coords[2]-y_offset]);
							board.fullUpdate();
						}
					}
				}
			});
		}
	});
}


//INTERPRATION AND EVALUATION OF PARAMETERS
function initialization()
{
	//RESET
	answer_submitted = false;
	values = {};
	expressions = {};
	diagram_temp = {};
	selectedDiagram = null;
	board = 0;
	$("#challenge_content").empty();

	//GENERATION OF A GRAPH IF NEEDED
	if(challenge.view != undefined && challenge.view.geometry != undefined)
	{
		$("<div id='graph_box' class='mb2'></div>").appendTo("#challenge_content");
		var axis = false;
		if(challenge.view.axis == true)
		{
			axis = true;
		}
		var grid = false;
		if(challenge.view.grid == true)
		{
			grid = true;
		}
		var box = [-100,100,100,-100];
		initiate_graph(box, axis, grid);
	}

	//VARIABLE SETUP
	$.each(challenge.var, function(index, variable){
		//Determination of variable type if it is not set by user
		var type_temp = variable.type;
		if(!type_temp.match(/^\w+$/) || values[type_temp] != undefined)
		{
			type_temp = math.eval(type_temp, values);
			challenge.var[index].type = type_temp;
		}
		//Interpretation of the variable
		interpretation(index, variable);
		var value_temp = variable.value;
		//Evaluation if the value was meant to be evaluated (not defined by an expression)
		if(typeof(variable.expression) == "undefined")
		{
			value_temp = evaluate(value_temp, index, variable.conditions);
			values[index] = value_temp;
		}
		values[index] = value_temp;
		//Interpretation of the outcome if necessary
		interpretation(index, variable);
		var value_temp = variable.value;
		//Conversion in geometrical object if appropriate
		if(geometric_types.indexOf(variable.type) != -1)
		{
			//Default behavior for unspecified parameters
			var variable_name = index;
			var visible = true;
			if(variable.visible != undefined && variable.visible == false)
			{
				visible = false;
			}
			var fixed_position = true;
			if(variable.fixed != undefined && variable.fixed == false)
			{
				fixed_position = false;
			}
			var label_display = false;
			if(variable.labelDisplay != undefined && variable.labelDisplay != false)
			{
				label_display = variable.labelDisplay;
			}
			var label_type = "name";
			if(variable.labelType != undefined && variable.labelType != "name")
			{
				label_type = variable.labelType;
			}
			var color = "grey";
			if(variable.color != undefined && variable.color != "grey")
			{
				color = variable.color;
			}
			var precision = 1;
			if(variable.precision != undefined && variable.precision != 1)
			{
				precision = variable.precision;
			}
			var dash = 0;
			if(variable.dash != undefined && variable.dash != 0)
			{
				dash = variable.dash;
			}
			var radius = 2;
			if(variable.radius  != undefined && variable.radius != 2)
			{
				radius = variable.radius;
			}
			var snapToGrid = false;
			if(variable.snapToGrid  != undefined)
			{
				snapToGrid = variable.snapToGrid;
			}
			//Creation of geometric objects
			if(variable.type == "point")
			{
				value_temp = trace_point(value_temp, variable_name, color, label_display, label_type, fixed_position, visible, snapToGrid);
			}
			if(variable.type == "glider")
			{
				value_temp = trace_glider(value_temp, variable_name, color, label_display, label_type, fixed_position, visible);
			}
			if(variable.type == "segment")
			{
				value_temp = trace_segment(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible, dash);
			}
			if(variable.type == "halfline")
			{
				value_temp = trace_halfline(value_temp, variable_name, color, label_display, label_type, fixed_position, visible, dash);
			}
			if(variable.type == "line")
			{
				value_temp = trace_line(value_temp, variable_name, color, label_display, label_type, fixed_position, visible, dash);
			}
			if(variable.type == "curve")
			{
				value_temp = trace_curve(value_temp, variable_name, color, label_display, label_type, fixed_position, visible, dash);
			}
			if(variable.type == "angle")
			{
				value_temp = trace_angle(value_temp, variable_name, color, label_display, label_type, radius, precision, visible);
			}
			if(variable.type == "arc")
			{
				value_temp = trace_arc(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "circle")
			{
				value_temp = trace_circle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "triangle")
			{
				value_temp = trace_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "isoRightAngleTriangle")
			{
				value_temp = trace_iso_rightangle_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "isoTriangle")
			{
				value_temp = trace_iso_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "rightAngleTriangle")
			{
				value_temp = trace_rightangle_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "equiTriangle")
			{
				value_temp = trace_equi_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "obtuseTriangle")
			{
				value_temp = trace_obtuse_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "acuteTriangle")
			{
				value_temp = trace_acute_triangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "quadrilateral")
			{
				value_temp = trace_quadrilateral(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "square")
			{
				value_temp = trace_square(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "rectangle")
			{
				value_temp = trace_rectangle(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "lozenge")
			{
				value_temp = trace_lozenge(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "parallelogram")
			{
				value_temp = trace_parallelogram(value_temp, variable_name, color, label_display, label_type, precision, fixed_position, visible);
			}
			if(variable.type == "labelGeometry")
			{
				value_temp = trace_label(value_temp, variable_name, color, label_display, fixed_position, visible);
			}
		}
		values[index] = value_temp;
	});
	if(challenge.type == 5){
		magnet_movable_points(); //Make movable points magnetic if needed
	}
	//CALL NEXT STEP
	viewGeneration();
}

//FUNCTION USED IN VIEW GENERATION
//Create an HTML input for each {input} within the expression
function inputGeneration(expression_temp)
{
	inputs_count = 0;
	while(expression_temp.match(/{input\d+}/))
	{
		var temp = expression_temp.match(/{(input\d+)}/)[1];
		var num_input = temp.match(/(\d+)/)[1];
		expression_temp = expression_temp.replace(/{input\d+}/, '<input id="input'+num_input+'" class="champ" autocomplete="off" type="text"/>');
		inputs_count ++;
	}
	while(expression_temp.match(/{fraction}/))
	{
		expression_temp = expression_temp.replace(/{fraction}/, '<span class="ib l100 fraction_bar"><img class="l8" src="/webroot/img/challenges/trait_fraction.png" /></span>');
	}
	return expression_temp;
}

//Create an HTML slot for each {slot} within the expression
function slotGeneration(expression_temp)
{
	slots_count = 0;
	while(expression_temp.match(/{slot\d+}/))
	{
		var temp = expression_temp.match(/{(slot\d+)}/)[1];
		var num_slot = temp.match(/(\d+)/)[1];
		expression_temp = expression_temp.replace(/{slot\d+}/, '<div class="slot" id="slot'+num_slot+'"></div>');
		slots_count ++;
	}
	return expression_temp;
}

//Create a margin-top for each <br> within the expression
function brGeneration(expression_temp)
{
	while(expression_temp.match(/<br>/))
	{
		expression_temp = expression_temp.replace(/<br>/, '<span class="ib l100 mh2">');
		if(expression_temp.match(/<br>/))
		{
			expression_temp = expression_temp.replace(/<br>/, '</span><br>');
		}
		else
		{
			expression_temp = expression_temp + '</span>';
		}
	}
	return expression_temp;
}

//Create an image for each img_* within the expression
function imgGeneration(expression_temp)
{
	while(expression_temp.match(/{img_[_\w]+}/))
	{
		var img_temp = expression_temp.match(/{img_([_\w]+)}/)[1];
		var elements = ["feu", "eau", "vent", "terre"];
		var pyrs = ["pyrs_feu", "pyrs_eau", "pyrs_vent", "pyrs_terre"];
		var gender = ["fille", "gars"];
		if(elements.indexOf(img_temp) != -1)
		{
			img_temp = '<img src="/webroot/img/elements/'+img_temp+'.png">';
		}
		if(pyrs.indexOf(img_temp) != -1)
		{
			img_temp = '<img src="/webroot/img/icones/'+img_temp+'.png">';
		}
		if(gender.indexOf(img_temp) != -1)
		{
			img_temp = '<img src="/webroot/img/icones/'+img_temp+'.png">';
		}
		expression_temp = expression_temp.replace(/{img_[_\w]+}/, img_temp);
	}
	return expression_temp;
}

function circleProportionalityPositionning(circle, column, position){
	if(position == "top"){
		circle.removeClass("cache");
		var targetFrom = $("#table tr:first td:eq("+(column-1)+")");
		var targetTo = $("#table tr:first td:eq("+(column)+")");
		circle.css("width", 0.5*targetFrom.outerWidth() + 0.5*targetTo.outerWidth());
		circle.css("top", targetFrom.position().top - circle.height());
		circle.css("left", targetFrom.position().left + 0.5*targetFrom.outerWidth());
	} else if(position == "bottom"){
		circle.removeClass("cache");
		var targetFrom = $("#table tr:last td:eq("+(column-1)+")");
		var targetTo = $("#table tr:last td:eq("+(column)+")");
		circle.css("width", 0.5*targetFrom.outerWidth() + 0.5*targetTo.outerWidth());
		circle.css("top", targetFrom.position().top + targetFrom.outerHeight());
		circle.css("left", targetFrom.position().left + 0.5*targetFrom.outerWidth());
	}
}

//GENERATION OF THE VIEW
function viewGeneration()
{
	//OPTIONNAL VIEW ELEMENTS
	if(challenge.view != undefined)
	{
		$("<div id='optionnal_view'></div>").appendTo("#challenge_content");
		if(challenge.view.euclideanDivision != undefined)
		{
			$("<div id='euclidean_structure' class='relatif p2' ><img class='img_120' src='/webroot/img/challenges/euclidean_div.png'/></div>").appendTo("#challenge_content");
			var dividend = math.eval(challenge.view.euclideanDivision.match(/\((.+),.+,.+,.+\)/)[1], values);
			$("<div id='dividend' class='absolu' >"+dividend+"</div>").appendTo("#euclidean_structure");
			$("#dividend").css("bottom", "74%").css("right", "52%");
			var divisor = math.eval(challenge.view.euclideanDivision.match(/\(.+,(.+),.+,.+\)/)[1], values);
			$("<div id='divisor' class='absolu' >"+divisor+"</div>").appendTo("#euclidean_structure");
			$("#divisor").css("bottom", "74%").css("left", "52%");
			var quotient = math.eval(challenge.view.euclideanDivision.match(/\(.+,.+,(.+),.+\)/)[1], values);
			$("<div id='quotient' class='absolu' >"+quotient+"</div>").appendTo("#euclidean_structure");
			$("#quotient").css("bottom", "43%").css("left", "52%");
			var remainder = math.eval(challenge.view.euclideanDivision.match(/\(.+,.+,.+,(.+)\)/)[1], values);
			$("<div id='remainder' class='absolu' >"+remainder+"</div>").appendTo("#euclidean_structure");
			$("#remainder").css("bottom", "8%").css("right", "52%");
		}
		if(challenge.view.tableTitle != undefined)
		{
			if(challenge.view.circleProportionality != undefined && challenge.view.circleProportionality[1] == "top")
			{
				var margin_bottom = "mb6";
			}
			else
			{
				var margin_bottom = "mb1";
			}
			$("<div id='table_title' class='"+margin_bottom+" g' >"+challenge.view.tableTitle+"</div>").appendTo("#challenge_content");
		}
		if(challenge.view.table != undefined)
		{
			$("<table id='table' class='p1 mb2'></table>").appendTo("#challenge_content");
			if(typeof(challenge.var.table.columnheads) != "undefined")
			{
				var current_row = $("<tr></tr>").appendTo("#table");
				if(typeof(challenge.var.table.rowheads) != "undefined")
				{
					$("<th values='col'></th>").appendTo("#table tr");
				}
				$.each(challenge.var.table.columnheads, function(i, head){
					var headTemp = InterprateView(head);
					try
					{
						// evaluate the expression
						math.eval(headTemp, values);
					}
					catch (e)
					{
						// return the error
						var err = e;
					}
					if(err == undefined)
					{
						headTemp = math.eval(headTemp, values);
					}
					$("<th values='col'>"+headTemp+"</th>").appendTo(current_row);
				});
			}
			$.each(values["table"][0], function(i, row){
				var current_row = $("<tr></tr>").appendTo("#table");
				if(typeof(challenge.var.table.rowheads) != "undefined")
				{
					var rowTemp = InterprateView(challenge.var.table.rowheads[i]);
					try
					{
						// evaluate the expression
						math.eval(rowTemp, values);
					}
					catch (e)
					{
						// return the error
						var err = e;
					}
					if(err == undefined)
					{
						rowTemp = math.eval(rowTemp, values);
					}
					$("<th>"+rowTemp+"</th>").appendTo(current_row);
				}
				$.each(values["table"], function(j, val){
					var value_temp = InterprateView(convert_EngToFr(String(values["table"][j][i])));
					if(value_temp == "undefined")
					{
						var value_temp = "";
					}
					$("<td>"+value_temp+"</td>").appendTo(current_row);
				});
			if(i == values["table"][0].length - 1){ //Last iteration
				if(challenge.view.circleProportionality != undefined)
				{
					var column = challenge.view.circleProportionality[0];
					var position = challenge.view.circleProportionality[1];
					var circle = $("<img id='circle_proportionality' class='absolu cache' src='/webroot/img/challenges/circle_proportionality_"+position+".png'/></div>").appendTo("#challenge_content").load(function(){
						circleProportionalityPositionning(circle, column, position);
					});
				}
			}
			});
		}
		if(challenge.view.geometry != undefined)
		{
			//UPDATE OF THE GRAPH BOUNDINGBOX
			var box = challenge.view.geometry;
			$.each(box, function(i, v){
				box[i] = math.eval(v, values);
			});
			board.setBoundingBox(box);
			line_label_positionning();
		}
		if(challenge.view.diagram != undefined)
		{
			$("<div id='graph_box' class='mb2'></div>").appendTo("#challenge_content");
			var series = [];
			challenge.view.title = stringInterpretation(challenge.view.title, "view");
			if(challenge.view.diagram == "spider")
			{
				$.each(challenge.var.series.value, function(index, value){
					series.push({
						name: challenge.var.seriesName.value[index],
						data: value,
						draggableX: challenge.var.seriesOption.draggableX,
						draggableY: challenge.var.seriesOption.draggableY,
						dragMinY: challenge.var.seriesOption.dragMinY,
						minPointLength: challenge.var.seriesOption.minPointLength,
						pointPlacement: challenge.var.seriesOption.pointPlacement
					});
				});
				initiate_spider_diagram(challenge.view.title, challenge.var.legend.value, series, challenge.var.seriesOption.showTooltip)
			}
			else if(challenge.view.diagram == "bars")
			{
				$.each(challenge.var.series.value, function(index, value){
					series.push({
						name: challenge.var.seriesName.value[index],
						data: value,
						draggableX: challenge.var.seriesOption.draggableX,
						draggableY: challenge.var.seriesOption.draggableY,
						dragMinY: challenge.var.seriesOption.dragMinY,
						minPointLength: challenge.var.seriesOption.minPointLength,
						type: challenge.var.seriesOption.type_diagram,
						showInLegend: challenge.var.seriesOption.showLegend
					});
				});
				initiate_bars_diagram(challenge.view.title, challenge.var.legend.value, series, challenge.var.seriesOption.showTooltip);
			}
			else if(challenge.view.diagram == "pies")
			{
				var data_pie = [];
				var legend = challenge.var.legend.value;
				var sum_values = math.sum(challenge.var.serie1.value);
				$.each(challenge.var.series.value[0], function(index, value){
					data_pie.push({
						name: legend[index],
						y: math.round(value/sum_values*100,0),
                		drilldown: null
					});
				});
				series.push({
					name: challenge.var.seriesName.value[0],
					colorByPoint: challenge.var.seriesOption.colorByPoint,
					data: data_pie
				});
				var legend = challenge.var.seriesOption.showLegend;
				initiate_circular_diagram(challenge.view.title, legend, series);
			}
		}
		if(challenge.view.image != undefined)
		{
			$("<div id='graph_box' class='mb2'><img class='h100' src='/webroot/img/challenges/"+challenge.view.image+".png'/></div>").appendTo("#challenge_content");
		}
		if(challenge.view.smallBars != undefined)
		{
			$("<div id='smallBars' class='mb2'></div>").appendTo("#challenge_content");
			var series = [];
			$.each(challenge.var.series.value, function(index, value){
				series.push(value);
			});
			var legend = [];
			$.each(challenge.var.legend.expression, function(index, value){
				legend.push({
					label: value
				});
			});
			initiate_small_bars(legend, series);
		}
	}

	//DISPLAY QUESTION
	question_size = challenge.question.length
	challenge.question = inputGeneration(challenge.question);
	challenge.question = slotGeneration(challenge.question);
	challenge.question = InterprateView(challenge.question);
	if(question_size > 150){
		question_size = "";
	} else if(question_size > 100){
		question_size = "p3";
	} else {
		question_size = "p5";
	}
	$("<div id='question_challenge' class='mg5 l90 "+question_size+"'></div>").appendTo("#challenge_content");
	$("#question_challenge").html(challenge.question);

	//DISPLAY ANSWERING OPTIONS
	if(challenge.type == 1) //Input FIELD
	{
		//input field
		$("#challenge_content input").on("keyup", function(){
			var number = $(this).val();
			while(number.match(/\s/))
			{
				number = number.replace(/\s/, "");
			}
			number = convert_EngToFr(number);
			$(this).val(number);
		})
		//Validation button
		$("<div id='challenge_validate' class='p2'>Valider</div>").appendTo("#challenge_content");
		$("<div id='submitIndication' class='mh1 p0 g'>(ou appuie sur Entrée)</div>").appendTo("#challenge_content");
		$("#challenge_content input:eq(0)").focus();
		$("#challenge_validate").on("click", function(){
			var filled = true;
			$("#challenge_content input").each(function(){
				if($(this).val() == ""){
					$(this).val(0);
					filled = false;
				}
			});
			if(filled){
				call_verification();
			}
		});
	}
	if(challenge.type == 2) //Radio Buttons
	{
		var nb_char_max = 0;
		$("<div id='challenge_buttons' class='mh2 p2'></div>").appendTo("#challenge_content");
		$.each(challenge.answer, function(index, value){
			if(value.choice.length > nb_char_max)
			{
				nb_char_max = value.choice.length;
			}
			$("<span class='md6'>" + InterprateView(value.choice) + "</span>").appendTo("#challenge_buttons");
		});
		if(nb_char_max > 10)
		{
			$("#challenge_buttons").removeClass("p2");
		}
		//Give properties to buttons
		user_answer = "";
		$("#challenge_buttons span").on("click", function(){
			user_answer = $(this).html();
			call_verification();
		});
		//Randomize the buttons order
		var parent = $("#challenge_buttons");
    	var divs = parent.children();
    	while (divs.length) {
       	 parent.append(divs.splice(Math.floor(Math.random() * divs.length), 1)[0]);
		}
	}
	if(challenge.type == 3) //CheckBoxes
	{
		$("<div id='checkboxes' class='mh6 p2'></div>").appendTo("#challenge_content");
		var answers_temp = shuffleArray(challenge.answer);
		$.each(answers_temp, function(index, value){
			$("<input type='checkbox' id='check"+index+"'><label for='check"+index+"'>" + InterprateView(value.choice) + "</label>").appendTo("#checkboxes");
		});
		$("<div class='mh1 p0 g'>Plusieurs réponses possibles</div>").appendTo("#challenge_content");
		//Validation button
		$("<div id='challenge_validate' class='p2'>Valider</div>").appendTo("#challenge_content");
		$("<div id='submitIndication' class='mh1 p0 g'>(ou appuie sur Entrée)</div>").appendTo("#challenge_content");
		$("#challenge_validate").on("click", function(){
			call_verification();
		});
		$("#checkboxes").buttonset();
	}
	if(challenge.type == 4) //Drag & drop
	{
		var size_max_answer = 0;
		$("<div id='challenge_buttons' class='mh2'></div>").appendTo("#challenge_content");
		$.each(challenge.answer, function(index, value){
			if(value.length > size_max_answer)
			{
				size_max_answer = value.length;
			}
			if(isEvaluable(value))
			{
				value = math.eval(value,values);
			}
			$("<span class='md2'>" + InterprateView(value) + "</span>").appendTo("#challenge_buttons");
		});
		if(size_max_answer > 20)
		{
			$("#challenge_buttons").addClass("p0");
		} else {
			$("#challenge_buttons").addClass("p1");
		}
		//Give properties to buttons
		$("#challenge_buttons span").draggable({revert: true});
		$("#challenge_buttons span").each(function(index, element){ //Prevent buttons resize when dragged
			$(this).css("height", $(this).height());
			$(this).css("width", $(this).outerWidth());
		});
		var button_draged;
		$("#challenge_buttons span").on("mousedown", function(){
			button_draged = $(this);
		});
		//Give properties to slots
		$(".slot").droppable({
		  activate: function( event, ui ){
			$(this).css("background-color", "#FFFFBF").css("border", "4px solid #EDDC10");
		  },
		  deactivate: function( event, ui ){
			$(this).css('background-color', '').css("border", "");
		  },
		  over: function( event, ui ){
			$(this).css("height", button_draged.outerHeight());
			$(this).css("width", button_draged.outerWidth());;
		  },
		  out: function( event, ui ){
			$(this).css('height', '');
			$(this).css('width', '');
		  },
		  drop: function( event, ui ) {
			$(this).html(button_draged.html());
			$(this).removeAttr('style');
			$(this).css("background-color", "#ac0000").css("color", "#fff").css("margin-top", "0.5%").css("margin-bottom", "0.5%").css("cursor", "pointer");
			$(this).css("-moz-border-radius", "10px").css("-webkit-border-radius","10px").css("border-radius", "10px");
			$(this).css("height", button_draged.outerHeight());
			$(this).css("width", button_draged.outerWidth());
			if($("#challenge_buttons").hasClass("p0")){
				var font_class = "p0";
			} else {
				var font_class = "p1";
			}
			$(this).addClass(font_class);
			button_draged.hide();
			$(this).droppable("option", "disabled", true);
			$(this).on("mousedown", function(){
				$(".slot").empty().removeAttr('style');
				$(".slot").droppable("option", "disabled", false);
				$("#challenge_buttons span").show();
			});
		  }
		});
		//Randomize the buttons order
		var parent = $("#challenge_buttons");
    	var divs = parent.children();
    	while (divs.length) {
       	 parent.append(divs.splice(Math.floor(Math.random() * divs.length), 1)[0]);
		}
		$("<div class='p0 g'>Fais glisser les choix dans les emplacements</div>").appendTo("#challenge_content");
		//Validation button
		$("<div id='challenge_validate' class='p2'>Valider</div>").appendTo("#challenge_content");
		$("<div id='submitIndication' class='mh1 p0 g'>(ou appuie sur Entrée)</div>").appendTo("#challenge_content");
		$("#challenge_content input:eq(0)").focus()
		$("#challenge_validate").on("click", function(){
			call_verification();
		});
	}
	if(challenge.type == 5) //Geometry
	{
		//Validation button
		$("<div id='challenge_validate' class='p2'>Valider</div>").appendTo("#challenge_content");
		$("<div id='submitIndication' class='mh1 p0 g'>(ou appuie sur Entrée)</div>").appendTo("#challenge_content");
		$("#challenge_validate").on("click", function(){
			call_verification();
		});
	}
}


//FUNCTION USEFULL FOR VERIFICATION OF ANSWER AND DISPLAY OF THE GOOD(S) ANSWER(S)
//Function called by clicking on "Valider" or by typing "Enter" in a input-type question
function call_verification()
{
	if(challenge.type == 1)
	{
		user_answer = {};
		$("#challenge_content input").each(function (index) {
			user_answer[$(this).attr("id")] = $(this).val();
		});
		verification();
	}
	if(challenge.type == 2)
	{
		verification();
	}
	if(challenge.type == 3)
	{
		verification();
	}
	if(challenge.type == 4)
	{
		verification();
	}
	if(challenge.type == 5)
	{
		verification();
	}
}

//Evaluation of an input to compare with expected answer
function input_eval(expression_temp)
{
	while(expression_temp.match(/{input\d+}/))
	{
		var num_input = expression_temp.match(/{input(\d+)}/)[1];
		var user_input = convert_FrToEng(user_answer["input"+num_input]);
		if(user_input.match(/^-?\d+\.?\d*$/)) //If it is a number
		{
			//nothing happens
		}
		else if(user_input.match(/^\w+$/)) //If it is a string
		{
			user_input = user_input.toUpperCase();
			if(values[user_input] == undefined)
			{
				user_input = "0";
			}
		}
		else
		{
			user_input = "0"; //Avoid impossible evaluation by math.js
		}
		expression_temp = expression_temp.replace(/{input\d+}/, user_input);
	}
	return expression_temp;
}

//Evaluation of a slot to compare with expected answer
function slot_eval(expression_temp)
{
	while(expression_temp.match(/{slot\d+}/))
	{
		var num_slot = expression_temp.match(/{slot(\d+)}/)[1];
		var user_input = convert_FrToEng(user_answer["slot"+num_slot]);
		if(user_input == "")
		{
			user_input = 0; //Avoid impossible evaluation by math.js
		}
		expression_temp = expression_temp.replace(/{slot\d+}/, user_input);
	}
	return expression_temp;
}

//Function to evaluate geometrical conditions
function geometry_condition_evaluation(expression_temp)
{
	var ok = 0;
	var evaluated = false;
	if(board != 0)
	{
		board.update();
	}
	//Replacement of variable between brackets
	while(expression_temp.match(/{\w+}/))
	{
		var placeholder = expression_temp.match(/{(\w+)}/)[1];
		expression_temp = expression_temp.replace(/{\w+}/, values[placeholder]);
	}
	//A belongsTo figure
	if(expression_temp.match(/\w+\sbelongsTo\s\w+/))
	{
		evaluated = true;
		var name_point = expression_temp.match(/(\w+)\sbelongsTo\s\w+/)[1];
		var name_figure = expression_temp.match(/\w+\sbelongsTo\s(\w+)/)[1];
		var type_figure = challenge.var[name_figure].type;
		if(values[name_figure] == undefined || values[name_point] == undefined)
		{
			return false;
		}
		var figure = values[name_figure];
		var point = values[name_point];
		var x = point.coords.scrCoords[1];
		var y = point.coords.scrCoords[2];
		var invert = false;
		if(expression_temp.match(/!\w+\sbelongsTo\s\w+/))
		{
			invert = true;
		}
		if(type_figure == "segment")
		{
			var segment1 = coordinates(figure.point1);
			var segment2 = coordinates(figure.point2);
			point = coordinates(point);
			if((point[0]==segment1[0] || point[0]==segment2[0] || figure.hasPoint(x,y)) && ((point[0]>=segment1[0] && point[0]<=segment2[0]) || (point[0]<=segment1[0] && point[0]>=segment2[0])) )
			{
				if(invert)
				{
					ok++;
				}
			}
			else
			{
				if(!invert)
				{
					ok++;
				}
			}
		}
		if(type_figure == "halfline")
		{
			var segment1 = coordinates(figure.point1);
			var segment2 = coordinates(figure.point2);
			point = coordinates(point);
			if((point[0]==segment1[0] || point[0]==segment2[0] || figure.hasPoint(x,y)) && ((point[0]>=segment1[0] && segment2[0]>=segment1[0]) || (point[0]<=segment1[0] && segment2[0]<=segment1[0])) )
			{
				if(invert)
				{
					ok++;
				}
			}
			else
			{
				if(!invert)
				{
					ok++;
				}
			}
		}
		else
		{
			if(figure.hasPoint(x,y))
			{
				if(invert)
				{
					ok++;
				}
			}
			else
			{
				if(!invert)
				{
					ok++;
				}
			}
		}
	}
	//intersection(object1,object2)
	if(expression_temp.match(/\w+\sintersection\(\w+,\w+,?\d?\)/))
	{
		evaluated = true;
		var name_point = expression_temp.match(/(\w+)\sintersection\(\w+,\w+\)/)[1];
		var point = values[name_point];
		var variable1_name = expression_temp.match(/\w+\sintersection\((\w+),\w+\)/)[1];
		var variable2_name = expression_temp.match(/\w+\sintersection\(\w+,(\w+)\)/)[1];
		var type_variable1 = challenge.var[variable1_name].type;
		var type_variable2 = challenge.var[variable2_name].type;
		var variable1 = values[variable1_name];
		var variable2 = values[variable2_name];
		if(type_variable1 == "line" && type_variable2 == "line")
		{
			var intersection = intersection_lines (variable1, variable2);
			if(length_segment([intersection, point]) > 0.3)
			{
				ok++;
			}
		}
		if(type_variable1 == "circle" && type_variable2 == "line")
		{
			var intersection1 = intersection_circle_line (variable1, variable2, 1);
			var intersection2 = intersection_circle_line (variable1, variable2, 2);
			if(length_segment([intersection1, point]) > 0.3 && length_segment([intersection2, point]) > 0.3)
			{
				ok++;
			}
		}
		if(type_variable1 == "circle" && type_variable2 == "circle")
		{
			var intersection = intersection_circle_circle (variable1, variable2, 1);
			var intersection2 = intersection_circle_circle (variable1, variable2, 2);
			if(length_segment([intersection1, point]) > 0.3 && length_segment([intersection2, point]) > 0.3)
			{
				ok++;
			}
		}
	}
	//inside
	if(expression_temp.match(/\w+\sinside\s\w+/))
	{
		evaluated = true;
		var name_variable1 = expression_temp.match(/(\w+)\sinside\s\w+/)[1];
		var name_variable2 = expression_temp.match(/\w+\sinside\s(\w+)/)[1];
		var type_variable1 = challenge.var[name_variable1].type;
		var type_variable2 = challenge.var[name_variable2].type;
		variable1 = values[name_variable1];
		variable2 = values[name_variable2];
		invert = false;
		if(expression_temp.match(/!\w+\sinside\s\w+/))
		{
			invert = true;
		}
		if(type_variable1 == "point" && type_variable2 == "circle")
		{
			var circle = coordinates(variable2);
			if(length_segment([variable1, circle[0]]) > circle[1])
			{
				if(invert == false)
				{
					ok++;
				}
			}
			else
			{
				if(invert)
				{
					ok++;
				}
			}
		}
	}
	//object1 far(object2,object3,objects4)
	if(expression_temp.match(/far\(.+\)/))
	{
		evaluated = true;
		var array_temp = expression_temp.match(/(\w+)\sfar\(.+\)/)[1];
		var array_differents = expression_temp.match(/\w+\sfar\((.+)\)/)[1];
		var array_differents = array_differents.split(",");
		$.each(array_differents, function(index, value){
			var type_array_temp = challenge.var[array_temp].type
			var type_array_diff = challenge.var[value].type
			if(type_array_temp == "line" && type_array_diff == "line")
			{
				if(distance_line_line(eval_geometry(values[array_temp], "line"), eval_geometry(values[value], "line")) < 2)
				{
					ok++;
				}
			}
			else if(type_array_temp == "point" && type_array_diff == "point")
			{
				if(length_segment([values[array_temp], values[value]]) < 2)
				{
					ok++;
				}
			}
			else if(type_array_temp == "point" && type_array_diff == "line")
			{
				if(distance_point_line(values[array_temp], eval_geometry(values[value], "line")) < 2)
				{
					ok++;
				}
			}
		});
	}
	//aligned(object2,object3,objects4)
	if(expression_temp.match(/aligned\(.+\)/))
	{
		evaluated = true;
		var array_aligned = expression_temp.match(/aligned\((.+)\)/)[1];
		var array_aligned = array_aligned.split(",");
		var test = 0;
		$.each(array_aligned, function(index, value){
			if(values[value.toUpperCase()] == undefined)
			{
				test++;
			}
		});
		if(test == 0)
		{
			var point1 = values[array_aligned[0].toUpperCase()];
			var point2 = values[array_aligned[1].toUpperCase()];
			var line = obtention_equation([point1, point2]);
			$.each(array_aligned, function(index, value){
				var point = values[value.toUpperCase()];
				if(distance_point_line(point, line) > 2)
				{
					ok++;
				}
			});
		}
		else
		{
			ok++;
		}
	}
	//barDiagram(serie,name,value)
	if(expression_temp.match(/barDiagram\(\w+,\w+,\d+\.?\d*\)/))
	{
		evaluated = true;
		var serie = expression_temp.match(/barDiagram\((\w+),\w+,\d+\.?\d*\)/)[1];
		var name = expression_temp.match(/barDiagram\(\w+,(\w+),\d+\.?\d*\)/)[1];
		var value = expression_temp.match(/barDiagram\(\w+,\w+,(\d+\.?\d*)\)/)[1];
		if(diagram_temp[serie] == undefined || diagram_temp[serie][name] == undefined || math.abs(diagram_temp[serie][name]-value)>5)
		{
			ok++;
		}
	}
	//clickDiagram(serieIndex,index)
	if(expression_temp.match(/clickDiagram\(\w+,\w+\)/))
	{
		evaluated = true;
		var indexSerie = expression_temp.match(/clickDiagram\((\w+),\w+\)/)[1];
		var index = expression_temp.match(/clickDiagram\(\w+,(\w+)\)/)[1];
		indexSerie = math.eval(indexSerie,values);
		index = math.eval(index,values);
		if(selectedDiagram==null || selectedDiagram.series.index != indexSerie-1 || selectedDiagram.index != index-1 )
		{
			ok++;
		}
	}
	//Evaluate the expression if no evaluation has been made before
	if(evaluated == false)
	{
		try
		{
			// evaluate the expression
			math.eval(expression_temp, values);
		}
		catch (e)
		{
			// return the error
			var err = e;
			ok ++;
		}
		if(err == undefined && math.eval(expression_temp, values) == false)
		{
			ok ++;
		}
	}
	if(ok==0)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Function to display answer in geometry
function geometry_show_answer(expression_temp)
{
	//Replacement of variable between brackets
	while(expression_temp.match(/{\w+}/))
	{
		var placeholder = expression_temp.match(/{(\w+)}/)[1];
		expression_temp = expression_temp.replace(/{\w+}/, values[placeholder]);
	}
	//placePoint(object,destination)
	if(expression_temp.match(/placePoint\(\w+,\w+\)/))
	{
		var object_name = expression_temp.match(/placePoint\((\w+),\w+\)/)[1];
		var destination_name = expression_temp.match(/placePoint\(\w+,(\w+)\)/)[1];
		var object = values[object_name];
		var destination = coordinates(values[destination_name]);
		values[object_name].setPosition(JXG.COORDS_BY_USER,[destination[0],destination[1]]);
		board.fullUpdate();
	}
	//segmentLength(segment,X)
	if(expression_temp.match(/segmentLength\(\w+,\d+\)/))
	{
		var segment_name = expression_temp.match(/segmentLength\((\w+),\d+\)/)[1];
		var segment = values[segment_name];
		var length = expression_temp.match(/segmentLength\(\w+,(\d+)\)/)[1];
		resize_segment(segment, length);
	}
	//angleValue(angle,X)
	if(expression_temp.match(/angleValue\(\w+,\d+\)/))
	{
		var angle_name = expression_temp.match(/angleValue\((\w+),\d+\)/)[1];
		var angle = values[angle_name];
		var value = expression_temp.match(/angleValue\(\w+,(\d+)\)/)[1];
		resize_angle(angle, value);
	}
	//square(ABCD)
	if(expression_temp.match(/square\([A-Z][A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/square\(([A-Z])[A-Z][A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/square\([A-Z]([A-Z])[A-Z][A-Z]\)/)[1];
		var point3_name = expression_temp.match(/square\([A-Z][A-Z]([A-Z])[A-Z]\)/)[1];
		var point4_name = expression_temp.match(/square\([A-Z][A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		var point4 = values[point4_name];
		trace_square([point1, point2, point3, point4], "answer", "grey", false, false, 1, true, true);
	}
	//rectangle(ABCD)
	if(expression_temp.match(/rectangle\([A-Z][A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/rectangle\(([A-Z])[A-Z][A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/rectangle\([A-Z]([A-Z])[A-Z][A-Z]\)/)[1];
		var point3_name = expression_temp.match(/rectangle\([A-Z][A-Z]([A-Z])[A-Z]\)/)[1];
		var point4_name = expression_temp.match(/rectangle\([A-Z][A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		var point4 = values[point4_name];
		trace_rectangle([point1, point2, point3, point4], "answer", "grey", false, false, 1, true, true);
	}
	//parallelogram(ABCD)
	if(expression_temp.match(/parallelogram\([A-Z][A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/parallelogram\(([A-Z])[A-Z][A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/parallelogram\([A-Z]([A-Z])[A-Z][A-Z]\)/)[1];
		var point3_name = expression_temp.match(/parallelogram\([A-Z][A-Z]([A-Z])[A-Z]\)/)[1];
		var point4_name = expression_temp.match(/parallelogram\([A-Z][A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		var point4 = values[point4_name];
		trace_parallelogram([point1, point2, point3, point4], "answer", "grey", false, false, 1, true, true);
	}
	//lozenge(ABCD)
	if(expression_temp.match(/lozenge\([A-Z][A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/lozenge\(([A-Z])[A-Z][A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/lozenge\([A-Z]([A-Z])[A-Z][A-Z]\)/)[1];
		var point3_name = expression_temp.match(/lozenge\([A-Z][A-Z]([A-Z])[A-Z]\)/)[1];
		var point4_name = expression_temp.match(/lozenge\([A-Z][A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		var point4 = values[point4_name];
		trace_lozenge([point1, point2, point3, point4], "answer", "grey", false, false, 1, true, true);
	}
	//isoRightAngleTriangle(ABC)
	if(expression_temp.match(/isoRightAngleTriangle\([A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/isoRightAngleTriangle\(([A-Z])[A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/isoRightAngleTriangle\([A-Z]([A-Z])[A-Z]\)/)[1];
		var point3_name = expression_temp.match(/isoRightAngleTriangle\([A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		trace_iso_rightangle_triangle([point1, point2, point3], "answer", "grey", false, false, 1, true, true);
	}
	//isoTriangle(ABC)
	if(expression_temp.match(/isoTriangle\([A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/isoTriangle\(([A-Z])[A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/isoTriangle\([A-Z]([A-Z])[A-Z]\)/)[1];
		var point3_name = expression_temp.match(/isoTriangle\([A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		trace_iso_triangle([point1, point2, point3], "answer", "grey", false, false, 1, true, true);
	}
	//rightAngleTriangle(ABC)
	if(expression_temp.match(/rightAngleTriangle\([A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/rightAngleTriangle\(([A-Z])[A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/rightAngleTriangle\([A-Z]([A-Z])[A-Z]\)/)[1];
		var point3_name = expression_temp.match(/rightAngleTriangle\([A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		trace_rightangle_triangle([point1, point2, point3], "answer", "grey", false, false, 1, true, true);
	}
	//equiTriangle(ABC)
	if(expression_temp.match(/equiTriangle\([A-Z][A-Z][A-Z]\)/))
	{
		var point1_name = expression_temp.match(/equiTriangle\(([A-Z])[A-Z][A-Z]\)/)[1];
		var point2_name = expression_temp.match(/equiTriangle\([A-Z]([A-Z])[A-Z]\)/)[1];
		var point3_name = expression_temp.match(/equiTriangle\([A-Z][A-Z]([A-Z])\)/)[1];
		var point1 = values[point1_name];
		var point2 = values[point2_name];
		var point3 = values[point3_name];
		trace_equi_triangle([point1, point2, point3], "answer", "grey", false, false, 1, true, true);
	}
	//bisector(AB,CD)
	if(expression_temp.match(/bisector\([A-Z][A-Z],[A-Z][A-Z]\)/))
	{
		var segment1 = expression_temp.match(/bisector\(([A-Z])[A-Z],[A-Z][A-Z]\)/)[1];
		var segment2 = expression_temp.match(/bisector\([A-Z]([A-Z]),[A-Z][A-Z]\)/)[1];
		var middle_name = expression_temp.match(/bisector\([A-Z][A-Z],([A-Z])[A-Z]\)/)[1];
		var line_point_name = expression_temp.match(/bisector\([A-Z][A-Z],[A-Z]([A-Z])\)/)[1];
		segment1 = values[segment1];
		segment2 = values[segment2];
		var middle = middle_segment([segment1,segment2]);
		var bisector = perpendicular([segment1,segment2], middle);
		var length = length_segment([middle, segment1]);
		var line_point = intersection_circle_line ([middle,length], bisector, 2)
		values[middle_name].setPosition(JXG.COORDS_BY_USER,[middle[0],middle[1]]);
		values[line_point_name].setPosition(JXG.COORDS_BY_USER,[line_point[0],line_point[1]]);
		board.fullUpdate();
	}
	//altitude(AB,CD,E)
	if(expression_temp.match(/altitude\([A-Z][A-Z],[A-Z][A-Z],[A-Z]\)/))
	{
		var segment1 = expression_temp.match(/altitude\(([A-Z])[A-Z],[A-Z][A-Z],[A-Z]\)/)[1];
		var segment2 = expression_temp.match(/altitude\([A-Z]([A-Z]),[A-Z][A-Z],[A-Z]\)/)[1];
		var altitude1 = expression_temp.match(/altitude\([A-Z][A-Z],([A-Z])[A-Z],[A-Z]\)/)[1];
		var altitude2 = expression_temp.match(/altitude\([A-Z][A-Z],[A-Z]([A-Z]),[A-Z]\)/)[1];
		var vertice = expression_temp.match(/altitude\([A-Z][A-Z],[A-Z][A-Z],([A-Z])\)/)[1];
		segment1 = values[segment1];
		segment2 = values[segment2];
		vertice = coordinates(values[vertice]);
		values[altitude2].setPosition(JXG.COORDS_BY_USER,[vertice[0],vertice[1]]);
		var altitude = perpendicular([segment1,segment2], vertice);
		var intersection = intersection_lines ([segment1,segment2], altitude, 2);
		values[altitude1].setPosition(JXG.COORDS_BY_USER,[intersection[0],intersection[1]]);
		board.fullUpdate();
	}
	//barDiagram(serie,name,value)
	if(expression_temp.match(/barDiagram\(\w+,\w+,\d+\.?\d*\)/))
	{
		var serie = expression_temp.match(/barDiagram\((\w+),\w+,\d+\.?\d*\)/)[1];
		var name = expression_temp.match(/barDiagram\(\w+,(\w+),\d+\.?\d*\)/)[1];
		var value = parseFloat(expression_temp.match(/barDiagram\(\w+,\w+,(\d+\.?\d*)\)/)[1]);
		serie = values["seriesName"].indexOf(serie);
		name = values["legend"].indexOf(name);
		var chart = $('#graph_box').highcharts();
		chart.series[serie].data[name].update(value);
	}
	//highlightDiagram(serieIndex,index)
	if(expression_temp.match(/highlightDiagram\(\w+,\w+\)/))
	{
		var serieIndex = expression_temp.match(/highlightDiagram\((\w+),\w+\)/)[1];
		var index = expression_temp.match(/highlightDiagram\(\w+,(\w+)\)/)[1];
		serieIndex = math.eval(serieIndex,values);
		index = math.eval(index,values);
		if(selectedDiagram != null){
			selectedDiagram.update({ color: selectedDiagram.series.color }, false, false);
		}
		diagram.series[serieIndex-1].data[index-1].update({ color: '#c80505' }, true, false);
	}
}



//VALIDATION OF THE ANSWER(S)
function verification()
{
	if(!answer_submitted)
	{
		answer_submitted = true;
		if(challenge.type == 1) //Input FIELD
		{
			var ok = 0;
			true_answer = "";
			//VERIFICATION OF EACH CRITERION
			$.each(challenge.criteria, function(index, value){
				var condition_temp = InterprateCalculate(value);
				if(board != 0)
				{
					if(geometry_condition_evaluation(condition_temp) == false)
					{
						ok ++;
					}
				}
				else
				{
					condition_temp = convert_FrToEng(condition_temp);
					try
					{
						// evaluate the expression
						math.eval(condition_temp, values);
					}
					catch (e)
					{
						// return the error
						var err = e;
						ok ++;
					}
					if(err == undefined && math.eval(condition_temp, values) == false)
					{
						ok ++;
					}
				}
			});
			//ANSWER DISPLAY
			if(ok == 0)
			{
				good_answer();
			}
			else
			{
				//Determination of answers to display
				$.each(challenge.answer, function(index, value){
					value = InterprateCalculate(value);
					true_answer += convert_EngToFr(math.eval(value, values))+", ";
				})
				true_answer = true_answer.substring(0, true_answer.length - 2);
				if(challenge.type_answer == "multiple" && inputs_count > 1 && challenge.question.match(/trait_fraction.png/)) //If the 2 inputs form a fraction
				{
					true_answer = true_answer.replace(/, /, "/");
					wrong_answer("Pas tout à fait, une bonne réponse était par exemple : <span class='g rouge_fonce'>" + true_answer +"</span>.");
				}
				else if(challenge.type_answer == "multiple")
				{
					wrong_answer("Pas tout à fait, une bonne réponse était par exemple : <span class='g rouge_fonce'>" + true_answer +"</span>.");
				}
				else if(inputs_count > 1 && challenge.question.match(/trait_fraction.png/)) //If the 2 inputs form a fraction
				{
					true_answer = true_answer.replace(/, /, "/");
					wrong_answer("Pas tout à fait, la bonne réponse était : <span class='g rouge_fonce'>" + true_answer +"</span>.");
				}
				else if(inputs_count > 1)
				{
					wrong_answer("Pas tout à fait, les bonnes réponses étaient : <span class='g rouge_fonce'>" + true_answer +"</span>.");
				}
				else
				{
					wrong_answer("Pas tout à fait, la bonne réponse était : <span class='g rouge_fonce'>" + true_answer +"</span>.");
				}
			}
		}
		if(challenge.type == 2) //Radio Buttons
		{
			var multiple_answers=false;
			$.each(challenge.answer, function(index, value){
				if(typeof(value.determined) != "undefined" && value.determined == true)
				{
					true_answer = InterprateView(value.choice);
				}
				else if(typeof(value.if) != "undefined")
				{
					var condition_temp = InterprateCalculate(value.if);
					if(board != 0)
					{
						if(geometry_condition_evaluation(condition_temp) == true)
						{
							true_answer = InterprateView(value.choice);
						}
					}
					else
					{
						condition_temp = convert_FrToEng(condition_temp);
						try
						{
							// evaluate the expression
							math.eval(condition_temp, values);
						}
						catch (e)
						{
							// return the error
							var err = e;
						}
						if(err == undefined && math.eval(condition_temp, values) == true)
						{
							true_answer = InterprateView(value.choice);
						}
					}
				}
			});

			if(user_answer == true_answer)
			{
				good_answer();
			}
			else if(multiple_answers)
			{
				wrong_answer("Pas tout à fait, une bonne réponse était : <span class='g rouge_fonce'>" + true_answer +"</span>.");
			}
			else if(!multiple_answers)
			{
				wrong_answer("Pas tout à fait, la bonne réponse était : <span class='g rouge_fonce'>" + true_answer +"</span>.");
			}
		}
		if(challenge.type == 3) //CheckBoxes
		{
			true_answer = [];
			//Recovery of a good answers array
			$.each(challenge.answer, function(index, value){
				if(typeof(value.determined) != "undefined" && value.determined == true)
				{
					true_answer.push(InterprateView(value.choice));
				}
				else if(typeof(value.if) != "undefined" && math.eval(value.if, values) == true)
				{
					true_answer.push(InterprateView(value.choice));
				}
			});
			true_answer.sort();
			user_answer = [];
			//Recovery of user answers
			$("#checkboxes input").each(function(index){
				if($(this).is(':checked'))
				{
					user_answer.push($(this).button( "option", "label" ));
				}
			});
			user_answer.sort();
			if(compareArrays(user_answer, true_answer))
			{
				good_answer();
			}
			else if(true_answer.length == 1)
			{
				wrong_answer("Pas tout à fait, la bonne réponse était : <span class='g rouge_fonce'>" + true_answer[0] +"</span>.");
			}
			else if(true_answer.length > 1)
			{
				true_answer = true_answer.join(", ");
				wrong_answer("Pas tout à fait, les bonnes réponses étaient : <span class='g rouge_fonce'>" + true_answer +"</span>.");
			}
		}
		if(challenge.type == 4) //Drag & drop
		{
			var ok = 0;
			true_answer = "";
			//VERIFICATION OF EACH CRITERION
			$(".slot").each(function(index, value){
				var answer_temp = challenge.answer[index];
				answer_temp = InterprateView(answer_temp);
				if($(this).html() != answer_temp)
				{
					ok ++;
				}
			});
			//ANSWER DISPLAY
			if(ok == 0)
			{
				good_answer();
			}
			else
			{
				//Determination of answers to display
				var answers_count = 0;
				$.each(challenge.answer, function(index, value){
					answers_count ++;
					if(answers_count <= slots_count)
					{
						true_answer += stringInterpretation(value, "view")+", ";
					}
				})
				true_answer = true_answer.substring(0, true_answer.length - 2);
				wrong_answer("Pas tout à fait, les réponses étaient dans l'ordre : <span class='g rouge_fonce'>" + true_answer +"</span>.");
			}
		}
		if(challenge.type == 5) //Geometry
		{
			var ok = 0;
			true_answer = "";
			//VERIFICATION OF EACH CRITERION
			$.each(challenge.criteria, function(index, value){
				var condition_temp = InterprateCalculate(value);
				if(geometry_condition_evaluation(condition_temp) == false)
				{
					ok ++;
				}
			});
			//ANSWER DISPLAY
			if(ok == 0)
			{
				good_answer();
			}
			else
			{
				//Determination of answers to display
				$.each(challenge.answer, function(index, value){
					geometry_show_answer(value);
				});
				if(challenge.type_answer == "multiple")
				{
					wrong_answer("Pas tout à fait, <span class='g rouge_fonce'>regarde ci-dessous un exemple de bonne réponse</span> pour comprendre.");
				}
				else
				{
					wrong_answer("Pas tout à fait, <span class='g rouge_fonce'>regarde ci-dessous la réponse</span> pour comprendre.");
				}
			}
		}
	}
}


//GEOMETRIC FUNCTIONS
//Functions to determine the coordinates of a geometric object
function coordinates(geometric_object)
{
	//If the object is an array
	if($.isArray(geometric_object))
	{
		//If the line is an array of 2 geometric points
		if(geometric_object[0] != undefined && geometric_object[0].elType != undefined)
		{
			geometric_object[0] = [geometric_object[0].coords.usrCoords[1], geometric_object[0].coords.usrCoords[2]];
			geometric_object[1] = [geometric_object[1].coords.usrCoords[1], geometric_object[1].coords.usrCoords[2]];
		}
		return geometric_object;
	}
	//If the object is not a geometric object
	else if(geometric_object.elType == undefined)
	{
		return geometric_object;
	}
	else if(geometric_object.elType == "point" || geometric_object.elType == "glider")
	{
		return [geometric_object.coords.usrCoords[1], geometric_object.coords.usrCoords[2]];
	}
	else if(geometric_object.elType == "line")
	{
		var point1 = [geometric_object.point1.coords.usrCoords[1], geometric_object.point1.coords.usrCoords[2]];
		var point2 = [geometric_object.point2.coords.usrCoords[1], geometric_object.point2.coords.usrCoords[2]];
		return [point1, point2];
	}
	else if(geometric_object.elType == "circle")
	{
		if(geometric_object.radius != undefined && geometric_object.radius > 0)
		{
			var radius = geometric_object.radius;
		}
		else
		{
			var radius = geometric_object.gxtterm;
		}
		return [[geometric_object.center.coords.usrCoords[1], geometric_object.center.coords.usrCoords[2]], radius];
	}
}

function update_point(point, value)
{
	point.setPosition(JXG.COORDS_BY_USER,[value[0],value[1]]);
	board.fullUpdate();
}

function obtention_equation(line) //If a line is characterized by 2 points, transforms it into an equation slope + intercept otherwise does nothing
{
	line = coordinates(line);
	if($.isArray(line[0])) //Si the line is defined by 2 points
	{
		var line1 = line[0];
		var line2 = line[1];
		if(line1[1] == line2[1]) //horizontal line
		{
			var slope = 0;
			var intercept = line1[1];
		}
		else if(line1[0] == line2[0]) //verticale line
		{
			var slope = "infinite";
			var intercept = line1[0];
		}
		else
		{
			var slope = (line2[1] - line1[1])/(line2[0] - line1[0]);
			var intercept = line1[1] - slope * line1[0];
		}
	}
	else //Otherwise, its already defined by a slope and an intercept
	{
		var slope = line[0];
		var intercept = line[1];
	}
	return [slope, intercept];
}

function perpendicular(line, point) //Gives the equation of the perpendicular line to {line} passing by a given point
{
	line = coordinates(line);
	point = coordinates(point);
	var equation = obtention_equation(line);
	var slope = equation[0];
	var intercept = equation[1];
	if(slope == 0)
	{
		var slope_perp = "infinite";
		var intercept_perp = point[0];
	}
	else if(slope == "infinite")
	{
		var slope_perp = 0;
		var intercept_perp = point[1];
	}
	else
	{
		var slope_perp = -1/slope;
		var intercept_perp = point[1] - slope_perp * point[0];
	}
	return [slope_perp, intercept_perp];
}

function parallel(line, point) //Gives the equation of the parallel line to {line} passing by a given point
{
	line = coordinates(line);
	point = coordinates(point);
	var equation = obtention_equation(line);
	var slope = equation[0];
	var intercept = equation[1];

	var slope_parr = slope;
	var intercept_parr = point[1] - slope_parr * point[0];

	return [slope_parr, intercept_parr];
}

function intersection_lines (line1, line2) //Takes 2 lines as arguments and gives the coordinates of the intersection point
{
	line1 = coordinates(line1);
	line2 = coordinates(line2);
	var equation = obtention_equation(line1);
	var slope1 = equation[0];
	var intercept1 = equation[1];
	var equation = obtention_equation(line2);
	var slope2 = equation[0];
	var intercept2 = equation[1];
	if(slope1 == 0)
	{
		if(slope2 == 0)
		{
			if(intercept1 != intercept2)
				{return false;}
			else
				{return "infinite";}
		}
		if(slope2 == "infinite")
		{
			var x = intercept2;
			var y = intercept1;
			return [x, y];
		}
		else
		{
			var y = intercept1;
			var x = (y - intercept2)/slope2
			return [x, y];
		}
	}
	else if(slope1 == "infinite")
	{
		if(slope2 == 0)
		{
			var x = intercept1;
			var y = intercept2;
			return [x, y];
		}
		if(slope2 == "infinite")
		{
			if(intercept1 != intercept2)
				{return false;}
			else
				{return "infinite";}
		}
		else
		{
			var x = intercept1;
			var y = slope2 * x + intercept2;
			return [x, y];
		}
	}
	else
	{
		if(slope2 == 0)
		{
			var y = intercept2;
			var x = (y - intercept1)/slope1
			return [x, y];
		}
		if(slope2 == "infinite")
		{
			var x = intercept2;
			var y = slope1 * x + intercept1;
			return [x, y];
		}
		else
		{
			var x = (intercept2 - intercept1) / (slope1 - slope2);
			var y = slope1 * x + intercept1;
			return [x, y];
		}
	}
}

function intersection_circle_line (circle, line, num) //Give the intersection between a line and a circle (there will normally be 2 intersections, num allows to choose if you want the first or the second one
{
	circle = coordinates(circle);
	line = coordinates(line);
	var center = circle[0];
	var radius = circle[1];

	var equation = obtention_equation(line);
	var slope = equation[0];
	var intercept = equation[1];

	if(slope == "infinite")
	{
		var a = 1;
		var b = -2*center[1];
		var c = Math.pow(center[1],2) - Math.pow(radius,2) + Math.pow(intercept-center[0],2);
		var delta = Math.pow(b,2) - 4*a*c;
		var x1 = intercept;
		var y1 = (-b+Math.sqrt(delta))/(2*a);
		var x2 = intercept;
		var y2 = (-b-Math.sqrt(delta))/(2*a);
	}
	else
	{
		var a = Math.pow(slope,2) + 1;
		var b = 2*(slope*intercept - center[0] - slope*center[1]);
		var c = Math.pow(intercept,2) -2*intercept*center[1] + Math.pow(center[1],2) + Math.pow(center[0],2) - Math.pow(radius,2);
		var delta = Math.pow(b,2) - 4*a*c;
		var x1 = (-b+Math.sqrt(delta))/(2*a);
		var y1 = slope*x1+intercept;
		var x2 = (-b-Math.sqrt(delta))/(2*a);
		var y2 = slope*x2+intercept;
	}
	if(num == 1)
	{
		return [x1,y1];
	}
	else if(num == 2)
	{
		return [x2,y2];
	}
}

function intersection_circle_circle (circle1, circle2, num) //Give the intersection between a circle and another circle (there will normally be 2 intersections, num allows to choose if you want the first or the second one
{
	circle1 = coordinates(circle1);
	circle2 = coordinates(circle2);
	var center1 = circle1[0];
	var x1 = center1[0];
	var y1 = center1[1];
	var r1 = circle1[1];

	var center2 = circle2[0];
	var x2 = center2[0];
	var y2 = center2[1];
	var r2 = circle2[1];

	var a = 2*(x1 - x2);
	var b = 2*(y1 - y2);
	var c = Math.pow(x1-x2,2) + Math.pow(y1-y2,2) - Math.pow(r1,2) + Math.pow(r2,2);
	var delta = Math.pow(2*a*c,2) - 4*(Math.pow(a,2)+Math.pow(b,2)) * (Math.pow(c,2) - Math.pow(b,2)*Math.pow(r2,2));

	var xp = x2 + (2*a*c - Math.sqrt(delta))/(2*(Math.pow(a,2)+Math.pow(b,2)));
	var xq = x2 + (2*a*c + Math.sqrt(delta))/(2*(Math.pow(a,2)+Math.pow(b,2)));

	if(y1 == y2)
	{
		var yp = y2 + b*0.5 + Math.sqrt(Math.pow(r1,2)-Math.pow( (2*c-Math.pow(a,2)) / (2*a) ,2) );
		var yq = y2 + b*0.5 - Math.sqrt(Math.pow(r1,2)-Math.pow( (2*c-Math.pow(a,2)) / (2*a) ,2) );
	}
	else
	{
		var yp = y2 + (c-a*(xp-x2))/b;
		var yq = y2 + (c-a*(xq-x2))/b;
	}
	if(num == 1)
	{
		return [xp,yp];
	}
	else if(num == 2)
	{
		return [xq,yq];
	}
}

function random_point_line (line) //Gives a random point located on the line within the geometry values
{
	line = coordinates(line);
	var equation = obtention_equation(line);
	var slope = equation[0];
	var intercept = equation[1];
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	//Determination of the abscissa interval for the point to remain visible
	var min_abscissa_bis = math.min((min_ordonate-intercept)/slope, (max_ordonate-intercept)/slope);
	var max_abscissa_bis = math.max((min_ordonate-intercept)/slope, (max_ordonate-intercept)/slope);
	min_abscissa = math.max(min_abscissa, min_abscissa_bis);
	max_abscissa = math.min(max_abscissa, max_abscissa_bis);
	//Determination of a random abscissa witin the interval
	var random_abscissa = math.random(min_abscissa, max_abscissa);
	var ordonate = slope*random_abscissa + intercept;
	return [random_abscissa, ordonate];
}

function middle_segment(segment) //Gives the coordinates of the middle of a segment
{
	segment = coordinates(segment);
	var a = segment[0];
	var b = segment[1];
	var x = a[0] + (b[0] - a[0])/2;
	var y = a[1] + (b[1] - a[1])/2;
	return [x, y];
}

function random_point_segment(segment) //Gives the coordinates of a random point on the segment
{
	segment = coordinates(segment);
	var a = segment[0];
	var b = segment[1];
	var rand = math.random(0.2,0.8);
	var x = a[0] + rand*(b[0] - a[0]);
	var y = a[1] + rand*(b[1] - a[1]);
	return [x, y];
}

function axial_symmetry_point(axis, point) //Symmetric of a point by an axis
{
	var equation_axis = obtention_equation(axis);
	var slope_axis = equation_axis[0];
	var intercept_axis = equation_axis[1];

	//We determine the equation of the line perpendicular to the axis passing by the point given in argument
	var slope_perp = perpendicular(axis, point)[0];
	var intercept_perp = perpendicular(axis, point)[1];

	//We set a point i at the intersection between these 2 lines
	var i = intersection_lines(axis, [slope_perp, intercept_perp])

	//We determine the symmetric of the point
	var sym = new Array();
	sym[0] = i[0] + i[0] - point[0];
	sym[1] = i[1] + i[1] - point[1];

	return sym;
}

function axial_symmetry_segment(axis, segment) //Symmetric of a segment by an axis
{
	var sym1 = axial_symmetry_point(axis, segment[0]);
	var sym2 = axial_symmetry_point(axis, segment[1]);
	return [sym1, sym2];
}

function distance_point_line(point, line) //Determination of a distance between a line and a point
{
	point = coordinates(point);
	line = coordinates(line);
	var equation_line = obtention_equation(line);
	var slope_line = equation_line[0];
	var intercept_line = equation_line[1];

	//Determination of the perpendicular equation to the line passing by the point given in argument
	var slope_perp = perpendicular(line, point)[0];
	var intercept_perp = perpendicular(line, point)[1];

	//We set a point at the intersection of the 2 lines
	var i = intersection_lines(line, [slope_perp, intercept_perp])

	return length_segment([point, i]);
}

function distance_line_line(line1, line2) //Determination of a distance between a line and a line
{
	var equation_line1 = obtention_equation(line1);
	var slope_line1 = equation_line1[0];
	var intercept_line1 = equation_line1[1];

	var equation_line2 = obtention_equation(line2);
	var slope_line2 = equation_line2[0];
	var intercept_line2 = equation_line2[1];

	//Determination of the perpendicular equation to line2 passing by the intercept of line1
	var slope_perp = perpendicular(line2, [0, intercept_line1])[0];
	var intercept_perp = perpendicular(line2, [0, intercept_line1])[1];

	//We set a point at the intersection of the 2 lines
	var i = intersection_lines(line2, [slope_perp, intercept_perp])

	return length_segment([[0, intercept_line1], i]);
}

function length_segment(segment)
{
	var a = coordinates(segment[0]);
	var b = coordinates(segment[1]);
	return Math.sqrt(Math.pow(a[0] - b[0],2) + Math.pow(a[1] - b[1],2));
}

function type_triangle(a, b, c)
{
	length_ab = length_segment([a, b])
	length_bc = length_segment([b, c])
	length_ac = length_segment([a, c])
	if(length_ab == length_bc && length_ab == length_ac)
	{
		return "equilateral";
	}
	else
	{
		if( Math.abs(length_ab + length_bc - length_ac) <= 1 || Math.abs(length_ab + length_ac - length_bc) <= 1 || Math.abs(length_ac + length_bc - length_ab) <= 1 )
		{
			return "flat";
		}
		else if(length_ab == length_bc || length_bc == length_ac || length_ab == length_ac)
		{
			if( (Math.pow(length_ab, 2) + Math.pow(length_bc, 2) == Math.pow(length_ac, 2)) || (Math.pow(length_ac, 2) + Math.pow(length_bc, 2) == Math.pow(length_ab, 2)) || (Math.pow(length_ac, 2) + Math.pow(length_ab, 2) == Math.pow(length_bc, 2)) )
			{
				return "isocele_right_angled";
			}
			else if(Math.abs(length_ab - length_bc) <= 2 && Math.abs(length_bc - length_ac) <= 2) //We make sure there is no possible mistake
			{
				return "ambiguous";
			}
			else
			{
				return "isocele";
			}
		}
		else if( (Math.pow(length_ab, 2) + Math.pow(length_bc, 2) == Math.pow(length_ac, 2)) || (Math.pow(length_ac, 2) + Math.pow(length_bc, 2) == Math.pow(length_ab, 2)) || (Math.pow(length_ac, 2) + Math.pow(length_ab, 2) == Math.pow(length_bc, 2)) )
		{
			if(Math.abs(length_ab - length_bc) >= 2 || Math.abs(length_bc - length_ac) >= 2  || Math.abs(length_ab - length_ac) >= 2) //We make sure there is no possible mistake
			{
				return "right_angled";
			}
			else
			{
				return "ambiguous";
			}
		}
		else if(Math.abs(length_ab - length_bc) >= 2 || Math.abs(length_bc - length_ac) >= 2  || Math.abs(length_ab - length_ac) >= 2) //We make sure there is no possible mistake
		{
			return "general";
		}
		else
		{
			return "ambiguous";
		}
	}
}

function resize_segment(segment, length)
{
	var point1 = coordinates(segment.point1);
	var point2 = coordinates(segment.point2);
	length = parseFloat(length);
	var equation = obtention_equation([point1,point2]);

	var circle = [point1, length];
	var possibility1 = intersection_circle_line (circle, equation, 1);
	var possibility2 = intersection_circle_line (circle, equation, 2);
	if(length_segment([point2, possibility1]) <=  length_segment([point2, possibility2]))
	{
		point2 = possibility1;
	}
	else
	{
		point2 = possibility2;
	}
	update_point(segment.point2, point2);
}

function resize_angle(angle, value)
{
	angle.point1.free();
	angle.point2.free();
	angle.point3.free();
	angle.setAngle(value);
	board.fullUpdate();
	/*
	To be done
	var point1 = coordinates(angle.point1);
	var point2 = coordinates(angle.point2);
	var point3 = coordinates(angle.point3);
	length = parseFloat(length);
	var equation = obtention_equation([point1,point2]);

	var circle = [point1, length];
	var possibility1 = intersection_circle_line (circle, equation, 1);
	var possibility2 = intersection_circle_line (circle, equation, 2);
	if(length_segment([point2, possibility1]) <=  length_segment([point2, possibility2]))
	{
		point2 = possibility1;
	}
	else
	{
		point2 = possibility2;
	}
	update_point(segment.point2, point2);
	*/
}

//Functions to trace graphical elements (points, lines, segments, figures)
function trace_point(point, name, color, label_display, label_type, fixed_position, visible, snapToGrid)
{
	var with_label = true;
	switch(label_display)
	{
		case "left":
			var label_offset = [-15,0];
			break;
		case "right":
			var label_offset = [5,0];
			break;
		case "top":
			var label_offset = [-5,8];
			break;
		case "bottom":
			var label_offset = [-5,-10];
			break;
		case "center":
			var label_offset = [0,0];
			break;
		case false:
			var label_offset = [0,0];
			with_label = false;
			break;
	}

	var label = name;
	if(label_type != "name")
	{
		//Never used yet label = "";
	}
	var size = 1;
	var showInfobox = true;
	if(fixed_position == false){
		size = 2;
		showInfobox = false;
	}
	return board.create('point',point, {name:name, withLabel: with_label, label: {offset:label_offset}, face:'[]', Color: color, strokeColor:color, size:size, showInfobox:showInfobox, snapToGrid:snapToGrid, fixed:fixed_position, visible:visible });
}

function trace_glider(glider, name, color, label_display, label_type, fixed_position, visible)
{
	var with_label = true;
	switch(label_display)
	{
		case "left":
			var label_offset = [-15,0];
			break;
		case "right":
			var label_offset = [5,0];
			break;
		case "top":
			var label_offset = [-5,8];
			break;
		case "bottom":
			var label_offset = [-5,-10];
			break;
		case "center":
			var label_offset = [0,0];
			break;
		case false:
			var label_offset = [0,0];
			with_label = false;
			break;
	}

	var label = name;
	if(label_type != "name")
	{
		//Never used yet label = "";
	}
	var size = 1;
	var showInfobox = true;
	if(fixed_position == false){
		size = 2;
		showInfobox = false;
	}
	return board.create('glider', glider, {name:name, withLabel: with_label, label: {offset:label_offset}, face:'[]', Color: color, strokeColor:color, size:size, showInfobox:showInfobox, fixed:fixed_position, visible:visible});
}

function trace_segment(segment, name, color, label_display, label_type, precision, fixed_position, visible, dash)
{
	if(label_display == "top")
	{
		var offset_label = [0, 20];
		show_label= true;
	}
	else if(label_display == "bottom")
	{
		var offset_label = [0, -20];
		show_label= true;
	}
	else if(label_display == "right")
	{
		var offset_label = [30, 0];
		show_label= true;
	}
	else if(label_display == "left")
	{
		var offset_label = [-90, 0];
		show_label= true;
	}
	else if(label_display == "center")
	{
		var offset_label = [0, 0];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	var dash_type = 0;
	if(dash == true)
	{
		dash_type = 1;
	}
	return board.createElement('line',segment, {straightFirst:false, straightLast:false, strokeWidth:2, strokeColor:color, withLabel: show_label, fixed:fixed_position, visible:visible, dash:dash_type,
	name: function () {
		if(label_type == "length")
		{
			var length = parseFloat(segment[0].Dist(segment[1]));
			length = math.round(precision*math.round(length/precision),4);
			values[name+"Length"] = length;
			if(expressions[name] == undefined){
				var nameSegment = "[" + segment[0].name + segment[1].name + "]";
			} else {
				var nameSegment = expressions[name];
			}
			length = "" + nameSegment + " = " + convert_EngToFr(length) + " cm";
			return length;
		}
		else if(label_type == "name")
		{
			return "["+expressions[name]+"]";
		}
		else
		{
			var length = parseFloat(segment[0].Dist(segment[1]));
			length = math.round(precision*math.round(length/precision),4);
			values[name+"Length"] = length;
		}
    },
    label: {
		offset: offset_label,
	}
	});
}

function trace_halfline(halfline, name, color, label_display, label_type, fixed_position, visible, dash)
{
	if(label_display == "top")
	{
		var offset_label = [0, 20];
		show_label= true;
	}
	else if(label_display == "bottom")
	{
		var offset_label = [0, -20];
		show_label= true;
	}
	else if(label_display == "right")
	{
		var offset_label = [30, 0];
		show_label= true;
	}
	else if(label_display == "left")
	{
		var offset_label = [-90, 0];
		show_label= true;
	}
	else if(label_display == "center")
	{
		var offset_label = [0, 0];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	var dash_type = 0;
	if(dash == true)
	{
		dash_type = 1;
	}
	return board.createElement('line',halfline, {straightFirst:false, straightLast:true, strokeWidth:2, strokeColor:color, withLabel: show_label, fixed:fixed_position, visible:visible, dash:dash_type,
	name: function () {
		if(label_type == "name")
		{
			return "["+name+")";
		}
    },
    label: {
		offset:offset_label,
	}
	});
}

function trace_line(line, name, color, label_display, label_type, fixed_position, visible, dash)
{
	if(line[0] != undefined && $.isNumeric(line[0])) //If the line is defined by slope and intercept
	{
		var slope = line[0];
		var intercept = line[1];
		if(slope != "infinite")
		{
			var x = [1, slope + intercept]; //We take the point of the line x=1
			var y = [0, intercept]; //We take the point of the line x=0
			line = [x, y];
		}
		else
		{
			var x = [intercept, 1];
			var y = [intercept, 0];
			line = [x, y];
		}
	}
	if(label_display == "top")
	{
		var offset_label = [0, 20];
		show_label= true;
	}
	else if(label_display == "bottom")
	{
		var offset_label = [0, -20];
		show_label= true;
	}
	else if(label_display == "right")
	{
		var offset_label = [30, 0];
		show_label= true;
	}
	else if(label_display == "left")
	{
		var offset_label = [-90, 0];
		show_label= true;
	}
	else if(label_display == "center")
	{
		var offset_label = [0, 0];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	var dash_type = 0;
	if(dash == true)
	{
		dash_type = 1;
	}
	return board.createElement('line',line, {straightFirst:true, straightLast:true, strokeWidth:2, strokeColor:color, withLabel: show_label, fixed:fixed_position, visible:visible, dash:dash_type,
	name: function () {
		if(label_type == "name" || label_type == undefined)
		{
			return "("+name+")";
		}
    },
    label: {
		offset:[0, 0],
		anchorX: 'middle',
        anchorY: 'middle',
        cssClass: 'line_labels',
        highlightCssClass: 'line_labels'
	}
	});
}

function trace_curve(curve, name, color, label_display, label_type, fixed_position, visible, dash)
{
	if(label_display == "top")
	{
		var offset_label = [0, 20];
		show_label= true;
	}
	else if(label_display == "bottom")
	{
		var offset_label = [0, -20];
		show_label= true;
	}
	else if(label_display == "right")
	{
		var offset_label = [30, 0];
		show_label= true;
	}
	else if(label_display == "left")
	{
		var offset_label = [-90, 0];
		show_label= true;
	}
	else if(label_display == "center")
	{
		var offset_label = [0, 0];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	var dash_type = 0;
	if(dash == true)
	{
		dash_type = 1;
	}
	return board.createElement('curve',curve, {straightFirst:false, straightLast:false, strokeWidth:2, strokeColor:color, withLabel: show_label, fixed:fixed_position, visible:visible, dash:dash_type,
	name: function () {
		if(label_type == "name")
		{
			return name;
		}
    },
    label: {
		offset:offset_label,
	}
	});
}

function trace_angle(angle, name, color, label_display, label_type, radius, precision, visible)
{
	var show_label = false;
	if(label_display != false)
	{
		show_label= true;
	}
	return board.createElement('angle',angle, { radius:radius, fillColor:color, fillOpacity: 0.2, strokeColor:color, withLabel: show_label, visible: visible,
	name: function () {
		if(label_type == "value")
		{
			var angle_value = JXG.Math.Geometry.trueAngle(angle[0], angle[1], angle[2]);
			angle_value = math.round(precision*math.round(angle_value/precision),4);
			values[name+"Value"] = angle_value;
			angle_value = convert_EngToFr(angle_value) +"°";
			return angle_value;
		}
		else
		{
			var angle_value = JXG.Math.Geometry.trueAngle(angle[0], angle[1], angle[2]);
			angle_value = math.round(precision*math.round(angle_value/precision),4);
			values[name+"Value"] = angle_value;
			return "";
		}
    }
	});
}

function trace_arc(arc, name, color, label_display, label_type, precision, fixed_position, visible) //Trace an arc
{

	var radius_label = coordinates(arc[0]);
	if(label_display != false)
	{
		var offset_label = [-radius_label*10, -radius_label*10];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	return board.createElement('arc',arc, { fillColor:color, strokeColor:color, fillOpacity: 0.2, strokeWidth:1, fixed:fixed_position, withLabel: show_label, visible: visible,
	name: function () {
		if(label_type == "radius")
		{
			var radius = length_segment([arc[0],arc[1]]);
			radius = math.round(precision*math.round(radius/precision),4);
			values[name+"Radius"] = radius;
			radius = "Rayon = " + convert_EngToFr(radius) +" cm";
			return radius;
		}
		else
		{
			var radius = length_segment([arc[0],arc[1]]);
			radius = math.round(precision*math.round(radius/precision),4);
			values[name+"Radius"] = radius;
			return "";
		}
	},
    label: {
		offset: [offset_label, offset_label]
	}
	});
}

function trace_circle(circle, name, color, label_display, label_type, precision, fixed_position, visible) //Trace a circle
{
	if($.isNumeric(circle[1]))
	{
		var radius_label = circle[1];
	}
	else
	{
		var radius_label = length_segment([coordinates(circle[0]), coordinates(circle[1])]);
	}
	if(label_display != false)
	{
		var offset_label = [-radius_label*10, -radius_label*10];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	return board.createElement('circle',circle, { fillColor:color, strokeColor:color, fillOpacity: 0.2, strokeWidth:1, fixed:fixed_position, withLabel: show_label, visible: visible,
	name: function () {
		if(this.radius != undefined && this.radius > 0)
		{
			var radius = this.radius;
		}
		else
		{
			var radius = this.gxtterm;
		}
		if(label_type == "perimeter")
		{
			var perimeter = 2*math.pi*radius;
			perimeter = math.round(precision*math.round(perimeter/precision),4);
			values[name+"Perimeter"] = perimeter;
			perimeter = "Périmètre = " + convert_EngToFr(perimeter) +" cm";
			return perimeter;
		}
		if(label_type == "area")
		{
			var radius = length_segment([coordinates(circle[0]), coordinates(circle[1])]);
			var area = math.pi*Math.pow(radius,2);
			area = math.round(precision*math.round(area/precision),4);
			values[name+"Area"] = area;
			area = "Aire = " + convert_EngToFr(area) +" cm²";
			return area;
		}
		if(label_type == "radius")
		{
			radius = math.round(precision*math.round(radius/precision),4);
			values[name+"Radius"] = radius;
			radius = "Rayon = " + convert_EngToFr(radius) +" cm";
			return radius;
		}
		else
		{
			return "";
		}
	},
    label: {
		offset: [offset_label, offset_label]
	}
	});
}

function trace_quadrilateral(quadrilateral, name, color, label_display, label_type, precision, fixed_position, visible) //Trace a quadrilateral
{
	if(label_display == "top")
	{
		offset_label = [0, 20];
		show_label= true;
	}
	else if(label_display == "bottom")
	{
		offset_label = [0, -20];
		show_label= true;
	}
	else if(label_display == "right")
	{
		offset_label = [30, 0];
		show_label= true;
	}
	else if(label_display == "left")
	{
		offset_label = [-90, 0];
		show_label= true;
	}
	else if(label_display == "center")
	{
		offset_label = [-50, 0];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	return board.createElement('polygon',quadrilateral, {fillColor:color, strokeColor:color, fillOpacity: 0.2, strokeWidth:1, fixed:fixed_position, withLabel: show_label, visible: visible, name: function () {
		if(label_type == "perimeter")
		{
			var perimeter = parseFloat(quadrilateral[0].Dist(quadrilateral[1]) + quadrilateral[1].Dist(quadrilateral[2]) + quadrilateral[2].Dist(quadrilateral[3]) + quadrilateral[3].Dist(quadrilateral[0]));
			perimeter = math.round(precision*math.round(perimeter/precision),4);
			values[name+"Perimeter"] = perimeter;
			perimeter = "Périmètre = " + convert_EngToFr(perimeter) + " cm";
			return perimeter;
		}
		else if(label_type == "area")
		{
			var area = parseFloat(quadrilateral[0].Dist(quadrilateral[1]) * quadrilateral[1].Dist(quadrilateral[2]));
			area = math.round(precision*math.round(area/precision),4);
			values[name+"Area"] = area;
			area = "Aire = " + convert_EngToFr(area) + " cm²";
			return area;
		}
		else
		{
			var area = parseFloat(quadrilateral[0].Dist(quadrilateral[1]) * quadrilateral[1].Dist(quadrilateral[2]));
			area = math.round(precision*math.round(area/precision),4);
			values[name+"Area"] = area;
		}
    },
    label: {
		anchorX: 'middle',
        anchorY: 'middle'
	}
	});
}

function trace_square(square, name, color, label_display, label_type,  precision, fixed_position, visible) //Adjust the point given in the expression to make a real square
{
	var a = square[0];
	var b = square[1];
	var c = square[2];
	var d = square[3];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
		d = coordinates(d);
	}
	var side = length_segment([a, b]);

	var equation = perpendicular([a,b], b);
	var circle = [b, side];
	c = intersection_circle_line (circle, equation, 2);

	var equation = perpendicular([a,b], a);
	var circle = [a, side];
	d = intersection_circle_line (circle, equation, 2);

	update_point(square[2], c);
	update_point(square[3], d);
	return trace_quadrilateral(square, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_rectangle(rectangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real rectangle
{
	var a = rectangle[0];
	var b = rectangle[1];
	var c = rectangle[2];
	var d = rectangle[3];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
		d = coordinates(d);
	}
	var side1 = length_segment([a, b]);
	var side2 = length_segment([b, c]);
	if(Math.abs(side2 - side1) <= 1){
		side2 = side2 - 1;
	}

	var equation = perpendicular([a,b], b);
	var circle = [b, side2];
	c = intersection_circle_line (circle, equation, 2);

	var equation = perpendicular([a,b], a);
	var circle = [a, side2];
	d = intersection_circle_line (circle, equation, 2);

	update_point(rectangle[2], c);
	update_point(rectangle[3], d);

	return trace_quadrilateral(rectangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_lozenge(lozenge, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real lozenge
{
	var a = lozenge[0];
	var b = lozenge[1];
	var c = lozenge[2];
	var d = lozenge[3];
	if(!$.isArray(a))	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
		d = coordinates(d);
	}
	var side = length_segment([a, b]);

	var circle = [b, side];
	var equation = ["infinite", c[0]];
	c = intersection_circle_line (circle, equation, 2);
	length_ab = length_segment([a, b]);
	length_bc = length_segment([b, c]);
	length_ac = length_segment([a, c]);
	if( Math.abs(Math.pow(length_ab, 2) + Math.pow(length_bc, 2) - Math.pow(length_ac, 2)) <= 15 )	{
		var equation = [2, c[0]];
		c = intersection_circle_line (circle, equation, 2);
	}
	var circle1 = [a, side];
	var circle2 = [c, side];
	d = intersection_circle_circle (circle1, circle2, 1);

	update_point(lozenge[2], c);
	update_point(lozenge[3], d);

	return trace_quadrilateral(lozenge, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_parallelogram(parallelogram, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real parallelogram
{
	var a = parallelogram[0];
	var b = parallelogram[1];
	var c = parallelogram[2];
	var d = parallelogram[3];
	if(!$.isArray(a))	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
		d = coordinates(d);
	}
	while (Math.abs(length_segment([a, b]) - length_segment([b, c])) <= 1 || Math.abs(Math.pow(length_segment([a, b]), 2) + Math.pow(length_segment([b, c]), 2) - Math.pow(length_segment([a, c]), 2)) <= 15 ){
		c = [c[0] + math.random(-1, 1), c[1] + math.random(-1, 1)];
	}


	var equation1 = parallel([b,c], a);
	var equation2 = parallel([a,b], c);

	d = intersection_lines (equation1, equation2);

	update_point(parallelogram[2], c);
	update_point(parallelogram[3], d);

	return trace_quadrilateral(parallelogram, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Trace a triangle
{
	if(label_display == "top")
	{
		offset_label = [0, 20];
		show_label= true;
	}
	else if(label_display == "bottom")
	{
		offset_label = [0, -20];
		show_label= true;
	}
	else if(label_display == "right")
	{
		offset_label = [30, 0];
		show_label= true;
	}
	else if(label_display == "left")
	{
		offset_label = [-90, 0];
		show_label= true;
	}
	else if(label_display == "center")
	{
		offset_label = [-50, 0];
		show_label= true;
	}
	else if(label_display == false)
	{
		var offset_label = [0, 0];
		show_label= false;
	}
	return board.createElement('polygon',triangle, {fillColor:color, strokeColor:color, fillOpacity: 0.2, strokeWidth:1, fixed:fixed_position, withLabel: show_label, visible: visible, name: function () {
		if(label_type == "perimeter")
		{
			var perimeter = parseFloat(triangle[0].Dist(triangle[1]) + triangle[1].Dist(triangle[2]) + triangle[2].Dist(triangle[0]));
			perimeter = math.round(precision*math.round(perimeter/precision),4);
			values[name+"Perimeter"] = perimeter;
			perimeter = "Périmètre = " + convert_EngToFr(perimeter) + " cm";
			return perimeter;
		}
		else if(label_type == "area")
		{
			var middle = middle_segment([triangle[1], triangle[2]]);
			var area = parseFloat(triangle[1].Dist(triangle[2]) * length_segment([triangle[0],middle]));
			area = math.round(precision*math.round(area/precision),4);
			values[name+"Area"] = area;
			area = "Aire = " + convert_EngToFr(area) + " cm²";
			return area;
		}
		else
		{
			var middle = middle_segment([triangle[1], triangle[2]]);
			var area = parseFloat(triangle[1].Dist(triangle[2]) * length_segment([triangle[0],middle]));
			area = math.round(precision*math.round(area/precision),4);
			values[name+"Area"] = area;
		}
    },
    label: {
		anchorX: 'middle',
        anchorY: 'middle'
	}
	});
}

function trace_iso_rightangle_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real isocel rightangle triangle
{
	var a = triangle[0];
	var b = triangle[1];
	var c = triangle[2];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
	}
	var side = length_segment([a, b]);

	var equation = perpendicular([a,b], b);
	var circle = [b, side];
	c = intersection_circle_line (circle, equation, 2);
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	if(c[0] < min_abscissa || c[0] > max_abscissa || c[1] < min_ordonate || c[1] > max_ordonate)
	{
		c = intersection_circle_line (circle, equation, 1);
	}

	update_point(triangle[2], c);

	return trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_iso_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real isocel triangle
{
	var a = triangle[0];
	var b = triangle[1];
	var c = triangle[2];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
	}
	var side = 0.8*length_segment([a, b]);

	var circle1 = [b, side];
	var circle2 = [a, side];
	c = intersection_circle_circle (circle1, circle2, 1);
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	if(c[0] < min_abscissa || c[0] > max_abscissa || c[1] < min_ordonate || c[1] > max_ordonate)
	{
		c = intersection_circle_circle (circle1, circle2, 2);
	}

	update_point(triangle[2], c);

	return trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_rightangle_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real rightangle triangle
{
	var a = triangle[0];
	var b = triangle[1];
	var c = triangle[2];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
	}
	var side = 0.8*length_segment([a, b]);

	var equation = perpendicular([a,b], b);
	var circle = [b, side];
	c = intersection_circle_line (circle, equation, 2);
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	if(c[0] < min_abscissa || c[0] > max_abscissa || c[1] < min_ordonate || c[1] > max_ordonate)
	{
		c = intersection_circle_line (circle, equation, 1);
	}

	update_point(triangle[2], c);

	return trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_equi_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make a real equilateral triangle
{
	var a = triangle[0];
	var b = triangle[1];
	var c = triangle[2];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
	}
	var side = length_segment([a, b]);

	var circle1 = [b, side];
	var circle2 = [a, side];
	c = intersection_circle_circle (circle1, circle2, 1);
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	if(c[0] < min_abscissa || c[0] > max_abscissa || c[1] < min_ordonate || c[1] > max_ordonate)
	{
		c = intersection_circle_circle (circle1, circle2, 2);
	}

	update_point(triangle[2], c);

	return trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_obtuse_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make an obtuse triangle
{
	var a = triangle[0];
	var b = triangle[1];
	var c = triangle[2];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
	}

	var side = 0.8*length_segment([a, b]);
	var circle = [b, side];
	var equation = perpendicular([a,b], b);

	c = intersection_circle_line (circle, equation, 2);
	c[1] = c[1] + 5;
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	if(c[0] < min_abscissa || c[0] > max_abscissa || c[1] < min_ordonate || c[1] > max_ordonate)
	{
		c = intersection_circle_line (circle, equation, 1);
		c[1] = c[1] + 5;
	}

	update_point(triangle[2], c);

	return trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_acute_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible) //Adjust the point given in the expression to make an obtuse triangle
{
	var a = triangle[0];
	var b = triangle[1];
	var c = triangle[2];
	if(!$.isArray(a))
	{
		a = coordinates(a);
		b = coordinates(b);
		c = coordinates(c);
	}

	var side = 0.8*length_segment([a, b]);
	var circle = [b, side];
	var equation = perpendicular([a,b], b);

	c = intersection_circle_line (circle, equation, 2);
	c[0] = c[0] + 8;
	var min_abscissa = challenge.view.geometry[0] + 5;
	var max_abscissa = challenge.view.geometry[2] - 5;
	var min_ordonate = challenge.view.geometry[3] + 3;
	var max_ordonate = challenge.view.geometry[1] - 3;
	if(c[0] < min_abscissa || c[0] > max_abscissa || c[1] < min_ordonate || c[1] > max_ordonate)
	{
		c = intersection_circle_line (circle, equation, 1);
		c[0] = c[0] + 8;
	}

	update_point(triangle[2], c);

	return trace_triangle(triangle, name, color, label_display, label_type, precision, fixed_position, visible);
}

function trace_label(expression, name, color, label_display, fixed_position, visible)
{
	var min_y = challenge.view.geometry[3];
	var max_y = challenge.view.geometry[1];
	var min_x = challenge.view.geometry[0];
	var max_x = challenge.view.geometry[2];
	if(label_display == "top")
	{
		var label_position = [min_x + 2, max_y - 2];
	}
	else if(label_display == "bottom")
	{
		var label_position = [min_x + 2, min_y + 2];
	}
	else if(label_display == "right")
	{
		var label_position = [max_x - 5, 0];
	}
	else if(label_display == "left")
	{
		var label_position = [min_x + 2, 0];
	}
	else if(label_display == "center")
	{
		var label_position = [-3, 0];
	}
	else if(label_display == false)
	{
		var label_position = [0, 0];
	}
	return board.createElement('text',[label_position[0],label_position[1],expression], {strokeColor:color, fixed: fixed_position, visible: visible});
}

//Function to initiate graph
function initiate_graph(box, axis, grid)
{
	//Bounding box must be given as [min_absissa, max_ordinate, max_abscissa, min_ordinate]
	//Axis can be set to true or false
	//Grid can be set to true or false
	board = JXG.JSXGraph.initBoard('graph_box', {boundingbox: box, axis:false, showCopyright:false, showNavigation:false, grid:false, keepaspectratio: true});
	if(axis == true)
	{
		yaxis = board.create('axis', [[0, 0], [0, 1]], {ticks: {label: {offset: [-25, -2]}}});
		xaxis = board.create('axis', [[0, 0], [1, 0]], {ticks: {label: {offset: [-8, -10]}}});
	}
	if(grid == true)
	{
		grid = board.create('grid', [], {strokeColor: "#000"});
	}
}

function initiate_styles_diagram()
{
	// Load the fonts
	Highcharts.createElement('link', {
	   href: 'https://fonts.googleapis.com/css?family=Dosis:400,600',
	   rel: 'stylesheet',
	   type: 'text/css'
	}, null, document.getElementsByTagName('head')[0]);

	Highcharts.theme = {
	   colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
		  "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
	   chart: {
		  backgroundColor: null,
		  style: {
			 fontFamily: "Dosis, sans-serif"
		  }
	   },
	   title: {
		  style: {
			 fontSize: '16px',
			 fontWeight: 'bold',
			 textTransform: 'uppercase'
		  }
	   },
	   tooltip: {
		  borderWidth: 0,
		  backgroundColor: 'rgba(219,219,216,0.8)',
		  shadow: false
	   },
	   legend: {
		  itemStyle: {
			 fontWeight: 'bold',
			 fontSize: '13px'
		  }
	   },
	   xAxis: {
		  gridLineWidth: 0.5,
		  gridLineColor: "#797979",
		  tickColor: "#797979",
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   yAxis: {
		  minorTickInterval: 'auto',
		  gridLineWidth: 0.5,
		  gridLineColor: "#797979",
		  minorGridLineColor: "#797979",
		  minorGridLineWidth: 0.5,
		  title: {
			 style: {
				textTransform: 'uppercase'
			 }
		  },
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   plotOptions: {
		  candlestick: {
			 lineColor: '#404048'
		  }
	   },


	   // General
	   background2: '#F0F0EA'

	};

	// Apply the theme
	Highcharts.setOptions(Highcharts.theme);
}

function initiate_spider_diagram(title, legend, series, showTooltip)
{
	initiate_styles_diagram();
	diagram = $('#graph_box').highcharts({
        chart: {
            polar: true,
            type: 'line'
        },
        title: {
            text: title,
            x: -30
        },
        pane: {
            size: '90%'
        },
        xAxis: {
            categories: legend,
            tickmarkPlacement: 'on',
            lineWidth: 0
        },
        yAxis: {
            gridLineInterpolation: 'polygon',
            lineWidth: 0,
			tickmarkPlacement: 'on',
            min: 0,
			minorTickInterval: 1,
			showLastLabel: true,
			offset: -10,
			labels: {
				style: {"color":"#797979","fontWeight":"bolder"},
				step: 1
			}
        },
		plotOptions: {
			series: {
				point: {
					events: {

						drag: function (e) {
							// Returning false stops the drag and drops. Example:
						},
						drop: function () {

						},
						click: function () {
							//Highcharts.numberFormat(e.y, 2) + this.category + this.series.name
						},
					}
				},
				stickyTracking: false
			}
		},
        tooltip: {
            shared: false,
			formatter: function () {
				if(showTooltip == true){
				   return  math.round(this.y,0);
				} else {
					return  "?";
				}
			}
        },
        legend: {
            align: 'right',
            verticalAlign: 'top',
            y: 70,
            layout: 'vertical'
        },
		credits: {
			enabled: false
		},
        series: series
    });
}

function initiate_bars_diagram(title, legend, series, showTooltip)
{
	if(diagram == null){
		initiate_styles_diagram()
	}
	diagram = new Highcharts.Chart({

		chart: {
			renderTo: 'graph_box',
			animation: false
		},

		title: {
			text: title
		},

		xAxis: {
			categories: legend
		},

		plotOptions: {
			series: {
				point: {
					events: {

						drag: function (e) {
							// Returning false stops the drag and drops. Example:
							/*
							if (e.newY > 300) {
								this.y = 300;
								return false;
							}
							*/
							if(diagram_temp[this.series.name] == undefined)
							{
								diagram_temp[this.series.name] = {};
								diagram_temp[this.series.name][this.category] = math.round(e.y, 0);
							}
							else
							{
								diagram_temp[this.series.name][this.category] = math.round(e.y, 0);
							}
							return math.round(e.y, 0);
						},
						drop: function () {

						},
						click: function (e) {
							if(selectedDiagram != null){
								selectedDiagram.update({ color: selectedDiagram.series.color }, false, false);
							}
							selectedDiagram = this;
							this.update({ color: '#c80505' }, true, false);
						}
					}
				},
				stickyTracking: false
			},
			column: {
				stacking: false
			},
			line: {
				cursor: 'ns-resize'
			}
		},

		tooltip: {
			shared: false,
			formatter: function () {
				if(showTooltip == true){
				   return  math.round(this.y,0);
				} else {
					return  "?";
				}
			}
		},

		credits: {
			enabled: false
		},

		series: series

	});
	for(var i = 0;i<series.length;i++){
		diagram_temp[diagram.series[i].name] = {};
		for(var j = 0;j<series[i].data.length;j++){
			diagram_temp[diagram.series[i].name][diagram.series[i].data[j].category] = math.round(diagram.series[i].data[j].y, 0);
		}
	}
	diagram.yAxis[0].axisTitle.attr({
        text: 'Valeurs'
    });
	diagram.setSize($("#graph_box").width(),$("#graph_box").height());
	diagram.redraw();
}

function initiate_circular_diagram(title, legend, series)
{
	if(legend == false)
	{
		var format = '{point.name}: ?';
		var tooltip = '<b>?</b>';
	}
	else
	{
		var format = '{point.name}: {point.y:0f}%';
		var tooltip = '<b>{point.y:0f}%</b> du total';
	}
	initiate_styles_diagram();
    diagram = $('#graph_box').highcharts({
        chart: {
            type: 'pie'
        },
        title: {
            text: title
        },
        subtitle: {
            text: ''
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: format
                }
            }
        },

        tooltip: {
            headerFormat: '',
            pointFormat: tooltip
        },
		credits: {
			enabled: false
		},
        series: series
    });
}

function initiate_small_bars(legend, series)
{
  plot3 = $.jqplot('smallBars', series, {
    // Tell the plot to stack the bars.
    stackSeries: true,
    captureRightClick: true,
	series: legend,
    seriesDefaults:{
      renderer:$.jqplot.BarRenderer,
	  pointLabels: { show: true },
      rendererOptions: {
          // Put a 30 pixel margin between bars.
          barMargin: 0,
          // Highlight bars when mouse button pressed.
          // Disables default highlighting on mouse over.
          highlightMouseDown: false,
		  barWidth: 200,
		  shadowOffset: 0
      }
    },
	axesDefaults: {
      min: 0
    },
	grid: {
      drawGridLines: false,
      gridLineColor: '#fff',
      background: '#fff',
      borderWidth: 0,
      shadow: false
    },
    axes: {
      xaxis: {
          renderer: $.jqplot.CategoryAxisRenderer
      },
      yaxis: {
        // Don't pad out the bottom of the data range.  By default,
        // axes scaled as if data extended 10% above and below the
        // actual range to prevent data points right on grid boundaries.
        // Don't want to do that here.
        padMin: 0
      },
	  xaxis: {
        show: false,
        showTicks: false,
        showTickMarks: false,
      }
    },
	credits: {
		enabled: false
	},
    legend: {
      show: true,
      location: 'e',
      placement: 'outside'
    }
  });
}


$(function(){

	$(document).on("keydown", function (event) {
	  var key = event.which || event.keyCode;
		//Enter Key
		if (key == 13){
			if(pause !=undefined && pause==true) //If the user has the right answer displayed, allows to switch to next exercise
			{
				//
			}
			else
			{
				if(challenge.type == 1){
					var filled = true;
					$("#challenge_content input").each(function(){
						if($(this).val() == ""){
							$(this).val(0);
							filled = false;
						}
					});
					if(filled){
						call_verification();
					};
				} else if(challenge.type != 2){
					call_verification();
				}
			}
		} else	if(key==111 && !pause && challenge.question.match(/trait_fraction.png/) && $("#challenge_content input:eq(0)").is(':focus')) {
			event.preventDefault();
			$("#challenge_content input:eq(1)").focus();
		} else {
			if($("#challenge_content input:eq(0)").length){
				var focused = 0;
				$("#challenge_content input").each(function(){
					if($(this).is(':focus')){
						focused = 1;
					}
				});
				if($("textarea[name=descriptif_bug]").is(':focus')){
					focused = 1;
				}
				if(focused == 0){
					$("#challenge_content input:eq(0)").focus();
				}
			}
		}
	});

});
