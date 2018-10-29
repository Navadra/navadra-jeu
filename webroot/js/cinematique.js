// JavaScript Document

$(function() {
    var iframe = $('#cinematique')[0];
    var player = $f(iframe);
    var status = $('.status');
		var video = "";
		var quality = "";

		$('#cinematique').css("width", $(window).width());
		$('#cinematique').css("height", $(window).height());

    // When the player is ready, add listeners for pause, finish, and playProgress
    player.addEvent('ready', function() {
        status.text('ready');
        player.addEvent('pause', onPause);
        player.addEvent('finish', onFinish);
        player.addEvent('playProgress', onPlayProgress);
    });

    // Call the API when a button is pressed

    function onPause(id) {
    }


    function onFinish(id) {
			$("#carte").css("z-index",50);
			display_clip($("#impressions"));
    }

		var skipVideo = false;
    function onPlayProgress(data, id) {
			/*
			skipVideo = true;
			if(data.seconds >= 10 && skipVideo){
				skipVideo = false;
				$("#sortir_intro").show("clip", {easing: "swing"}, 500);
			} */
    }

		setTimeout(function(){
			$("#sortir_intro").show("clip", {easing: "swing"}, 500);
		}, 5000);



		$("#impressions").css("position", "absolute").css("width", "40%").css("top", "30%").css("left", "30%").css("z-index",51).hide();
		$("#length").buttonset();
		$("#difficulty").buttonset();
		$("#error_impressions").dialog({
			autoOpen: false,
		});


		$(".validate_impressions").on("mousedown", function(){
			if($("#bof").is(':checked')){
				quality = $("#bof").val();
			}
			else if($("#sympa").is(':checked')){
				quality = $("#sympa").val();
			}
			else if($("#tropBien").is(':checked')){
				quality = $("#tropBien").val();
			}
			if(quality != ""){
				send_impressions();
			} else {
				$("#error_impressions").dialog("open");
			}
		});

		var pendingAjax = false;
		function send_impressions(){
			if(!pendingAjax){
				pendingAjax = true;
				var video = $("input[name=video]").val();
				$.ajax({
					url: '/app/controllers/ajax.php',
					type: 'POST',
					data: 'record_impression='+video+'&quality='+quality,
					dataType: 'html',
					success: function (result, status) {
						$(location).attr('href',"/index.php");
					},
					error: function (result, status, error) {
						pendingAjax = false;
					  send_impressions();
					},
					complete: function (result, status) {

					}
				});
			}
		}

	$("#sortir_intro").hide();
	$("#sortir_intro").on("click", function(event){
		event.preventDefault();
		send_impressions();
	});

	/*
	if(typeof(mixpanel) != "undefined"){
		if(mixpanel.get_property('utm_source') != "-" && mixpanel.get_property('utm_campaign') != "-"){
			mixpanel.track("tutorial");
		}
	} */


});
