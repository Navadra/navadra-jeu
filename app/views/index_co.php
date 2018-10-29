<?php if($timeSlot == "NoTimeSlot"){
if(isset($monstres) && is_array($monstres)){
	$countFights = count($monstres);
} else {
	$countFights = 0;
}?>
<img id="indicatorChallenges" title="Il te reste <?= $sum_challenges; ?> défi(s) aujourd'hui" src="/webroot/img/icones/indicatorChallenges.png" />
<img id="countChallengesBackground" src="/webroot/img/icones/backgroundIndicators.png" />
<div id="countChallenges"><?= $sum_challenges; ?></div>

<img id="indicatorFights" title="Il te reste <?= $countFights; ?> combat(s) aujourd'hui" src="/webroot/img/icones/indicatorFights.png" />
<img id="countFightsBackground" src="/webroot/img/icones/backgroundIndicators.png" />
<div id="countFights"><?= $countFights; ?></div>
<?php } ?>

<?php if($joueur->tuto() == "fini" && $codes_manager->rewardObtained($joueur) == 0){ ?>
<a href="/app/controllers/profil.php?id=<?= $joueur->id(); ?>&tab=sponsor"><img id="treasureMap" src="/webroot/img/icones/treasureMap.png" /></a>
<?php } ?>

<!-- Si le tuto est fini juste depuis aujourd'hui, le tuteur n'apparait pas -->
<?php if($joueur->tuto() == "fini") { ?>
    <!-- Tuteur -->
    <img id="tuteur_index" src="<?= $joueur->img_tuteur(); ?>" />

    <!-- Bulle du personnage si tuto fini-->
    <div id="bulle_index" class="bulle">
        <span class="g" id="nom_tuteur"><?= $joueur->tuteur(); ?></span>
        <!-- le texte qui apparaitra dans la bulle -->
        <span id="txt_bulle">
        <?= $msg_tuteur; ?>
        </span>
    </div>
    <img id="lancer_defi" src="/webroot/img/icones/play_inverse.png" />

<?php
	if ($joueur->nb_jours_fin_tuto() == 0 && $sum_challenges > 0){
		echo('<img id="spotTraining" class="img_120" src="/webroot/img/icones/fleche4.png" />');
	}
} elseif($joueur->tuto() != "fini"){ ?>
    <!-- Tuteur -->
    <img id="tuteur_index" src="<?= $joueur->img_tuteur(); ?>" />

    <!-- Bulle du personnage si tuto non fini-->
    <div id="bulle_index" class="bulle cache">
        <span class="g" id="nom_tuteur"><?= $joueur->tuteur(); ?></span>
        <!-- le texte qui apparaitra dans la bulle -->
        <span id="txt_bulle">
        </span>
    </div>

    <div id="commandes_tuto_index">
        <img id="tuto_precedent" src="/webroot/img/icones/chevron1.png" />
        <img id="tuto_suivant" src="/webroot/img/icones/chevron2.png" />
    </div>

    <!-- Div cachées utilisées par JS-->
    <div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
    <div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
    <div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
    <div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
    <div id="nb_prestige" class="cache"><?= $joueur->prestige(); ?></div>
    <?php
		if(isset($combat)){echo('<div id="id_combat" class="cache">'.$combat->id().'</div>');}
    if(isset($monstre)){echo('<div id="id_monstre" class="cache">'.$monstre->id().'</div>');}
		if(isset($winLastFight)){echo('<div id="winLastFight" class="cache">'.$winLastFight.'</div>');}
		if(isset($prestigeLastFight)){echo('<div id="prestigeLastFight" class="cache">'.$prestigeLastFight.'</div>');}
}

/* KILL THE CHAT WHILE NOT BEING FIXED
if($joueur->tuto() == "fini" && ($joueur->nb_jours_fin_tuto() > 0) || isset($_SESSION["msg_fin_tuto"]) ) { ?>
<img id="chatDisplay" src="/webroot/img/icones/chat_bubbles.png" />
<select name="selectFilter" id="selectFilter">
        <option value="all" selected="selected">Tous</option>
        <option value="nearLevels" >Niveaux proches</option>
        <option value="none" >Aucun</option>
</select>
<div id="chatContent" class="bordure gauche corps_scroll"></div>
<textarea id="message" autocomplete="off" name="message"></textarea>

<audio preload="auto" id="son_dialogue">
	<source src = "/webroot/sons/dialogue.ogg" type="audio/ogg" />
	<source src = "/webroot/sons/dialogue.mp3" type="audio/mp3" />
</audio>
<?php } */

//Bilan de la saison en cours
if(isset($podium_saison )) { ?>
    <div id="bilan_saison" class="fond l70 mh2">
        <img id="fermer_bilan_saison" src="/webroot/img/icones/refuser.png">
        <div class="titre">Bilan Saison <?= $derniere_saison->nom(); ?></div>
        <div class="podium ib l100 mh1">
            <span class="g p9 ib l10 md4 or">1°</span>
            <span class="ib l5"><img class="l100" src="<?= $podium_saison[0]->full_portrait(); ?>"/></span>
            <span class="ib g p4 l30"><?= $podium_saison[0]->pseudo(); ?></span>
            <span class="ib g p2 l20">(<?= $podium_prestige[0]; ?> <img class="img_20" src="/webroot/img/icones/prestige.png"/>)</span>
        </div>
        <div class="podium ib l100 mh1">
            <span class="g p9 ib l10 md4 argent">2°</span>
            <span class="ib l5"><img class="l100" src="<?= $podium_saison[1]->full_portrait(); ?>"/></span>
            <span class="ib g p4 l30"><?= $podium_saison[1]->pseudo(); ?></span>
            <span class="ib g p2 l20">(<?= $podium_prestige[1]; ?> <img class="img_20" src="/webroot/img/icones/prestige.png"/>)</span>
        </div>
        <div class="podium ib l100 mh1">
            <span class="g p9 ib l10 md4 bronze">3°</span>
            <span class="ib l5"><img class="l100" src="<?= $podium_saison[2]->full_portrait(); ?>"/></span>
            <span class="ib g p4 l30"><?= $podium_saison[2]->pseudo(); ?></span>
            <span class="ib g p2 l20">(<?= $podium_prestige[2]; ?> <img class="img_20" src="/webroot/img/icones/prestige.png"/>)</span>
        </div>
        <div class="recompense_saison ib l80 mh4">
            <span class="l100 ib p5 g">Tu finis <?= $derniere_saison->classement_joueur($joueur);?>° sur <?= sizeof(explode(",", $derniere_saison->classement()));?></span>
            <span class="l100 ib p3 mh1"><?=$info_saison[0]; ?></span>
            <span class="l100 ib p5 g mh1"><?=$info_saison[1]; ?></span>
        </div>
    <!-- Fin du fond-->
    </div>

    <audio preload="auto" id="son_fin_saison">
         <source src = "/webroot/sons/fin_defi.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/fin_defi.mp3" type="audio/mp3" />
    </audio>
<?php
} elseif(isset($nouveau_monstre_multi)) {
		$heure_log = (int) substr($joueur->dernier_log(), 11, 2);
		$facteur_proba= "";
		if($heure_log >= 18) {
			$facteur_proba = '<span class="ib l100 rouge_fonce g mh2">Première connexion du jour après 18h :<br>Chance de trouver des gros monstres augmentée !</span>';
		}
		switch($nouveau_monstre_multi->nb_chasseurs()){
			case 2.5 :
				echo('<div id="nouveau_monstre_multi" class="bulle_daide"><span class="ib l100 p4 g">Tu as repéré un gros monstre<br>(<span class="'.$nouveau_monstre_multi->couleur_monstre().'">'.$nouveau_monstre_multi->nom().' niv. '.$nouveau_monstre_multi->niveau().'</span>) !</span><span class="ib l100 p2 mh2">Ça risque d\'être compliqué d\'en venir à bout seul'.$feminin.', demande vite l\'aide d\'1 ou 2 contacts avant que tu ne perdes le monstre de vue.</span>'.$facteur_proba.'</div>');
				break;
			case 4.5 :
				echo('<div id="nouveau_monstre_multi" class="bulle_daide"><span class="ib l100 p4 g">Tu as repéré un énorme monstre<br>(<span class="'.$nouveau_monstre_multi->couleur_monstre().'">'.$nouveau_monstre_multi->nom().' niv. '.$nouveau_monstre_multi->niveau().'</span>) !</span><span class="ib l100 p2 mh2">Impossible d\'en venir à bout seul'.$feminin.', demande vite l\'aide de 3 ou 4 contacts avant que tu ne perdes le monstre de vue.</span>'.$facteur_proba.'</div>');
				break;
			case 8 :
				echo('<div id="nouveau_monstre_multi" class="bulle_daide"><span class="ib l100 p4 g">Tu as repéré un monstre LÉGENDAIRE<br>(<span class="'.$nouveau_monstre_multi->couleur_monstre().'">'.$nouveau_monstre_multi->nom().' niv. '.$nouveau_monstre_multi->niveau().'</span>) !</span><span class="ib l100 p2 mh2">Ces monstres sont extrêmement rares et puissants, trouve rapidement 5 à 9 contacts pour t\'aider avant que tu ne perdes le monstre de vue. Et même avec leur aide, rien n\'est gagné... Bon courage !</span>'.$facteur_proba.'</div>');
				break;
		}
} elseif(isset($monstre) && $joueur->tuto() == "fini" && $_SESSION["newMonster"] == "yes"){ //Annonce pour les autres monstres du tuto
		if($monstre->nb_chasseurs() > 1) {
			echo('<div id="monstre_tuto_suite" class="bulle_daide"><span class="ib l100 p4 g">Incroyable : tu viens de repérer un GROS monstre !<br>(<span class="'.$monstre->couleur_monstre().'">'.$monstre->nom().' niv. '.$monstre->niveau().'</span>)</span><span class="ib l100 p2 mh2">Cette fois-ci tu seras obligé'.$feminin.' de demander l\'aide d\'autres aventuriers pour espérer gagner...</span></div>');
		}	elseif($monstre->element() == $joueur->element_neutre()) {
			echo('<div id="monstre_tuto_suite" class="bulle_daide"><span class="ib l100 p4 g">Tu as repéré un autre monstre<br>(<span class="'.$monstre->couleur_monstre().'">'.$monstre->nom().' niv. '.$monstre->niveau().'</span>) !</span><span class="ib l100 p2 mh2">Cette fois-ci, plus d\'avantage lié à ton élément...<br>Tu relèves le défi ?</span></div>');
		}	elseif($monstre->element() == $joueur->element())	{
			echo('<div id="monstre_tuto_suite" class="bulle_daide"><span class="ib l100 p4 g">Encore un monstre<br>(<span class="'.$monstre->couleur_monstre().'">'.$monstre->nom().' niv. '.$monstre->niveau().'</span>) !</span><span class="ib l100 p2 mh2">Tu commences à prendre le coup de main n\'est-ce pas ?</span></div>');
		}	elseif(isset($playerSpells))	{
			echo('<div id="monstre_tuto_suite" class="bulle_daide"><span class="ib l100 p4 g">Décidemment, cette île est infestée !<br>Voilà un autre monstre (<span class="'.$monstre->couleur_monstre().'">'.$monstre->nom().' niv. '.$monstre->niveau().'</span>).</span><span class="ib l100 p2 mh2">Son élément est précisémment ta faiblesse ! Il te sera presque impossible de gagner... Mais ça ne te fais pas peur n\'est-ce pas ?</span></div>');
		}
} elseif(isset($msg_end_fight)){
	echo('<div id="msg_end_fight" class="bulle_daide"><span class="ib l100 p4 g"><img class="img_50" src="/webroot/img/icones/bravo.png"> BRAVO !!!</span><span class="ib l100 p2 g mh2">Grâce à cette victoire en combat :</span>'.$msg_end_fight.'</div>');
} elseif(isset($msg_fin_tuto)){
		if($msg_fin_tuto == "win")	{
			echo('<div id="monstre_tuto_suite" class="bulle_daide"><span class="ib l100 p4 g">BRAVO !!!</span><span class="ib l100 p2 mh2">Je te rassure, il reste plein de gros monstres à démolir mais il te faudra revenir demain.<br><br>L\'entraînement est beaucoup plus efficace quand on en fait un peu chaque jour plutôt que tout d\'un seul coup.<br><br>A demain !</span></div>');
		}	else	{
			echo('<div id="monstre_tuto_suite" class="bulle_daide"><span class="ib l100 p4 g">Pas facile hein ?</span><span class="ib l100 p2 mh2">T\'inquiète, tu prendras ta revanche mais ce sera un autre jour.<br><br>L\'entraînement est beaucoup plus efficace quand on en fait un peu chaque jour plutôt que tout d\'un seul coup.<br><br>A demain !</span></div>');
		}
} elseif(isset($popMonster) && $popMonster == "spellsToBuy"){
		if($sorts_manager->spellsToBuy($joueur) == 1){
			echo('<div id="spellsToBuy" class="bulle_daide"><span class="ib l100 p2">Tu peux <span class="g">apprendre '.$sorts_manager->spellsToBuy($joueur).' nouveau sort </span>avant d\'aller combattre !<span class="ib l100 p2 mh2">Clique sur l\'icone du <span class="g">grimoire</span> : <img class="img_50" src="/webroot/img/icones/grimoire_normal.png"></div>');
		} else {
			echo('<div id="spellsToBuy" class="bulle_daide"><span class="ib l100 p2">Tu peux <span class="g">apprendre '.$sorts_manager->spellsToBuy($joueur).' nouveaux sorts </span>avant d\'aller combattre !<span class="ib l100 p2 mh2">Clique sur l\'icone du <span class="g">grimoire</span> : <img class="img_50" src="/webroot/img/icones/grimoire_normal.png"></div>');
		}
}

?>

<audio preload="auto" id="son_evenement">
         <source src = "/webroot/sons/level_up.ogg" type="audio/ogg" />
         <source src = "/webroot/sons/level_up.mp3" type="audio/mp3" />
</audio>

<!-- Boites de dialogues -->
<div id="notEnoughFights" title="Tu ne peux plus combattre...">Tu peux faire 5 combats par jour.<br><br>Reviens demain pour combattre à nouveau !</div>
<div id="participateFight" title="Participer au combat ?">Souhaite-tu accepter ou refuser l'invitation au combat multijoueur ?</div>

<script> //Positionnement des monstres
$(function(){
	<?php
	//On crée puis positionne les différents monstres et leur descriptif
	if(isset($monstres) && $monstres != false && $joueur->tuto() == "fini")	{
		$popUpMonster = "";
		if($joueur->nb_jours_fin_tuto() == 0){
			$popUpMonster = 'id="deuxieme_monstre_tuto"';
		}
		foreach($monstres as $monstre)	{
			//Détermination de la position du monstre
			$position = $monstre->position();
			$posX = $position["posX"];
			$posY = $position["posY"];
			$posX_info = (float)substr($posX, 0, strlen($posX) - 1);
			$posY_info = (float)substr($posY, 0, strlen($posY) - 1) + 1.5;
			$titleMonster = "";
			echo ("var monsterIcon = '';");
			if($monstre->nb_chasseurs() != 1)	{
				if ($combats_manager->exists($joueur->id(), $monstre->id())) {
          $combat = $combats_manager->get($joueur->id(), $monstre->id());
					if($combat->deroulement() == ""){
						echo ("var temps_restant_".$monstre->id()." = ".$monstre->temps_avant_fuite().";");
					}
				} else {
					echo ("var temps_restant_".$monstre->id()." = ".$monstre->temps_avant_fuite().";");
				}
				if($combats_manager->exists( $joueur->id(), $monstre->id() )) {
					$combat = $combats_manager->get($joueur->id(), $monstre->id());
					if(!$combat->tous_prets() && in_array($joueur->id(), $combat->id_prets()) ){
						echo ("var monsterIcon = '<img id=\"monstre_".$monstre->id()."_icon\" class=\"monsterIcon\" idFight=\"".$combat->id()."\" src=\"/webroot/img/icones/wait.png\" />'");
						$titleMonster = "En attente des autres participants";
						$imgMonster = substr($monstre->img_tete(), 0, strlen($monstre->img_tete())-4)."_nb.png";
					} elseif(!$combat->tous_prets() && !in_array($joueur->id(), $combat->id_prets()) ){
							echo ("var monsterIcon = '<img id=\"monstre_".$monstre->id()."_icon\" class=\"monsterIcon\" idFight=\"".$combat->id()."\" src=\"/webroot/img/icones/notification.png\" />'");
							$titleMonster = "Tu dois accepter ou refuser ce combat";
							$imgMonster = substr($monstre->img_tete(), 0, strlen($monstre->img_tete())-4)."_nb.png";
					} elseif($combat->tous_prets() && $joueur->id() != $combat->prochain_a_jouer() && !$combat->alreadyFought($joueur) ){
							echo ("var monsterIcon = '<img id=\"monstre_".$monstre->id()."_icon\" class=\"monsterIcon\" idFight=\"".$combat->id()."\" src=\"/webroot/img/icones/check.png\" />'");
							$titleMonster = "Tous les joueurs sont prêts mais ce n\'est pas ton tour";
							$imgMonster = substr($monstre->img_tete(), 0, strlen($monstre->img_tete())-4)."_nb.png";
					} elseif($combat->tous_prets() && $joueur->id() != $combat->prochain_a_jouer() && $combat->alreadyFought($joueur) ){
							echo ("var monsterIcon = '<img id=\"monstre_".$monstre->id()."_icon\" class=\"monsterIcon\" idFight=\"".$combat->id()."\" src=\"/webroot/img/icones/check.png\" />'");
							$titleMonster = "Tu as déjà combattu ce monstre, attends la fin du combat !";
							$imgMonster = substr($monstre->img_tete(), 0, strlen($monstre->img_tete())-4)."_nb.png";
					} elseif($combat->tous_prets() && $joueur->id() == $combat->prochain_a_jouer() && count($combat->id_prets()) != 1 ){
							if($combat->id_orga() == $joueur->id() && $combat->deroulement() == ""){
								$destination = "/app/controllers/prepa_combats.php?idm=".$monstre->id();
							} else {
								$destination = "/app/controllers/combats_decks.php?id=".$combat->id();
							}
							echo ("var monsterIcon = '<img id=\"monstre_".$monstre->id()."_icon\" class=\"monsterIcon\" dest=\"".$destination."\" idFight=\"".$combat->id()."\" src=\"/webroot/img/icones/gaming.png\" />'");
							$titleMonster = "A ton tour de combattre !";
							$imgMonster = substr($monstre->img_tete(), 0, strlen($monstre->img_tete())-4)."_nb.png";
					} else {
							$imgMonster = $monstre->img_tete();
					}
				} else {
					$imgMonster = $monstre->img_tete();
				}
			} else {
				$imgMonster = $monstre->img_tete();
			}

			?>

			//Génération de l'icone du monstre
			$('<div id="monstre_<?= $monstre->id(); ?>" title="<?= $titleMonster; ?>" class="monstre_index"></div>').appendTo("body"); //On créé puis on cache la div image monstre
			var monstre = '<a <?= $popUpMonster; ?> class="gris" href="/app/controllers/prepa_combats.php?idm=<?= $monstre->id(); ?>"><img alt="" src="<?= $imgMonster; ?>" /></a>';
			$(monstre).appendTo("#monstre_<?= $monstre->id(); ?>");

			//Génération des infos monstres
			$('<div id="monstre_<?= $monstre->id(); ?>_info" class="info_monstre"></div>').appendTo("body"); //On créé puis on cache la div info_monstre
			var img_niveau = '<img class="fond_niveau_monstre" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png" />';
			var niveau_monstre = '<span class="ib niveau_monstre"><?= $monstre->niveau(); ?></span>';
			var nom_monstre = '<span class="ib nom_monstre"><?= $monstre->nom(); ?></span>';
			$(nom_monstre).appendTo("#monstre_<?= $monstre->id(); ?>_info");
			$(img_niveau).appendTo("#monstre_<?= $monstre->id(); ?>_info");
			$(niveau_monstre).appendTo("#monstre_<?= $monstre->id(); ?>_info");
			if(monsterIcon != ""){
				$(monsterIcon).appendTo("#monstre_<?= $monstre->id(); ?>");
				$("#monstre_<?= $monstre->id(); ?>_icon").css("position", "absolute").css("left", "10%");
				$("#monstre_<?= $monstre->id(); ?>_icon").css("bottom", "18%").css("width", "40%");
			}

			//Positionnement de l'icone du monstre
			$("#monstre_<?= $monstre->id(); ?>").css("left", "<?= $posX; ?>");
			$("#monstre_<?= $monstre->id(); ?>").css("bottom", "<?= $posY; ?>");

			//Positionnement des infos du monstre
			$("#monstre_<?= $monstre->id(); ?>_info").css("left", "<?= $posX_info; ?>%")
			$("#monstre_<?= $monstre->id(); ?>_info").css("bottom", "<?= $posY_info; ?>%");

			//Coloration de la bordure et de la police
			$("#monstre_<?= $monstre->id(); ?>_info .nom_monstre").css("border", "2px solid <?= $monstre->couleur_bordure_hex(); ?>");
			$("#monstre_<?= $monstre->id(); ?>_info .nom_monstre").css("color", "<?= $monstre->couleur_nom_hex(); ?>");


			//On affiche le compte à rebours sur le monstre s'il y en a un (toutes les minutes)
			if(typeof temps_restant_<?= $monstre->id(); ?> != "undefined") {
				var heures_<?= $monstre->id(); ?> = Math.floor(temps_restant_<?= $monstre->id(); ?>/60);
				var minutes_<?= $monstre->id(); ?> = temps_restant_<?= $monstre->id(); ?> - heures_<?= $monstre->id(); ?>*60;
				if(heures_<?= $monstre->id(); ?> < 10) {
					heures_<?= $monstre->id(); ?> = "0" + heures_<?= $monstre->id(); ?>;
				}
				if(minutes_<?= $monstre->id(); ?> < 10)	{
					minutes_<?= $monstre->id(); ?> = "0" + minutes_<?= $monstre->id(); ?>;
				}
				var chrono = heures_<?= $monstre->id(); ?> + "h" + minutes_<?= $monstre->id(); ?> + "min";
				$('<div id="chrono_<?= $monstre->id(); ?>" class="compte_a_rebours_monstre">'+ chrono +'</div>').appendTo("body");
				$("#chrono_<?= $monstre->id(); ?>").css("top", $("#monstre_<?= $monstre->id(); ?>_info").offset().top + 0.040*$(window).height());
				$("#chrono_<?= $monstre->id(); ?>").css("left", $("#monstre_<?= $monstre->id(); ?>_info").offset().left + 0.005*$(window).width());
				setInterval(function(){
					temps_restant_<?= $monstre->id(); ?> -= 1;
					if(temps_restant_<?= $monstre->id(); ?> > 0){
						heures_<?= $monstre->id(); ?> = Math.floor(temps_restant_<?= $monstre->id(); ?>/60);
						minutes_<?= $monstre->id(); ?> = temps_restant_<?= $monstre->id(); ?> - heures_<?= $monstre->id(); ?>*60;
						if(heures_<?= $monstre->id(); ?> < 10) {
							heures_<?= $monstre->id(); ?> = "0" + heures_<?= $monstre->id(); ?>;
						}
						if(minutes_<?= $monstre->id(); ?> < 10)	{
							minutes_<?= $monstre->id(); ?> = "0" + minutes_<?= $monstre->id(); ?>;
						}
						chrono = heures_<?= $monstre->id(); ?> + "h" + minutes_<?= $monstre->id(); ?> + "min";
						$("#chrono_<?= $monstre->id(); ?>").html(chrono);
					}
					else if(temps_restant_<?= $monstre->id(); ?> <= 0)	{
						$("#monstre_<?= $monstre->id(); ?>").hide("pulsate", {easing: "swing"}, 500);
						$("#monstre_<?= $monstre->id(); ?>_info").hide("pulsate", {easing: "swing"}, 500);
						$("#chrono_<?= $monstre->id(); ?>").hide("pulsate", {easing: "swing"}, 500);
					}
				}, 1000*60);
			}
			<?php
		}
	}
	elseif($joueur->tuto() == "index_1" || $joueur->tuto() == "index_8") {
			//Monster positionning
			$position = $monstre->position();
			$posX = $position["posX"];
			$posY = $position["posY"];
			$posX_info = (float)substr($posX, 0, strlen($posX) - 1);
			$posY_info = (float)substr($posY, 0, strlen($posY) - 1) + 1.5;
			?>
			//Génération de l'icone du monstre
			$('<div id="monstre_<?= $monstre->id(); ?>" class="monstre_index"></div>').appendTo("body").hide(); //On créé puis on cache la div image monstre
			var monstre = '<a id="monstre_tuto" class="gris" href="/app/controllers/prepa_combats.php?idm=<?= $monstre->id(); ?>"><img alt="" src="<?= $monstre->img_tete(); ?>" /></a>';
			$(monstre).appendTo("#monstre_<?= $monstre->id(); ?>");

			//Génération des infos monstres
			$('<div id="monstre_<?= $monstre->id(); ?>_info" class="info_monstre"></div>').appendTo("body").hide(); //On créé puis on cache la div info_monstre
			var img_niveau = '<img class="fond_niveau_monstre" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png" />';
			var niveau_monstre = '<span class="ib niveau_monstre"><?= $monstre->niveau(); ?></span>';
			var nom_monstre = '<span class="ib nom_monstre"><?= $monstre->nom(); ?></span>';
			$(nom_monstre).appendTo("#monstre_<?= $monstre->id(); ?>_info");
			$(img_niveau).appendTo("#monstre_<?= $monstre->id(); ?>_info");
			$(niveau_monstre).appendTo("#monstre_<?= $monstre->id(); ?>_info");

			//Positionnement de l'icone du monstre
			$("#monstre_<?= $monstre->id(); ?>").css("left", "<?= $posX; ?>");
			$("#monstre_<?= $monstre->id(); ?>").css("bottom", "<?= $posY; ?>");

			//Positionnement des infos du monstre
			$("#monstre_<?= $monstre->id(); ?>_info").css("left", "<?= $posX_info; ?>%")
			$("#monstre_<?= $monstre->id(); ?>_info").css("bottom", "<?= $posY_info; ?>%");

			//Coloration de la bordure et de la police
			$("#monstre_<?= $monstre->id(); ?>_info .nom_monstre").css("border", "2px solid <?= $monstre->couleur_bordure_hex(); ?>");
			$("#monstre_<?= $monstre->id(); ?>_info .nom_monstre").css("color", "<?= $monstre->couleur_nom_hex(); ?>");

			//Reveal monster only if it is the first monster
			<?php
			if($joueur->tuto() == "index_1")
			{
				echo("$('#monstre_".$monstre->id()."').show();");
				echo("$('#monstre_".$monstre->id()."_info').show();");
			}
	} ?>

});
</script>

<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/index.js?nvd_r=xxx"></script>
