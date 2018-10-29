<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="57x57" href="/webroot/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/webroot/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/webroot/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/webroot/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/webroot/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/webroot/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/webroot/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/webroot/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/webroot/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/webroot/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/webroot/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/webroot/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/webroot/favicon/favicon-16x16.png">
  <link rel="icon" type="image/png" href="/webroot/favicon/favicon.png"/>
  <link rel="manifest" href="/webroot/favicon/manifest.json">

  <title>β - Navadra</title>


  <!-- Feuilles de styles jQuery -->
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.css?nvd_r=xxx"/>
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.structure.css?nvd_r=xxx"/>
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.theme_grand.css?nvd_r=xxx" media="only screen and (min-width: 1751px)"/>
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.theme.css?nvd_r=xxx" media="only screen and (min-width: 992px) and (max-width: 1750px)"/>
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.theme_petit.css?nvd_r=xxx" media="only screen and (max-width: 991px)"/>


  <!-- Feuille de styles JSXGraph -->
  <link rel="stylesheet" href="/vendor/jsxgraph/jsxgraph.css?nvd_r=xxx"/>

  <!-- Feuille de styles Jqplot -->
  <link rel="stylesheet" type="text/css" href="/vendor/jqplot/jquery.jqplot.css?nvd_r=xxx"/>

  <!-- Feuilles de styles en fonction largeur écran -->
  <link rel="stylesheet" href="/webroot/css/styles_grands_ordis.css?nvd_r=xxx" media="only screen and (min-width: 1751px)" />
  <link rel="stylesheet" href="/webroot/css/styles_ordis.css?nvd_r=xxx" media="only screen and (min-width: 992px) and (max-width: 1750px)"/>
  <link rel="stylesheet" href="/webroot/css/styles_tablettes.css?nvd_r=xxx" media="only screen and (min-width: 769px) and (max-width: 991px)"/>
	<link rel="stylesheet" href="/webroot/css/styles_mobiles.css?nvd_r=xxx" media="only screen and (max-width: 768px)" />

  <!-- jQuery et jQuery-ui -->
  <script language="javascript" type="text/javascript" src="/vendor/jquery/jquery.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/jquery-ui/jquery-ui.js?nvd_r=xxx"></script>

  <!-- Math.js -->
  <script src="/vendor/mathjs/math.js?nvd_r=xxx"></script>

	<!-- fixedTableRC -->
  <link rel="stylesheet" href="/vendor/meetselva/css/fixed_table_rc.css"/>

  <!-- Tablesorter
  Library to add the possibilty of sorting tables. Doc : https://github.com/christianbach/tablesorter
  So far, not usefull
  <script src="/vendor/tablesorter/jquery.tablesorter.min.js?nvd_r=xxx"></script>
  -->

  <!-- JSXGraph
  <script language="javascript" type="text/javascript" src="/vendor/jsxgraph/src/loadjsxgraph.js?nvd_r=xxx"></script>
  -->
  <script language="javascript" type="text/javascript" src="/vendor/jsxgraph/jsxgraphcore.js?nvd_r=xxx"></script>

  <!-- JQPlot -->
  <!--[if lt IE 9]><![endif]
  <script language="javascript" type="text/javascript" src="/vendor/jqplot/excanvas.js?nvd_r=xxx"></script> -->
  <script language="javascript" type="text/javascript" src="/vendor/jqplot/jquery.jqplot.min.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/jqplot/plugins/jqplot.barRenderer.min.js?nvd_r=xxx"></script>

   <!-- Highcharts -->
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/highcharts.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/highcharts-more.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/draggable-points.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/modules/data.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/modules/drilldown.js?nvd_r=xxx"></script>


  <?php $server = $_SERVER['SERVER_NAME'];
  //ONLY FOR ONLINE SERVER
  if($server != "localhost1"){ ?>
    <!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
    for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
    mixpanel.init("c8e37f4493d75b03cd0c1b3c22880d49");</script><!-- end Mixpanel -->


  <?php } else { //ONLY FOR LOCAL SERVER?>
  <!-- Bug when trying to connect
  <script src='https://cdn.slaask.com/chat.js'></script>
  <script>
      _slaask.init('0346f664252c4f2878ac2c0e28ee7fad');
  </script>
    -->

  <?php } ?>

  <!-- Captures d'écran
  Library that can reconstitute the active page based on its HMTL and CSS code to take a screenshot but occasionnaly generates bugs -->
  <script type="text/javascript" src="/vendor/html2Canvas/html2canvas.js?nvd_r=xxx"></script>



</head>

<body>

<?php if(isset($_SESSION["joueur"])){
	$music_settings = $joueur->music_settings();
	$position = $joueur->position();?>
    <div id="playerId" class="cache"><?= $joueur->id(); ?></div>
    <div id="playerPseudo" class="cache"><?= $joueur->pseudo(); ?></div>
    <div id="playerSexe" class="cache"><?= $joueur->sexe(); ?></div>
		<div id="e" class="cache"><?= $joueur->feminine("e"); ?></div>
    <div id="playerClasse" class="cache"><?= $joueur->classe(); ?></div>
		<div id="playerEmail" class="cache"><?= $joueur->email(); ?></div>
		<div id="playerEmail_parent" class="cache"><?= $joueur->email_parent(); ?></div>
    <div id="playerNiveau" class="cache"><?= $joueur->niveau(); ?></div>
    <div id="playerXp" class="cache"><?= $joueur->xp(); ?></div>
    <div id="playerPyrs_feu" class="cache"><?= $joueur->pyrs_feu(); ?></div>
    <div id="playerPyrs_eau" class="cache"><?= $joueur->pyrs_eau(); ?></div>
    <div id="playerPyrs_vent" class="cache"><?= $joueur->pyrs_vent(); ?></div>
    <div id="playerPyrs_terre" class="cache"><?= $joueur->pyrs_terre(); ?></div>
    <div id="playerElement" class="cache"><?= $joueur->element(); ?></div>
    <div id="playerElement_article" class="cache"><?= $joueur->element_article(); ?></div>
    <div id="playerElement_article2" class="cache"><?= $joueur->element_article2(); ?></div>
    <div id="playerProfil_elem" class="cache"><?= $joueur->profil_elem_decompose(); ?></div>
    <div id="playerPrestige" class="cache"><?= $joueur->prestige(); ?></div>
    <div id="playerPm_joueur" class="cache"><?= $joueur->pm(); ?></div>
    <div id="playerEndu_joueur" class="cache"><?= $joueur->endu(); ?></div>
    <div id="playerNb_combats" class="cache"><?= $joueur->nb_combats(); ?></div>
    <div id="playerTuto" class="cache"><?= $joueur->tuto(); ?></div>
    <div id="playerImg_joueur" class="cache"><?= $joueur->avatar_entier(); ?></div>
    <div id="playerPortrait" class="cache"><?= $joueur->portrait(); ?></div>
    <div id="playerFullPortrait" class="cache"><?= $joueur->full_portrait(); ?></div>
    <div id="playerTuteur" class="cache"><?= $joueur->tuteur(); ?></div>
    <div id="playerUnassignedChallenges" class="cache"><?= $joueur->stock_challenges(); ?></div>
    <div id="playerAssignedChallenges" class="cache"><?= $challenges_manager->get_assigned($joueur); ?></div>
    <div id="playerSoloMonsters" class="cache"><?= $monstres_manager->nb_monstres_solo($joueur); ?></div>
    <div id="playerMultiMonsters" class="cache"><?= $monstres_manager->nb_monstres_multi($joueur); ?></div>
    <!--div id="playerBulles_daide_vues" class="cache">< ?= $joueur->bulles_daide_vues(); ? ></div-->
    <div id="playerAdvanced_description" class="cache"><?= $joueur->advanced_description(); ?></div>
    <div id="playerVolumeMusic" class="cache"><?= $music_settings[0]; ?></div>
    <div id="playerVolumeSoundEffects" class="cache"><?= $music_settings[1]; ?></div>
    <div id="playerVolumeInterface" class="cache"><?= $music_settings[2]; ?></div>
    <div id="playerPosX" class="cache"><?= $position["posX"]; ?></div>
    <div id="playerPosY" class="cache"><?= $position["posY"]; ?></div>
		<div id="playerGameLimitation" class="cache"><?= $joueur->gameLimitation(); ?></div>
		<div id="playerFirstConnexion" class="cache"><?= $playerFirstConnexion; ?></div>
		<div id="playerExistingPayment" class="cache"><?= $existingPayment; ?></div>
		<div id="tutoFinishedToday" class="cache"><?= $tutoFinishedToday; ?></div>
		<div id="playerTotalSpells" class="cache"><?= $sorts_manager->nb_sorts($joueur); ?></div>
		<div id="timeSlot" class="cache"><?php if(is_array($timeSlot)){echo (implode(",", $timeSlot));} else{ echo ($timeSlot);}; ?></div>
<?php } ?>

<!-- Script pour toutes les pages -->
<script language="javascript" type="text/javascript" src="/webroot/js/header.js?nvd_r=xxx"></script>
