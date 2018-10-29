var dateFormat = "dd/mm/yy",
frenchOptions = {
	defaultDate: "-1w",
	changeMonth: true,
	changeYear: true,
	numberOfMonths: 1,
	closeText: 'Fermer',
	prevText: 'Précédent',
	nextText: 'Suivant',
	currentText: 'Aujourd\'hui',
	monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
	monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
	dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
	dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
	dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
	weekHeader: 'Sem.',
	dateFormat: 'dd/mm/yy',
	firstDay: 1
},
periodStartPicker = $( "#periodStart" ).datepicker(frenchOptions).on( "change", function() {
	periodEndPicker.datepicker( "option", "minDate", getDate( this ) );
});
frenchOptions.defaultDate = 0;
var periodEndPicker = $( "#periodEnd" ).datepicker(frenchOptions).on( "change", function() {
	periodStartPicker.datepicker( "option", "maxDate", getDate( this ) );
});

function getDate( element ) {
	var date;
	try {
		date = $.datepicker.parseDate( dateFormat, element.value );
	} catch( error ) {
		date = null;
	}
	return date;
}

$("#typeAnalysis").buttonset();
$("#loading").hide();
$("#confirmation").hide();

$(".titles").tooltip({
	show: {
		effect: "slideDown",
		delay: 250
	}
});

$("#dashboardBorders").css("height", 0.99*$(window).height() - $("#dashboardBorders").offset().top)

var dataDashboard;
var periodStart;
var periodEnd;
var typeAnalysis;

$("#displayAnalysis").on("click", function(){
	periodStart = $("#periodStart").val().substr(6,4) + "-" + $("#periodStart").val().substr(3,2) + "-" + $("#periodStart").val().substr(0,2);
	periodEnd = $("#periodEnd").val().substr(6,4) + "-" + $("#periodEnd").val().substr(3,2) + "-" + $("#periodEnd").val().substr(0,2);
	typeAnalysis = $("input[name=typeAnalysis]:checked").val();
	if(periodStart != "" && periodEnd != "" && typeAnalysis != undefined){
		$("#loading").show();
		$("#displayAnalysis").hide();
		$.ajax({
			url: '/app/controllers/ajax_admin.php',
			type :'POST',
			data: 'getDashboard='+typeAnalysis+'&periodStart='+periodStart+'&periodEnd='+periodEnd,
			dataType: 'json',
			success: function(result, statut){
				dataDashboard = result;
				$("#loading").hide();
				$("#displayAnalysis").show();
				$("#confirmation").hide();
				displayAnalysis();
			},
			error: function(result, statut, erreur){
				$("#confirmation").switchClass("vert", "rouge").html("Erreur : la requête a échoué !").show();
				$("#loading").hide();
				$("#displayAnalysis").show();
			},
			complete: function(result, statut){
			}
		});
	}
});

function displayAnalysis(){
	if(typeAnalysis == "playersPerDay"){
		initiatePlayersPerDay();
	} else if(typeAnalysis == "addiction"){
		initiateAddiction();
	} else if(typeAnalysis == "retention"){
		initiateRetention();
	} else if(typeAnalysis == "userNetFlow"){
		initiateUserNetFlow();
	} else if(typeAnalysis == "conversion"){
		initiateConversion();
	} else if(typeAnalysis == "AARRR"){
		initiateAARRR();
	} else if(typeAnalysis == "acquisition"){
		initiateAcquisition();
	}
}


function initiatePlayersPerDay(){
	initiateStylesDiagram();
	var dataPlayersPerDay = [];
	var periodStartDate = new Date(periodStart);
	var periodEndDate = new Date(periodEnd);

	while(periodStartDate <= periodEndDate){
		var dd = ((periodStartDate.getDate())>=10)? (periodStartDate.getDate()) : '0' + (periodStartDate.getDate());
		var mm = ((periodStartDate.getMonth()+1)>=10)?(periodStartDate.getMonth()+1):'0'+(periodStartDate.getMonth()+1);
    var yyyy = periodStartDate.getFullYear();
    var curentDate = yyyy + "-" + mm + "-" + dd;
		if(dataDashboard[curentDate] != undefined){
			var nbPlayersTemp = dataDashboard[curentDate];
		} else {
			var nbPlayersTemp = 0;
		}
		dataPlayersPerDay.push([Date.UTC(periodStartDate.getFullYear(), periodStartDate.getMonth(), periodStartDate.getDate()), nbPlayersTemp]);
  	var newDate = periodStartDate.setDate(periodStartDate.getDate() + 1);
	  periodStartDate = new Date(newDate);
  }
	Highcharts.chart('graphBox', {
        chart: {
            type: 'area'
        },
        title: {
            text: 'Nombre de joueurs connectés par jour du '+ $("#periodStart").val() +' au '+ $("#periodEnd").val()
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens'
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
						}
        },
        yAxis: {
            title: {
                text: 'Nbre de joueurs'
            },
            min: 0
        },
        tooltip: {
					formatter: function() {
						var d = new Date(this.x);
						var allMonths = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
						d = 'Le ' + d.getDate() +" "+ allMonths[d.getMonth()] +" "+ d.getFullYear() + ' :';
						if(this.y <= 1){
							var serieLegend = "joueur connecté";
						} else {
							var serieLegend = "joueurs connectés";
						}
						d += '<br><span style="color:'+this.color+'">' + this.y +" "+ serieLegend + '</span>'
						return d;
					}
        },

        plotOptions: {
            spline: {
                marker: {
                    enabled: true
                }
            }
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Nombre de joueurs connectés',
            data: dataPlayersPerDay
        }]
    });
}

function initiateAddiction(){
	initiateStylesDiagram();
	var categoriesAddiction = [];
	for(let i=1;i<=dataDashboard.length;i++){
		categoriesAddiction.push(i);
	}
	Highcharts.chart('graphBox', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Nombre de connexions pour les '+ math.sum(dataDashboard) + ' joueurs du '+ $("#periodStart").val() +' au '+ $("#periodEnd").val()
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens, une seule connexion max est comptée par jour'
        },
				xAxis: {
					categories: categoriesAddiction
        },
        yAxis: {
            title: {
                text: 'Nbre de joueurs'
            },
            min: 0
        },
        tooltip: {
          headerFormat: '<span class="md2" style="color:{series.color};font-size:20px;font-weight:bold;">{point.y:.0f}</span> joueurs se sont connectés <span class="mg2 md2" style="color:{series.color};font-size:20px;font-weight:bold;">{point.key}</span> fois.',
					pointFormat: '',
          shared: true,
          useHTML: true
        },

        plotOptions: {
					column: {
							pointPadding: 0.2,
							borderWidth: 0
					}
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Nbre de connexions par joueur',
            data: dataDashboard
        }]
    });
}

function initiateRetention(){
	initiateStylesDiagram();
	var nbPlayers = dataDashboard[0];
	dataDashboard.splice(0, 1);
	Highcharts.chart('graphBox', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Rétention des '+ nbPlayers +' joueurs inscrits entre le '+ $("#periodStart").val() +' et le '+ $("#periodEnd").val()
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens et sont exprimées en % du nbre de joueurs inscrits sur la période'
        },
				xAxis: {
					categories: ["Inscription", "1 jour", "2-3 jours", "4-7 jours", "1-2 sem", "2-3 sem", "3-4 sem", "4-5 sem", "5-6 sem", "6-7 sem", "7-8 sem", "2-3 mois", "3 mois et +"]
        },
        yAxis: {
            title: {
                text: '% des joueurs inscrits sur la période'
            },
            min: 0,
						max: 100
        },
        tooltip: {
          headerFormat: '<span style="font-size:20px;">Connexion à {point.key} :</span><br><span style="color:{series.color};font-size:20px;font-weight:bold;">{point.y:.1f}</span> %',
					pointFormat: '',
          shared: true,
          useHTML: true
        },

        plotOptions: {
					column: {
							pointPadding: 0.2,
							borderWidth: 0
					}
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Funnel de conversion des joueurs',
            data: dataDashboard
        }]
    });
}

function initiateUserNetFlow(){
	initiateStylesDiagram();
	var maxInactivityPeriod = dataDashboard[0];
	dataDashboard.splice(0, 1);
	Highcharts.chart('graphBox', {
        chart: {
            type: 'waterfall'
        },
        title: {
            text: 'Joueurs actifs gagnés et perdus entre le '+ $("#periodStart").val() +' et le '+ $("#periodEnd").val()
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens et un joueur est considéré comme perdu après '+ maxInactivityPeriod +' jours d\'inactivité'
        },
        tooltip: {
          headerFormat: '<span style="color:{point.color};font-size:20px;font-weight:bold;">{point.y:.0f}</span>',
					pointFormat: '',
          shared: true,
          useHTML: true
        },
				xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Nombre d\'utilisateurs actifs'
            }
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
				series: [{
            upColor: Highcharts.getOptions().colors[3],
            color: Highcharts.getOptions().colors[2],
            data: [{
                name: 'Active users at period start',
                y: dataDashboard[0],
								color: Highcharts.getOptions().colors[0]
            }, {
                name: 'Churn<br>(lost users)',
                y: dataDashboard[1]
            }, {
                name: 'Acquired activated users',
                y: dataDashboard[2]
            }, {
                name: 'Active users at period end',
                isSum: true,
                color: Highcharts.getOptions().colors[0]
            }],
            dataLabels: {
                enabled: true,
                formatter: function () {
                    return Highcharts.numberFormat(this.y, 0, ',') + " users";
                },
                style: {
                    fontWeight: 'bold'
                }
            },
            pointPadding: 0
        }]
    });
}

function OLD_initiateUserNetFlow(){
	initiateStylesDiagram();
	var nbPlayers = dataDashboard[0];
	dataDashboard.splice(0, 1);
	Highcharts.chart('graphBox', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Bilan des joueurs gagnés et perdus entre le '+ $("#periodStart").val() +' et le '+ $("#periodEnd").val() + ' <br>(' + nbPlayers +' joueurs au total)'
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens et un joueur est considéré comme perdu après 15j d\'inactivité'
        },
        tooltip: {
          headerFormat: '<span style="color:{point.color};font-size:20px;font-weight:bold;">{point.y:.1f}</span> %',
					pointFormat: '',
          shared: true,
          useHTML: true
        },

        plotOptions: {
					pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<span style="font-size:20px;">{point.name}</span>'
                }
            }
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Joueurs gagnés et perdus',
            colorByPoint: true,
            data: [{
                name: 'Joueurs gagnés',
                y: dataDashboard[0]
            }, {
                name: 'Joueurs gagnés mais pas gardés',
                y: dataDashboard[1]
            }, {
                name: 'Joueurs perdus',
                y: dataDashboard[2]
            }]
        }]
    });
}

function initiateConversion(){
	initiateStylesDiagram();
	var nbPlayers = dataDashboard[0];
	dataDashboard.splice(0, 1);
	Highcharts.chart('graphBox', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Conversion des '+ nbPlayers +' joueurs inscrits entre le '+ $("#periodStart").val() +' et le '+ $("#periodEnd").val()
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens et sont exprimées en % du nbre de joueurs inscrits sur la période'
        },
				xAxis: {
					categories: ["Inscrits", "Reconnectés", "Limite atteinte", "Pass acheté"]
        },
        yAxis: {
            title: {
                text: '% des joueurs inscrits sur la période'
            },
            min: 0,
						max: 100
        },
        tooltip: {
          headerFormat: '<span style="font-size:20px;">{point.key} :</span><br><span style="color:{series.color};font-size:20px;font-weight:bold;">{point.y:.1f}</span> %',
					pointFormat: '',
          shared: true,
          useHTML: true
        },

        plotOptions: {
					column: {
							pointPadding: 0.2,
							borderWidth: 0
					}
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Funnel de conversion des joueurs',
            data: dataDashboard
        }]
    });
}

function initiateAARRR(){
	initiateStylesDiagram();
	Highcharts.chart('graphBox', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'AARRR données entre le '+ $("#periodStart").val() +' et le '+ $("#periodEnd").val()
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens'
        },
				xAxis: {
					categories: ["Acquisition<br>(#signup)", "Activation<br>(#tutorial complete)", "Retention<br>(#Active Users)", "Referral<br>(#sponsors)", "Revenue<br>(#subscriptions)"]
        },
        yAxis: {
            title: {
                text: 'Nombre'
            },
            min: 0,
        },
        tooltip: {
          headerFormat: '<span style="font-size:20px;">{point.key} :</span><br><span style="color:{series.color};font-size:20px;font-weight:bold;">{point.y:.0f}</span>',
					pointFormat: '',
          shared: true,
          useHTML: true
        },

        plotOptions: {
					column: {
							pointPadding: 0.2,
							borderWidth: 0
					}
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Données AARRR',
            data: dataDashboard
        }]
    });
}

function initiateAcquisition(){
	initiateStylesDiagram();
	var nbPlayers = math.sum(dataDashboard);
	Highcharts.chart('graphBox', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Répartition joueurs acquis entre le '+ $("#periodStart").val() +' et le '+ $("#periodEnd").val() + ' <br>(' + nbPlayers +' joueurs au total)'
        },
				subtitle: {
            text: 'Les données ne représentent que les collégiens'
        },
        tooltip: {
          headerFormat: '<span style="color:{point.color};font-size:20px;font-weight:bold;">{point.key} :<br>{point.y:.0f}</span>',
					pointFormat: '',
          shared: true,
          useHTML: true
        },

        plotOptions: {
					pie: {
                allowPointSelect: false,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<span style="font-size:20px;">{point.name}</span>'
                }
            }
        },
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
        series: [{
            name: 'Acquisition',
            colorByPoint: true,
            data: [{
                name: 'Demo in classrooms',
                y: dataDashboard[0]
            }, {
                name: 'Facebook campaigns',
                y: dataDashboard[1]
            }, {
                name: 'Twitter campaigns',
                y: dataDashboard[2]
            }, {
                name: 'Sponsorships',
                y: dataDashboard[3]
            }, {
                name: 'No invitation code',
                y: dataDashboard[4]
            }, {
                name: 'Others',
                y: dataDashboard[5]
            }]
        }]
    });
}

function initiateStylesDiagram(){
	$("#challenge_content").empty();
	$("<div id='graphBox'></div>").appendTo("#challenge_content");
	if(Highcharts.theme == undefined){
		// Load the fonts
		Highcharts.createElement('link', {
		   href: 'https://fonts.googleapis.com/css?family=Dosis:400,600',
		   rel: 'stylesheet',
		   type: 'text/css'
		}, null, document.getElementsByTagName('head')[0]);

		Highcharts.theme = {
		   colors: ["#c80505", "#8a8a8f", "#666", "#FE3C36", "#ffe4bd", "#FFFFC6"],
		   chart: {
			  backgroundColor: null,
			  style: {
				 fontFamily: "Dosis, sans-serif"
			  }
		   },
		   title: {
			  style: {
				 fontSize: '20px',
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
			  shadow: false,
				style: {
				 fontSize: '14px',
				 fontWeight: 'bold'
			  }
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


$(window).load(function(){
	$("#rideau_haut").hide("slide",{easing:"easeInExpo", direction: "up"}, "slow");
	$("#rideau_bas").hide("slide",{easing:"easeInExpo", direction: "down"}, "slow");
});
