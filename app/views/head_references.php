<!DOCTYPE html>
<html>
<head>
  <title>β - Navadra</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <!-- Favicon -->
  <link rel="shortcut icon" href="/webroot/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">

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


  <?php $server = $_SERVER['SERVER_NAME']; ?>
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
