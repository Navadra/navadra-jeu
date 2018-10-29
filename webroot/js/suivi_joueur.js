//CHALLENGES PROGRESSION

//Variables related to player
var idPlayer = parseFloat($("#playerId").html());
var playerFirstname = $("#playerFirstname").html();
var playerLastname = $("#playerLastname").html();
var playerClass = $("#playerClass").html();
var child = $("#child").html();
var e = $("#e").html();
var initialRanking = parseFloat($("#initialRanking").html());
var currentRanking = parseFloat($("#currentRanking").html());
var playerGameLimitation = parseFloat($("#playerGameLimitation").html());
var playerExistingPayment = parseFloat($("#playerExistingPayment").html());

var challenges = []; //Global variable loaded with all target player's challenges
var challengeSelected; //Selected challenge on the bar diagram
var triesGetChallenges = 0; //Count the number of ajax tries, if too many unsuccessful attempts displays error message
var barDiagram;
var lineDiagram;
var practiceDiagram;
var rankingDiagram;
var exercises = []; //Global variable loaded with all target player's exercises
var triesGetExercises = 0; //Count the number of ajax tries, if too many unsuccessful attempts displays error message
var scores = []; //Global variable loaded with all target player's scores
var triesGetScores = 0; //Count the number of ajax tries, if too many unsuccessful attempts displays error message

var red = "#ba0019";
var blue = "#059cd3";
var yellow = "#d0a00a";
var green = "#1c9500";
var redHighlighted = "#F07F8E";
var blueHighlighted = "#A4DFF5";
var yellowHighlighted = "#F7DD8D";
var greenHighlighted = "#8FDE7C";
var lightRed = "#FA8F8C";
var darkRed = "#c80505";

//Get all player challenges
function getPlayerChallenges(){
	if(!$("#loadingBars").length){
		$("<img id='loadingBars' class='img_100' src='/webroot/img/icones/loading.gif'/>").appendTo("#notionsPracticed .container");
	}
	if(challenges.length == 0 && triesGetChallenges<3){
		triesGetChallenges ++;
		$.ajax({
		   url: '/app/controllers/ajax_disconnected.php',
		   type: 'POST',
		   data: 'historicChallenges='+idPlayer,
		   dataType: 'json',
		   success: function (result, status) {
			 challenges = result;
			 initiateSummaryGraph(); //Bars diagram
		   },
		   error: function (result, status, error) {
			 getPlayerChallenges(); //Auto-callback
		   },
		});
	} else if(challenges.length == 0 && triesGetChallenges==3){
		$("#notionsPracticed .container").empty();
		$("<img class='img_100 mb4' src='/webroot/img/icones/dommage.png'/>").appendTo("#notionsPracticed .container");
		$("<div class='p3 rouge g'>Désolé, votre connexion Internet ne permet pas de récupérer le résumé de l'entraînement de votre "+child+".</div>").appendTo("#notionsPracticed .container");
	}
}

//Initiate bar diagram with summary of practice sorted from the highest level to the lowest
function initiateSummaryGraph(){
	$("#notionsPracticed .container").empty();
	initiateStylesDiagram();

	//Get data for category summary
	var dataCategories = [];
	var fireLevels = [], waterLevels = [], windLevels = [], earthLevels = [];
	challenges.forEach(function(value, index){
		if(value.element == "fire"){
			fireLevels.push(parseInt(value.level));
		} else if(value.element == "water"){
			waterLevels.push(parseInt(value.level));
		} else if(value.element == "wind"){
			windLevels.push(parseInt(value.level));
		} else if(value.element == "earth"){
			earthLevels.push(parseInt(value.level));
		}
	});
	if(fireLevels.length > 0){
		dataCategories.push({
			name : "Nombres et Calculs",
			y : math.max(fireLevels),
			drilldown : "Nombres et Calculs"
		});
	}
	if(waterLevels.length > 0){
		dataCategories.push({
			name : "Gestion de données et Fonctions",
			y : math.max(waterLevels),
			drilldown : "Gestion de données et Fonctions"
		});
	}
	if(windLevels.length > 0){
		dataCategories.push({
			name : "Espace et Géométrie",
			y : math.max(windLevels),
			drilldown : "Espace et Géométrie"
		});
	}
	if(earthLevels.length > 0){
		dataCategories.push({
			name : "Grandeurs et Mesures",
			y : math.max(earthLevels),
			drilldown : "Grandeurs et Mesures"
		});
	}
	dataCategories.sort(function(a,b) {
		return b.y - a.y;
	});
	var colorTemp = [];
	dataCategories.forEach(function(value, index){
		if(value.name == "Nombres et Calculs"){
			colorTemp.push(red);
		} else if(value.name == "Gestion de données et Fonctions"){
			colorTemp.push(blue);
		} else if(value.name == "Espace et Géométrie"){
			colorTemp.push(yellow);
		} else if(value.name == "Grandeurs et Mesures"){
			colorTemp.push(green);
		}
	});
	Highcharts.theme.colors = colorTemp;
	Highcharts.theme.title.style.color = colorTemp;
	Highcharts.setOptions(Highcharts.theme);

	//Get data for each challenge
	var dataChallenges = [];
	var dataCat1 = {
		name: dataCategories[0].name,
        id: dataCategories[0].name,
        data: []
	}
	if(dataCategories[1] != undefined){
		var dataCat2 = {
			name: dataCategories[1].name,
			id: dataCategories[1].name,
			data: []
		}
	}
	if(dataCategories[2] != undefined){
		var dataCat3 = {
			name: dataCategories[2].name,
			id: dataCategories[2].name,
			data: []
		}
	}
	if(dataCategories[3] != undefined){
		var dataCat4 = {
			name: dataCategories[3].name,
			id: dataCategories[3].name,
			data: []
		}
	}
	challenges.forEach(function(value, index){
		if(value.category == dataCat1.name){
			dataCat1.data.push([value.notion,parseInt(value.level)]);
		} else if(dataCat2 != undefined && value.category == dataCat2.name){
			dataCat2.data.push([value.notion,parseInt(value.level)]);
		} else if(dataCat3 != undefined && value.category == dataCat3.name){
			dataCat3.data.push([value.notion,parseInt(value.level)]);
		} else if(dataCat4 != undefined && value.category == dataCat4.name){
			dataCat4.data.push([value.notion,parseInt(value.level)]);
		}
	});
	dataChallenges.push(dataCat1);
	if(dataCat2 != undefined){
		dataChallenges.push(dataCat2);
	}
	if(dataCat3 != undefined){
		dataChallenges.push(dataCat3);
	}
	if(dataCat4 != undefined){
		dataChallenges.push(dataCat4);
	}
	// Create the chart
    $('#notionsPracticed .container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Maîtrises atteintes par '+playerFirstname
        },
        subtitle: {
            text: 'Cliquez sur les colonnes pour afficher le détail',
						style: {
                color: '#c80505',
                fontWeight: 'bold',
								fontStyle: 'normal',
								fontSize: 20
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
			min: 0,
			max: 6,
			tickInterval: 1,
            title: {
                text: 'Maîtrise atteinte'
            },
			labels: {
				formatter: function () {
					if(this.value == 6){
						return "Maitrise<br>absolue";
					}else{
						return this.value;
					}
				}
			},
        },
        legend: {
            enabled: false
        },
		credits: {
			enabled: false
		},
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    format: ''
                },
				point: {
					events: {
						click: function (e) {
							if(this.name != undefined && this != challengeSelected){
								removeHighlightDiagram();
								challengeSelected = this;
								challenges.forEach(function(value, index){
									if(value.notion == challengeSelected.name){
										initiateProgressGraph(value);
										if(value.element == "fire"){
											var colorHighlight = redHighlighted;
										}else if(value.element == "water"){
											var colorHighlight = blueHighlighted;
										}else if(value.element == "wind"){
											var colorHighlight = yellowHighlighted;
										}else if(value.element == "earth"){
											var colorHighlight = greenHighlighted;
										}
										challengeSelected.update({ color: colorHighlight }, true, false);
										return false;
									}
								});
							}
						}
					}
				},
            }
        },

        tooltip: {
			headerFormat: '',
			formatter: function () {
				if(this.y == 6){
					return '<span style="color:'+this.color+'">'+this.key+'</span><br><span><b>Maîtrise absolue</b></span>';
				}else{
					return '<span style="color:'+this.color+'">'+this.key+'</span><br><span><b>Maîtrise '+this.y+'</b></span>';
				}
			}
        },

        series: [{
            name: 'Maîtrises atteintes par '+playerFirstname,
            colorByPoint: true,
            data: dataCategories
        }],
        drilldown: {
            series: dataChallenges
        }
    });
	barDiagram = $('#notionsPracticed .container').highcharts();
	barDiagram.setSize($("#notionsPracticed").width(),0.8*$("#notionsPracticed").height(),true);
	challengeSelected = undefined;
}

//Get all player exercises
function getPlayerExercises(){
	if(exercises.length == 0 && triesGetExercises<3){
		triesGetExercises ++;
		$.ajax({
		   url: '/app/controllers/ajax_disconnected.php',
		   type: 'POST',
		   data: 'historicExercises='+idPlayer,
		   dataType: 'json',
		   success: function (result, status) {
			 exercises = result;
			 $("#notionsEvolution .container").empty();
		   },
		   error: function (result, status, error) {
			 getPlayerExercises(); //Auto-callback
		   },
		});
	} else if(exercises.length == 0 && triesGetExercises==3){
		$("#notionsEvolution .container").empty();
		$("<img class='img_100 mb4' src='/webroot/img/icones/dommage.png'/>").appendTo("#notionsEvolution .container");
		$("<div class='p3 rouge g'>Désolé, votre connexion Internet ne permet pas de récupérer l'évolution de votre "+child+".</div>").appendTo("#notionsEvolution .container");
	}
}

//Line diagram
function initiateProgressGraph(challenge){
	$("#notionsEvolution .container").empty();
	if($("#notionsPracticed .container").hasClass("alignMiddle")){
		$("#notionsPracticed").css("height", 0.4*$(window).height())
		$("#notionsPracticed .container").switchClass("alignMiddle", "alignBottom", 0, "easeInOutCubic", function(){
			barDiagram.setSize($("#notionsPracticed").width(),$("#notionsPracticed").height(),false);
		});
	}

	if(challenge.element == "fire"){
		var colorTemp = red;
	} else if(challenge.element == "water"){
		var colorTemp = blue;
	} else if(challenge.element == "wind"){
		var colorTemp = yellow;
	} else if(challenge.element == "earth"){
		var colorTemp = green;
	}
	Highcharts.theme.colors = [colorTemp];
	Highcharts.theme.title.style.color = [colorTemp];
	Highcharts.setOptions(Highcharts.theme);

	var scoresTemp = [];
	var dates = [];
	var abscisses = [];
	var diagnosis;
	for(var i = 0; i<exercises.length ; i++){
		var ex = exercises[i];
		if(ex.challenge == challenge.name){
			if(ex.diagnosis==1){
				scoresTemp.push({
               		marker: {
                    	symbol: 'url(/webroot/img/icones/speedometer_small.png)'
					},
					y: ex.score
                });
			} else {
				scoresTemp.push(Math.min(6,ex.score));
			}
			diagnosis = ex.diagnosis;
			dates.push(formatDates(ex.date));
			abscisses.push(scoresTemp.length);
		}
	}
	$("#notionsEvolution").show();
	$("#notionsEvolution").switchClass("invisiblePart", "centerPart", 0, "easeInOutCubic", function(){
	$('#notionsEvolution .container').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: ''
        },
        xAxis: {
			title: {
                text: 'Nombre de questions'
            },
            categories: abscisses
        },
        yAxis: {
			min: 0,
			max: 6,
            title: {
                text: 'Maîtrise atteinte'
            },
            labels: {
                formatter: function () {
					if(this.value == 6){
                    	return "Maitrise absolue";
					}else if(this.value < 6){
                    	return this.value;
					} else {
						return "";
					}
                }
            },
			plotBands: [{
                from: 0,
                to: 1,
                color: 'rgba(240, 217, 67, 0.3)',
                label: {
                    text: '',
                    style: {
                        color: '#F0D943',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
                }
			}, {
                from: 1,
                to: 2.5,
                color: 'rgba(240, 217, 67, 0.3)',
                label: {
                    text: 'Appropriation',
                    style: {
                        color: '#F0D943',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
                }
            }, {
                from: 2.5,
                to: 4.5,
                color: 'rgba(250, 169, 77, 0.3)',
                label: {
                    text: 'Approfondissement',
                    style: {
                        color: '#FAA94D',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
                }
			}, {
                from: 4.5,
                to: 5,
                color: 'rgba(232, 70, 70, 0.3)',
                label: {
                    text: 'Maitrise',
                    style: {
                        color: '#E84646',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
				}
            }, {
                from: 5,
                to: 5.5,
                color: 'rgba(232, 70, 70, 0.3)',
                label: {
                    text: '',
                    style: {
                        color: '#E84646',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
				}
             }, {
				from: 5.5,
                to: 6.5,
                color: 'rgba(191, 48, 48, 0.3)',
                label: {
                    text: 'Maitrise Absolue',
                    style: {
                        color: '#BF3030',
						fontWeight: 'bold',
						fontSize: '14px'
                    }
				}
            }]
        },
        tooltip: {
            crosshairs: true,
			shared: true,
			formatter: function () {
				if(scoresTemp[this.x-1].marker != undefined){
					var scoreTemp = scoresTemp[this.x-1].y;
				} else {
					var scoreTemp = scoresTemp[this.x-1];
				}
				if(Math.round(scoreTemp) <= scoreTemp){
					var successTemp = "Bonne réponse";
				} else if(Math.round(scoreTemp) > scoreTemp){
					var successTemp = "Erreur";
				}
				if(Math.round(scoreTemp) == 6){
					return  dates[this.x-1]+"<br><b>Maîtrise absolue</b>";
				}else{
					return  dates[this.x-1]+"<br>Maîtrise <b>"+Math.round(scoreTemp)+"</b>";
				}
			}
        },
        plotOptions: {
            spline: {
                marker: {
                    radius: 4,
                    lineColor: '#666666',
                    lineWidth: 1
                }
            }
        },
        series: [{
            name: 'Score au défi '+challenge.notion,
            marker: {
                symbol: 'square'
            },
            data: scoresTemp
        }],
		credits: {
			enabled: false
		},
		legend: {
		  enabled: false
		}
    });
	lineDiagram = $('#notionsEvolution .container').highcharts();
	lineDiagram.setSize($("#notionsEvolution").width(),0.8*$("#notionsEvolution").height(),false);
	$("<div class='mb2'><img class='ib img_20' src='/webroot/img/icones/speedometer.png'/><span class='ib i'> : Calibrage initial du niveau des exercices</span></div>").appendTo("#notionsEvolution .container");
	activateBackButton();
	});
}

//Get all player scores
function getPlayerScores(){
	if(!$("#loadingLines").length){
		$("<img id='loadingLines' class='img_100' src='/webroot/img/icones/loading.gif'/>").appendTo("#navadraRegularity .container");
	}
	if(scores.length == 0 && triesGetScores<3){
		triesGetScores ++;
		$.ajax({
		   url: '/app/controllers/ajax_disconnected.php',
		   type: 'POST',
		   data: 'historicPractice='+idPlayer,
		   dataType: 'json',
		   success: function (result, status) {
			 scores = result;
			 initiatePracticeGraph(); //Line diagram
		   },
		   error: function (result, status, error) {
			 getPlayerScores(); //Auto-callback
		   },
		});
	} else if(scores.length == 0 && triesGetScores==3){
		$("#navadraRegularity .container").empty();
		$("<img class='img_100 mb4' src='/webroot/img/icones/dommage.png'/>").appendTo("#navadraRegularity .container");
		$("<div class='p3 rouge g'>Désolé, votre connexion Internet ne permet pas de récupérer le résumé de l'entraînement de votre "+child+".</div>").appendTo("#notionsPracticed .container");
	}
}

//Shows the practice of the player (challenges + fights)
function initiatePracticeGraph(){
	$("#navadraRegularity .container").empty();
	var dataChallenges = [];
	var dataFights = [];
	scores.forEach(function(value, index){
		var yearTemp = parseInt(value.date.substr(0,4));
		var monthTemp = parseInt(value.date.substr(5,2)) - 1;
		var dayTemp = parseInt(value.date.substr(8,2));
		if(value.type == "challenge"){
			dataChallenges.push([Date.UTC(yearTemp, monthTemp, dayTemp), value.count]);
		} else if(value.type == "fight"){
			dataFights.push([Date.UTC(yearTemp, monthTemp, dayTemp), value.count]);
		}
	});
	initiateStylesDiagram();
	Highcharts.theme.colors = [red, blue];
	Highcharts.theme.title.style.color = [red, blue];
	Highcharts.setOptions(Highcharts.theme);
	$('#navadraRegularity .container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Activité de '+playerFirstname+' sur Navadra'
        },
        xAxis: {
            type: 'datetime',
            labels: {
							formatter: function() {
								var d = new Date(this.value);
								var allMonths = ['Janv', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin',  'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
								d = d.getDate() +" "+ allMonths[d.getMonth()];
								return d;
							}
						},
            title: {
                text: 'Date'
            }
        },
        yAxis: {
            title: {
                text: ''
            },
            min: 0
        },
        tooltip: {
					formatter: function() {
						var d = new Date(this.x);
						var allMonths = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
						d = 'Le ' + d.getDate() +" "+ allMonths[d.getMonth()] +" "+ d.getFullYear() + ' :';
						var indexS = this.series.index;
						var indexP = this.x;
						var series = this.series.chart.series;
						if(indexS == 0){ //fight
							if(this.y == 1){
								var serieLegend = "combat réalisé";
							} else {
								var serieLegend = "combats réalisés";
							}
							d += '<br><span style="color:'+this.color+'">' + this.y +" "+ serieLegend + '</span>'
							series[1].data.forEach(function(value, index){
								if(value.x == indexP){
									if(value.y == 1){
										var serieLegend = "défi réalisé";
									} else {
										var serieLegend = "défis réalisés";
									}
									d += '<br><span style="color:'+value.color+'">' + value.y +" "+ serieLegend + '</span>'
									return false;
								}
							});
						} else if(indexS == 1){ //challenge
							series[0].data.forEach(function(value, index){
								if(value.x == indexP){
									if(value.y == 1){
										var serieLegend = "combat réalisé";
									} else {
										var serieLegend = "combats réalisés";
									}
									d += '<br><span style="color:'+value.color+'">' + value.y +" "+ serieLegend + '</span>'
									return false;
								}
							});
							if(this.y == 1){
								var serieLegend = "défi réalisé";
							} else {
								var serieLegend = "défis réalisés";
							}
							d += '<br><span style="color:'+this.color+'">' + this.y +" "+ serieLegend + '</span>'
						}
						return d;
					}
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            },
			series: {
				pointWidth: 10
			},
			column: {
				stacking: 'normal'
			}
        },
		credits: {
			enabled: false
		},

        series: [{
            name: 'Combats réalisés',
            data: dataFights
        }, {
			name: 'Défis réalisés',
            data: dataChallenges
        }]
    });
	practiceDiagram = $('#navadraRegularity .container').highcharts();
	practiceDiagram.setSize($("#navadraRegularity").width(),0.8*$("#navadraRegularity").height(),false);
}

//Function to initiate ranking comparison between inscription and today
function initiateRankingComparison(){
	var dataComparison = [];
	dataComparison.push({
			name : "Arrivée sur Navadra",
			y : initialRanking,
	});
	dataComparison.push({
			name : "Aujourd'hui",
			y : currentRanking,
	});
	$("#progressionDiagram").empty();
	initiateStylesDiagram();

	Highcharts.theme.colors = [lightRed, darkRed];
	Highcharts.theme.title.style.color = [lightRed, darkRed];
	Highcharts.setOptions(Highcharts.theme);

	// Create the chart
    $('#progressionDiagram').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
			min: 0,
			max: 100,
            title: {
                text: 'Classement (centile)'
            },
			labels: {
				formatter: function () {
					return (this.value) + "°";
				}
			},
        },
        legend: {
            enabled: false
        },
		credits: {
			enabled: false
		},
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: false,
                    format: '{point.y}'
                },
            }
        },

        tooltip: {
			headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span><br><span><b>{point.y}° centile</b></span>'
        },

        series: [{
            name: 'Classement (centile)',
            colorByPoint: true,
            data: dataComparison
        }],
    });
	rankingDiagram = $('#progressionDiagram').highcharts();
	$('#progressionDiagram').css("margin-left", "20%");
	rankingDiagram.setSize(0.6*$(window).width(),0.5*$(window).height(),false);
}

//Utilitary functions
function formatDates(dateTemp){
	return "Le "+ dateTemp.substring(8,10) + "/" + dateTemp.substring(5,7) + "/" + dateTemp.substring(0,4);
}

function initiateStylesDiagram(){
	if(Highcharts.theme == undefined){
		// Load the fonts
		Highcharts.createElement('link', {
		   href: 'https://fonts.googleapis.com/css?family=Dosis:400,600',
		   rel: 'stylesheet',
		   type: 'text/css'
		}, null, document.getElementsByTagName('head')[0]);

		Highcharts.theme = {
		   colors: ["#7cb5ec", "#f7a35c", "#90ee7e", "#7798BF"],
		   chart: {
			  backgroundColor: null,
			  style: {
				 fontFamily: "Dosis, sans-serif"
			  }
		   },
		   title: {
			  style: {
				 fontSize: '24px',
				 fontWeight: 'bold',
				 textTransform: 'uppercase'
			  }
		   },
		   subtitle: {
			  style: {
				 fontSize: '18px',
				 fontWeight: 'bold',
				 fontStyle: 'italic',
				 fontDecoration: 'underline',
				 color: '#AEB5B8'
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
				 fontSize: '18px'
			  }
		   },
		   xAxis: {
			  gridLineWidth: 0,
			  gridLineColor: "#797979",
			  tickColor: "#797979",
			  labels: {
				 style: {
					fontSize: '16px',
					fontWeight: 'bold'
				 }
			  },
			  title: {
				 style: {
					fontSize: '16px',
				 	fontWeight: 'bold'
				 }
			  }
		   },
		   yAxis: {
			  minorTickInterval: 'auto',
			  gridLineWidth: 1,
			  gridLineColor: "#797979",
			  minorGridLineColor: "#797979",
			  minorGridLineWidth: 0,
			  title: {
				 style: {
					textTransform: 'uppercase',
					fontSize: '16px',
				 	fontWeight: 'bold'
				 }
			  },
			  labels: {
				 style: {
					fontSize: '16px',
					fontWeight: 'bold'
				 }
			  }
		   },
		   plotOptions: {
			  candlestick: {
				 lineColor: '#404048'
			  },
		   },

		   // General
		   background2: '#F0F0EA'

		};
		// Apply the theme
		Highcharts.setOptions(Highcharts.theme);
	}
}

function removeHighlightDiagram(){
	if(challengeSelected != undefined && challengeSelected.color != null){
		if(challengeSelected.color == redHighlighted){
			var colorTemp = red;
		}else if(challengeSelected.color == blueHighlighted){
			var colorTemp = blue;
		}else if(challengeSelected.color == yellowHighlighted){
			var colorTemp = yellow;
		}else if(challengeSelected.color == greenHighlighted){
			var colorTemp = green;
		}
		challengeSelected.update({ color: colorTemp }, true, false);
	}
}

function activateBackButton(){
	$("tspan").unbind("click").on("click", function(){
		$("#notionsEvolution .container").empty();
		$("#notionsEvolution").hide();
		$("#notionsPracticed .container").empty();
		$("#notionsPracticed").css("height", $(window).height())
		$("#notionsPracticed .container").switchClass("alignBottom", "alignMiddle");
		initiateSummaryGraph();
	});
}

//Add the class "title" to any HTML element to use the jQuery-ui widget
$(".titles").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

//Initialization
if(playerClass != "Prof" && !playerExistingPayment){
	var pages = ["#notionsPracticed", "#navadraRegularity", "#navadraProgress", "#teamWord"];
	$("#lockedContent").hide();
} else {
	var pages = ["#notionsPracticed", "#navadraRegularity", "#navadraProgress"];
	$("#lockedContent").hide();
	$("#teamWord").hide();
}
var lastPage = pages.length-1;
var currentPage;

$("#notionsPracticed").css("height", $(window).height()).addClass("centerPart");
$("#notionsEvolution").css("height", 0.6*$(window).height()).addClass("invisiblePart").hide();
$("#navadraRegularity").css("height", $(window).height()).addClass("centerPart");
$("#navadraProgress").css("height", $(window).height());
$("#lockedContent").css("height", $(window).height());
$("#teamWord").css("height", $(window).height());
$('#subscribeButton').on('click', function() {
	window.open('https://www.navadra.com/pass/');
});
$("body").removeClass("cache");
getPlayerChallenges();
getPlayerExercises();
getPlayerScores();
initiateRankingComparison();
$("#scrollUp").hide();
$("#scrollDown").attr("href",pages[1]);
$("#lockedTabs").tabs({
	heightStyle: "auto"
});


var activeScroll = false;
function smoothScroll(target){
	if(activeScroll == false){
		changeScrollDestination(target);
		activeScroll = true;
		var speed = 1000; // Durée de l'animation (en ms)
		$('html, body').animate( { scrollTop: $(target).offset().top }, speed, "easeInOutCubic", function(){
			activeScroll = false;
		});
	}
}

function isScrolledIntoView(elem){
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom + 10) && (elemTop + 10 >= docViewTop));
}

function changeScrollDestination(target){
	if(target == pages[0]){
		$("#scrollUp").hide("fade", {easing: "swing"}, 900);
		$("#scrollDown").show("fade", {easing: "swing"}, 900);
		$("#scrollUp").attr("href", "#");
	} else if(target == pages[lastPage]){
		$("#scrollUp").show("fade", {easing: "swing"}, 900);
		$("#scrollDown").hide("fade", {easing: "swing"}, 900);
		$("#scrollDown").attr("href", "#");
	} else {
		$("#scrollUp").show("fade", {easing: "swing"}, 900);
		$("#scrollDown").show("fade", {easing: "swing"}, 900);
		currentPage = pages.indexOf(target);
		$("#scrollUp").attr("href", pages[currentPage-1]);
		$("#scrollDown").attr("href", pages[currentPage+1]);
	}
}

//When document ready
$(window).load(function(){

	pages.forEach(function(value){
		if(isScrolledIntoView(value)){
			changeScrollDestination(value);
		}
	});

	$("body").bind('mousewheel DOMMouseScroll', function(event){
		event.preventDefault();
		pages.forEach(function(value){
			if(isScrolledIntoView(value)){
				currentPage = pages.indexOf(value);
			}
		});
		if (event.originalEvent.wheelDelta > 0 || event.originalEvent.detail < 0) { //Scroll up
			if(currentPage != 0){
				smoothScroll(pages[currentPage-1]);
			}
		}
		else { //Scroll down
			if(currentPage != lastPage){
				smoothScroll(pages[currentPage+1]);
			}
		}
	});

	$("#scrollUp").on("click", function(){
		smoothScroll($(this).attr("href"));
	});

	$("#scrollDown").on("click", function(){
		smoothScroll($(this).attr("href"));
	});

	/*
	if(typeof(mixpanel) != "undefined"){
		if(window.location.search.match(/[&]?source=\w+[&]?/)) {
			var source = window.location.search.match(/[&]?source=(\w+)[&]?/)[1];
			if(source == "emailParent1"){
				mixpanel.track("ActionFollowingEmailParent1", {
					"type ":  "LookProgress"
				});
			} else if(source == "emailParent2"){
				mixpanel.track("ActionFollowingEmailParent2", {
					"type ":  "LookProgress"
				});
			}
		}
	} */


});
