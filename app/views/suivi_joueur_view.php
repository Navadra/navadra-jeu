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
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.theme.css?nvd_r=xxx" media="only screen and (min-width: 1025px) and (max-width: 1750px)"/>
  <link rel="stylesheet" href="/vendor/jquery-ui/jquery-ui.theme_petit.css?nvd_r=xxx" media="only screen and (max-width: 1024px)"/>


  <!-- Feuille de styles JSXGraph -->
  <link rel="stylesheet" href="/vendor/jsxgraph/jsxgraph.css?nvd_r=xxx"/>

  <!-- Feuille de styles Jqplot -->
  <link rel="stylesheet" type="text/css" href="/vendor/jqplot/jquery.jqplot.css?nvd_r=xxx"/>

  <!-- Feuilles de styles Navadra -->
  <link rel="stylesheet" href="/webroot/css/basic_styles.css?nvd_r=xxx"/>

  <!-- jQuery et jQuery-ui -->
  <script language="javascript" type="text/javascript" src="/vendor/jquery/jquery.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/jquery-ui/jquery-ui.js?nvd_r=xxx"></script>

  <!-- Math.js -->
  <script src="/vendor/mathjs/math.js?nvd_r=xxx"></script>


  <!-- JSXGraph
  <script language="javascript" type="text/javascript" src="/vendor/jsxgraph/src/loadjsxgraph.js?nvd_r=xxx"></script>
  -->
  <script language="javascript" type="text/javascript" src="/vendor/jsxgraph/jsxgraphcore.js?nvd_r=xxx"></script>

  <!-- JQPlot -->
  <!--[if lt IE 9]>
  <script language="javascript" type="text/javascript" src="/vendor/jqplot/excanvas.js?nvd_r=xxx"></script><![endif]-->
  <script language="javascript" type="text/javascript" src="/vendor/jqplot/jquery.jqplot.min.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/jqplot/plugins/jqplot.barRenderer.min.js?nvd_r=xxx"></script>

  <!-- Highcharts -->
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/highcharts.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/highcharts-more.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/draggable-points.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/modules/data.js?nvd_r=xxx"></script>
  <script language="javascript" type="text/javascript" src="/vendor/Highcharts/js/modules/drilldown.js?nvd_r=xxx"></script>

	<?php
	if($_SERVER['SERVER_NAME'] != "localhost"){ ?>
    <!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
    for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
    mixpanel.init("c8e37f4493d75b03cd0c1b3c22880d49");</script><!-- end Mixpanel -->
  <?php } ?>

</head>
<body class="cache">



<div id="notionsPracticed">
	<div class="container alignMiddle textCenter"></div>
</div>

<div id="notionsEvolution">
	<div class="container alignBottom textCenter"></div>
</div>

<div id="navadraRegularity">
	<div class="container alignMiddle textCenter"></div>
</div>


<div id="navadraProgress" class="relative">
	<div class="container alignMiddle">
        <div class="pfun p6 g l100 texte_centre mb2 mh2">Progression de <?=$player->firstname();?> sur Navadra</div>
        <div id="progressionDiagram" class="mh1"></div>
        <div id="progressionMessage" class="mh1 p2 messages l70 mg10 textLeft">
        	<ul>
                <li>A son arrivée sur Navadra, <?=$player->firstname();?> avait un niveau supérieur ou égal à <span class="rouge_fonce"><?= $initialRanking;?>%</span> des joueurs.</li>
                <li>Aujourd’hui, <?=$player->firstname();?> a un niveau supérieur ou égal à <span class="rouge_fonce"><?= $currentRanking;?>%</span> des joueurs.</li>
            </ul>
        </div>
	</div>
</div>

<div id="lockedContent" class="relative">
	<div class="container alignMiddle">
        <div class="pfun p6 g l100 texte_centre">Fin de la période d'essai :(</div>
        <div id="infoMessage" class="mh1 mb1 l60 mg20 g texte_centre">
        	Votre enfant a atteint le niveau <?=$player->niveau();?> sur Navadra ce qui met fin à la période de démonstration du jeu.<br /><?=$player->firstname();?> bénéficie toujours d'un accès gratuit mais dont le contenu est limité.
        </div>
        <div id="lockedTabs" class="l60 mg20">
          <ul>
            <li><a href="#fire">Nombres et Calculs</a></li>
            <li><a href="#water">Gestion de données et Fonctions</a></li>
            <li><a href="#wind">Espace et Géométrie</a></li>
            <li><a href="#earth">Grandeurs et Mesures</a></li>
          </ul>
          <div id="fire" class="p3 relative">
          	<span class="l60 ib">
            	<ul class="checklist">
            <?php foreach($lockedFireChallenges as $challenge){
                  	echo '<li class="ib l100 mh1 mb1">'.$challenge->notion().'</li>';
            }?>
            	</ul>
            </span>
            <span class="l35 mg2 ib absolu alignMiddle textCenter">
            	<img class="img_180" src="/webroot/img/icones/lock.png"/>
            </span>
          </div>
          <div id="water" class="p3 relative">
          	<span class="l60 ib">
            	<ul class="checklist">
            <?php foreach($lockedWaterChallenges as $challenge){
                  	echo '<li class="ib l100 mh1 mb1">'.$challenge->notion().'</li>';
            }?>
            	</ul>
            </span>
            <span class="l35 mg2 ib absolu alignMiddle textCenter">
            	<img class="img_180" src="/webroot/img/icones/lock.png"/>
            </span>
          </div>
          <div id="wind" class="p3 relative">
          	<span class="l60 ib">
            	<ul class="checklist">
            <?php foreach($lockedWindChallenges as $challenge){
                  	echo '<li class="ib l100 mh1 mb1">'.$challenge->notion().'</li>';
            }?>
            	</ul>
            </span>
            <span class="l35 mg2 ib absolu alignMiddle textCenter">
            	<img class="img_180" src="/webroot/img/icones/lock.png"/>
            </span>
          </div>
          <div id="earth" class="p3 relative">
          	<span class="l60 ib">
            	<ul class="checklist">
            <?php foreach($lockedEarthChallenges as $challenge){
                  	echo '<li class="ib l100 mh1 mb1">'.$challenge->notion().'</li>';
            }?>
            	</ul>
            </span>
            <span class="l35 mg2 ib absolu alignMiddle textCenter">
            	<img class="img_180" src="/webroot/img/icones/lock.png"/>
            </span>
          </div>
        </div>
        <div id="subscriptionMessage" class="mh1 p1 messages l50 mg20">
        	Débloquez le reste du contenu pédagogique pour profiter du plein potentiel de Navadra !
        </div>
        <div id="subscribeButton" class="mh1">Voir les Pass Navadra</div>
	</div>
</div>

<div id="teamWord" class="relative">
    <div class="container alignMiddle">
        <div class="pfun p6 g l100 texte_centre mb2">Le mot de l'équipe Navadra</div>
        <div class="l90 mg5">
            <div class="l30 ib md2">
                <img class="mg1 md1 l30 ib" src="/webroot/img/trombi_equipe/michel.png"/>
                <img class="mg1 md1 l30 ib" src="/webroot/img/trombi_equipe/jeremie.png"/>
                <img class="mg1 md1 l30 ib" src="/webroot/img/trombi_equipe/julien.png"/>
            </div>
            <div class="l60 ib p3 justif">
            « <span class="g">Notre ambition est de <span class="g">redonner aux jeunes le plaisir lié à l’apprentissage</span> pour que ce dernier ne soit plus vu comme une corvée mais comme un jeu ! »
            </div>
        </div>
	</div>
</div>

<img id="scrollUp" href="#" src="/webroot/img/icones/fleche_haut.png"/>
<img id="scrollDown" href="#" src="/webroot/img/icones/fleche_bas.png"/>


<!-- Hidden div, value recovered by JS -->
<div id="playerId" class="cache"><?= $player->id(); ?></div>
<div id="playerFirstname" class="cache"><?= $player->firstname(); ?></div>
<div id="playerLastname" class="cache"><?= $player->nom(); ?></div>
<div id="playerClass" class="cache"><?= $player->classe(); ?></div>
<div id="child" class="cache"><?= $child; ?></div>
<div id="e" class="cache"><?= $e; ?></div>
<div id="initialRanking" class="cache"><?= $initialRanking; ?></div>
<div id="currentRanking" class="cache"><?= $currentRanking; ?></div>
<div id="playerGameLimitation" class="cache"><?= $player->gameLimitation(); ?></div>
<div id="playerExistingPayment" class="cache"><?= $existingPayment; ?></div>

<script language="javascript" type="text/javascript" src="/webroot/js/suivi_joueur.js?nvd_r=xxx"></script>
</body>
</html>
