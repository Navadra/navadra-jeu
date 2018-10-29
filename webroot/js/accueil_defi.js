// JavaScript Document
var click_jouer = false;
var challengeLevel = parseFloat($("#challengeLevel").html());
var challengeTries = parseFloat($("#challengeTries").html());
var diagram;
var playerAvatar;

//Avoid double click on "Play"
$(".jouer").on("click", function (event) {
   event.preventDefault();
	if (click_jouer == false)
    {
	  click_jouer = true;
      $(location).attr('href', "/app/controllers/new_defi.php");
    }
});

$("#progress_graph").on("click", function(){
	$(".jouer").effect("pulsate", {easing: "swing"}, 750);
});

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
		  gridLineWidth: 1,
		  labels: {
			 style: {
				fontSize: '12px'
			 }
		  }
	   },
	   yAxis: {
		  minorTickInterval: 'auto',
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

function initiate_bars_diagram(title, legend, series)
{
	initiate_styles_diagram()
	diagram = new Highcharts.Chart({

		chart: {
			renderTo: 'progress_graph',
			animation: false
		},

		title: {
			text: ""
		},

		xAxis: {
			categories: legend,
			minorTickLength: 0,
			tickLength: 0,
			lineColor: 'transparent',
			gridLineColor: 'transparent'
		},

		yAxis: {
		   title: "",
		   lineWidth: 0,
		   minorGridLineWidth: 0,
		   lineColor: 'transparent',
		   gridLineColor: 'transparent',
		   labels: {
			   enabled: false
		   },
		   minorTickLength: 0,
		   tickLength: 0
		},

		plotOptions: {
			series: {
				point: {
					events: {

						drag: function (e) {
						},
						drop: function () {

						},
						click: function (e) {
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
			yDecimals: 1,
			enabled: false
		},

		credits: {
			enabled: false
		},

		series: series

	});
}

function color_bars(){
	for(var i = 1;i<= 5;i++){
		diagram.series[0].data[i-1].update({ color: '#7cb5ec' });
	}
	if(challengeLevel < 5){
		//diagram.series[0].data[4].update({ color: '#CFD0D1' });
	}
	if(challengeLevel == 6){
		for(var i = 1;i<= 5;i++){
			diagram.series[0].data[i-1].update({ color: '#73EB73' });
		}
		$("<img id='check1' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check1"), 1);
		});
		$("<img id='check2' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check2"), 2);
		});
		$("<img id='check3' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check3"), 3);
		});
		$("<img id='check4' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check4"), 4);
		});
		$("<img id='check5' class='img_30' src='/webroot/img/icones/check.png' />").appendTo("body").load(function(){
			position_check($("#check5"), 5);
		});
	}	else if(challengeTries != 0){
		diagram.series[0].data[challengeLevel-1].update({ color: '#c80505' });
	}
}

function position_images(){
	playerAvatar = $("#playerAvatar");
	playerAvatar.css("position", "absolute");
	if(challengeLevel < 5)	{
		/*
		$("<img id='lock' class='img_30' src='/webroot/img/icones/lock.png' />").appendTo("body").load(function(){
			$("#lock").css("position", "absolute");
			$("#lock").css("top", $("#progress_graph").offset().top + 0.25*$("#progress_graph").height() + diagram.series[0].data[4].plotY);
			var offsetX = 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*4.5/5 ;
			$("#lock").css("left", $("#progress_graph").offset().left + offsetX - 0.5*$("#lock").width());
		}); */
	}
	if(challengeTries == 0)	{
		playerAvatar.css("top", $("#progress_graph").offset().top + 0.1*$("#progress_graph").height());
		playerAvatar.css("left", $("#progress_graph").offset().left + 0.1*$("#progress_graph").width());
		$("<img id='question_mark' class='img_30' src='/webroot/img/icones/question_mark.png' />").appendTo("body").load(function(){
			$("#question_mark").css("position", "absolute");
			$("#question_mark").css("left", playerAvatar.offset().left + playerAvatar.width() - 1.1*$("#question_mark").width() );
			$("#question_mark").css("top", playerAvatar.offset().top + playerAvatar.height() - 1.1*$("#question_mark").height() );
		});
	}	else if (challengeLevel < 6) {
		playerAvatar.css("top", $("#progress_graph").offset().top + diagram.series[0].data[challengeLevel-1].plotY - 0.5*playerAvatar.height());
		var offsetX = 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*(challengeLevel-0.5)/5 ;
		playerAvatar.css("left", $("#progress_graph").offset().left + offsetX - 0.5*playerAvatar.width());
	}
	else if (challengeLevel == 6)	{
		playerAvatar.hide();
	}
}

function position_check(object, level){
	object.css("position", "absolute");
	var offsetY = $("#progress_graph").offset().top + diagram.series[0].data[level-1].plotY - 0.5*object.height();
	var offsetX = $("#progress_graph").offset().left + 0.02*$("#progress_graph").width() + 0.96*$("#progress_graph").width()*(level-0.5)/5 - 0.5*object.width();
	object.css("top", offsetY).css("left", offsetX);
}

$(window).load(function(){

var series = [];
series.push({
	name: "Niveaux",
	data: [1, 2, 3, 4, 5],
	draggableX: false,
	draggableY: false,
	dragMinY: 0,
	minPointLength: 1,
	type: "column",
	showInLegend: false
});
var legendDiag = ["Maîtrise 1", "Maîtrise 2", "Maîtrise 3", "Maîtrise 4", "Maîtrise 5"];
initiate_bars_diagram("Progression", legendDiag, series);

color_bars();
if(playerTuto == "fini"){
	position_images();
}



});
