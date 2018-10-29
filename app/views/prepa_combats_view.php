<!-- Tuteur -->
<img alt="" id="tuteur_cote" src="<?= $joueur->portrait_tuteur(); ?>" />

<!-- Bulle du personnage-->
<div id="bulle_bas" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"><?= $msg_tuteur; ?></span>
</div

><?php if($joueur->tuto() != "fini"){ ?>
<div id="commandes_tuto_bas">
	<img id="tuto_precedent" src="/webroot/img/icones/chevron1.png" />
    <img id="tuto_suivant" src="/webroot/img/icones/chevron2.png" />
</div>
<?php } ?>

<!-- Fond -->
<div class="fond l70 mh1 h68">

<div class="titre">Préparation au combat</div>

<?php if($monstre->nb_chasseurs() != 1)
{ ?>

<div class="ib mg10 l50 align_haut">
	<?php /* if($timeSlot == "NoTimeSlot"){
		if($joueur->nb_combats() == 0){
			$colorTemp = "rouge";
		} else {
			$colorTemp = "gris";
		}
		echo('<span id="info_combats" class="div_centrale p2 '.$colorTemp.'>">Combats restants aujourd\'hui : '.$joueur->nb_combats().'</span>');
		if($joueur->gameLimitation() == 1 && $joueur->restrictedFree() == 1){
			echo "<span class='div_centrale rouge'>Aucun monstre solo n'apparaît chez toi mais d'autres joueurs peuvent t'inviter à combattre leurs monstres.</span>";
		} elseif($joueur->gameLimitation() == 1 && $joueur->restrictedFree() == 0){
			echo "<span class='div_centrale rouge'>Un seul monstre solo apparaît chez toi chaque jour mais d'autres joueurs peuvent t'inviter à combattre leurs monstres.</span>";
		}
	} */ ?>
    <div class="bordure">
        <div class="entetes_scroll">
            <div class="ligne_scroll">
                <span class="l30">Pseudo</span>
                <span class="l15">Elmnt</span>
                <span class="l15">Niv</span>
                <span id="state_contact" class="l15">Etat</span>
                <?php
                if($joueur->id() == $combat->id_orga() && $combat->deroulement() == "") //Si l'id du joueur actif est celle de l'organisateur permet de supprimer les autres joueurs
				 {
					 echo('<span class="l20">Suppr</span>');
				 }
				 else
				 {
					 echo('<span class="l20">Réponse</span>');
				 }
				 ?>
            </div>
        </div>
        <div id="list_fighting_players" class="corps_scroll align_centre scroll_moyen">
            <?php
            foreach($combattants as $combattant) { ?>
            <div class="ligne_scroll" id="<?= $combattant->id(); ?>">
            	<span class="l30"><?= $combattant->pseudo(); ?></span>
	            <span class="l15"><img class="neutre img_20" src="<?= $combattant->element_img(); ?>"/></span>
              <span class="l15"><?= $combattant->niveau(); ?></span>
              <?php if(in_array($combattant->id(), $combat->id_prets()) && $combat->alreadyFought($combattant)) {
					 			echo('<span class="l15"><img id="etat_'.$combattant->id().'" class="neutre pret img_20" src="/webroot/img/icones/gaming.png"/></span>');
				 			} elseif(in_array($combattant->id(), $combat->id_prets()) && !$combat->alreadyFought($combattant)) {
					 			echo('<span class="l15"><img id="etat_'.$combattant->id().'" class="neutre pret img_20" src="/webroot/img/icones/check.png"/></span>');
				 			} else {
					 			echo('<span class="l15"><img id="etat_'.$combattant->id().'" class="neutre pret img_20" src="/webroot/img/icones/sablier2.png"/></span>');
				 			}
				 if($joueur->id() == $combat->id_orga() && $joueur->id() != $combattant->id() && $combat->deroulement() == "") //Si l'id du joueur actif est celle de l'organisateur permet de supprimer les autres joueurs (SI le combat n'a pas déjà commencé)
				 {
					 echo('<span class="l20"><img id="suppr_'.$combattant->id().'" class="suppr img_20" src="/webroot/img/icones/faux.png"/></span>');
				 }
				 elseif($joueur->id() == $combat->id_orga() && $joueur->id() == $combattant->id()) //Empeche l'organisateur de se supprimer lui-même
				 {
					 echo('<span class="l20"></span>');
				 }
				 else //Sinon permet de répondre à l'invitation
				 {
					 if(in_array($combattant->id(), $combat->id_prets())) //Si le joueur est prêts
					 {
					 	echo('<span class="l20"><img id="participe_'.$combattant->id().'" class="neutre img_20" src="/webroot/img/icones/check.png"/></span>');
					 }
					 elseif($combattant->id() != $joueur->id()) //Si le joueur n'est pas le joueur actif
					 {
					 	echo('<span class="l20"><img id="participe_'.$combattant->id().'" class="neutre img_20" src="/webroot/img/icones/sablier2.png"/></span>');
					 }
					 else
					 {
						 echo('<span class="ib l20"><img id="accepter_'.$combattant->id().'" class="cliquable accepter img_20" src="/webroot/img/icones/check.png"/><img id="refuser_'.$combattant->id().'" class="cliquable refuser img_20" src="/webroot/img/icones/refuser.png"/></span>');
					 }
				 }
				 ?>
                 <span id="sexe_<?= $combattant->id(); ?>" class="cache"><?= $combattant->sexe(); ?></span>
            </div>
            <?php
            }?>
        </div>
    </div>
    <span id="nb_chasseurs" class="ib md30 l30 g"><?= $combat->afficher_nb_chasseurs(); ?></span>
    <span class="l10"><a id="voir_profil" class="ib" href="#"><img class="icone_admin_combat" src="/webroot/img/icones/oeil.png"/></a></span>
    <?php
    if($joueur->id() == $combat->id_orga() && $combat->deroulement() == "") //Si l'id du joueur actif est celle de l'organisateur permet de changer l'ordre des autres joueurs
	{
		echo('<span class="l10"><img id="monter" class="icone_admin_combat" src="/webroot/img/icones/fleche_haut.png"/></span>');
		echo('<span class="l10"><img id="descendre" class="icone_admin_combat" src="/webroot/img/icones/fleche_bas.png"/></span>');
	}
	?>
</div>

<!-- MONSTRE -->
<div class="ib l35">
	<span class="div_centrale"><img id="monstre" class="monstre_prepa_combat" src="<?= $monstre->img(); ?>"/></span>
    <span class="info_monstre_flow mb2">
    	<span class="ib nom_monstre monstre_<?= $monstre->element(); ?>"><?= $monstre->nom(); ?></span>
    	<img class="fond_niveau_monstre" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png" />
    	<span class="ib niveau_monstre"><?= $monstre->niveau(); ?></span>
    </span>
    <span id="info_nb_chasseurs" class="div_centrale mh10 mb2"><?= $monstre->info_nb_chasseurs(); ?></span>
    <div id="info_prestige">
        <span class="ib l10 mh2"><img class="icones_vd" src="/webroot/img/icones/victoire.png"/></span>
        <span class="ib l20 md15 g p1 vert" id="gain_prestige">+<?= $monstre->gain_prestige(sizeof($combattants), $timeSlot); ?><br /><img class="img_20" src="/webroot/img/icones/prestige.png"/></span>
        <span class="ib l10"><img class="icones_vd" src="/webroot/img/icones/defaite.png"/></span>
        <span class="ib l20 g p1 rouge" id="perte_prestige"><?= $monstre->perte_prestige(sizeof($combattants), $timeSlot); ?><br /><img class="img_20" src="/webroot/img/icones/prestige.png"/></span>
    </div>
</div>
<?php }
else
{ ?>

<div class="ib l100 align_haut mb1 mh1">
	<?php /* if($timeSlot == "NoTimeSlot"){
		if($joueur->nb_combats() == 0){
			$colorTemp = "rouge";
		} else {
			$colorTemp = "gris";
		}
		echo('<span id="info_combats" class="div_centrale p2 '.$colorTemp.'>">Combats restants aujourd\'hui : '.$joueur->nb_combats().'</span>');
		if($joueur->gameLimitation() == 1 && $joueur->restrictedFree() == 1){
			echo "<span class='div_centrale rouge'>Aucun monstre solo n'apparaît chez toi mais d'autres joueurs peuvent t'inviter à combattre leurs monstres.</span>";
		} elseif($joueur->gameLimitation() == 1 && $joueur->restrictedFree() == 0){
			echo "<span class='div_centrale rouge'>Un seul monstre solo apparaît chez toi chaque jour mais d'autres joueurs peuvent t'inviter à combattre leurs monstres.</span>";
		}
	} */ ?>
</div>

<!-- MONSTRE -->
<div class="ib l100">
	<span class="div_centrale"><img id="monstre" class="monstre_prepa_combat" src="<?= $monstre->img(); ?>"/></span>
    <span class="info_monstre_flow mb2">
    	<span class="ib nom_monstre monstre_<?= $monstre->element(); ?>"><?= $monstre->nom(); ?></span>
    	<img class="fond_niveau_monstre" src="/webroot/img/monstres/niv_monstre_<?= $monstre->element(); ?>.png" />
    	<span class="ib niveau_monstre"><?= $monstre->niveau(); ?></span>
    </span>
    <span id="info_nb_chasseurs" class="l50 div_centrale mh4 g"><?= $monstre->info_nb_chasseurs(); ?></span>
    <div id="info_prestige" class="l20 div_centrale">
        <span class="ib l5 mh2"><img class="icones_vd" src="/webroot/img/icones/victoire.png"/></span>
        <span class="ib l5 md15 g p1 vert" id="gain_prestige">+<?= $monstre->gain_prestige(sizeof($combattants), $timeSlot); ?><br /><img class="img_20" src="/webroot/img/icones/prestige.png"/></span>
        <span class="ib l5"><img class="icones_vd" src="/webroot/img/icones/defaite.png"/></span>
        <span class="ib l5 g p1 rouge" id="perte_prestige"><?= $monstre->perte_prestige(sizeof($combattants), $timeSlot); ?><br /><img class="img_20" src="/webroot/img/icones/prestige.png"/></span>
    </div>
</div>




<?php
}
if($joueur->id() == $combat->id_orga() && $combat->deroulement() == "" && $monstre->nb_chasseurs() != 1) //Si l'id du joueur actif est celle de l'organisateur permet d'inviter des contacts et lancer le combat
{ ?>
<a class="gris" href="#"><div class="bouton form_gauche open_invite">Inviter</div></a>
<a href="#"><img class="icone_form_gauche open_invite" src="/webroot/img/icones/btn_inviter.png"></a>

<?php }
if($joueur->id() == $combat->prochain_a_jouer($joueur)) //Si le joueur actif est le prochain à jouer
{
	$cache_attaque = "";
}
else
{
	$cache_attaque = "cache";
} ?>
<a class="blanc spell_choice" href="/app/controllers/combats_decks.php?id=<?=$combat->id();?>"><div class="bouton form_droite2 <?= $cache_attaque; ?>">Choix sorts</div></a>
<a class="spell_choice <?= $cache_attaque; ?>" href="/app/controllers/combats_decks.php?id=<?=$combat->id();?>"><img id="jouer_icone" class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>


<div class="cache" id="id_monstre"><?= $monstre->id(); ?></div>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin fond -->
</div>

<table border="0" id="tableau_profil" class="cache">
  	<tr>
    	<th scope="col l30">Élément</th>
    	<th scope="col l60">Dégâts reçus</th>
  	</tr>
    <tr>
    		<td><img class="img_30" src="/webroot/img/elements/<?= $monstre->element(); ?>.png"/></td>
    		<td class="g"><?= $joueur->surligner_bonus_elem($joueur->facteur_elem_def($monstre->element()), "def"); ?></td>
  	</tr>
</table>

<?php
if($joueur->id() == $combat->id_orga() && $monstre->nb_chasseurs() != 1){ ?>
<!-- Invite contacts -->
<div id="tabs_fight_preparation" class="fond l80 h70">
	<ul>
			<li><a href="#research">Recherche</a></li>
			<li><a href="#contacts">Contacts</a></li>
			<li><a href="#automatic">Automatique</a></li>
	</ul>
	<div id="automatic">
    	<span class="ib mh20 g p4 md4">Inviter automatiquement</span>
        <span class="ib">
        	<select id="nb_automatically_invited" class="champ p4 g">
            	<?php $selected_nb = ceil($monstre->nb_chasseurs()) - 1; ?>
                <option value="1" <?php if($selected_nb==1){echo 'selected="selected"';}?>>1</option>
                <option value="2" <?php if($selected_nb==2){echo 'selected="selected"';}?>>2</option>
                <option value="3" <?php if($selected_nb==3){echo 'selected="selected"';}?>>3</option>
                <option value="4" <?php if($selected_nb==4){echo 'selected="selected"';}?>>4</option>
                <option value="5" <?php if($selected_nb==5){echo 'selected="selected"';}?>>5</option>
                <option value="6" <?php if($selected_nb==6){echo 'selected="selected"';}?>>6</option>
                <option value="7" <?php if($selected_nb==7){echo 'selected="selected"';}?>>7</option>
                <option value="8" <?php if($selected_nb==8){echo 'selected="selected"';}?>>8</option>
                <option value="9" <?php if($selected_nb==9){echo 'selected="selected"';}?>>9</option>
    		</select>
   		</span>
        <span class="ib g p4 mg4">joueur(s).</span>
    </div>
    <div id="contacts">
    	<div id="tableContainer1" class="ib l100 align_centre tableContainer">
    	<table id="contacts_list" class="tablesorter scrollTable" width="100%">
            <thead class="table_head fixedHeader">
                <tr>
                    <th>Pseudo</th>
                    <th>Avatar</th>
                    <th>Niveau</th>
                    <th>Dernière connexion</th>
                </tr>
            </thead>
            <tbody class="scrollContent">
            	<?php foreach($contacts as $contact){ ?>
				<tr id="contact_<?= $contact->id();?>" class="potentialPartner">
                	<td><?= $contact->pseudo();?></td>
                	<td>
                    	<img id="head<?= $contact->id();?>" class="l30 team_portraits" title="<?= $contact->pseudo();?> - Niv.<?= $contact->niveau();?>" src="<?= $contact->full_portrait();?>">
                        <div id="head<?= $contact->id();?>_level" class="cache"><?= $contact->niveau();?></div>
                        <div id="head<?= $contact->id();?>_element" class="cache"><?= $contact->element();?></div>
                        <div id="head<?= $contact->id();?>_portrait" class="cache"><?= $contact->portrait();?></div>
                    </td>
                	<td><?= $contact->niveau();?></td>
               		<td><?= $contact->derniere_connexion();?></td>
                </tr>
				<?php }	?>
            </tbody>
        </table>
        </div>
    </div>
    <div id="research">
    	<div id="tableContainer2" class="ib l100 align_centre tableContainer">
    	<table id="research_list" class="tablesorter scrollTable" width="100%">
            <thead class="table_head fixedHeader">
                <tr>
                    <th>Pseudo</th>
                    <th>Avatar</th>
                    <th>Niveau</th>
                    <th>Dernière connexion</th>
                </tr>
            </thead>
            <tbody class="scrollContent">
            	<?php foreach($researchPlayers as $researchPlayer){ ?>
				<tr id="research_<?= $researchPlayer->id();?>" class="potentialPartner">
                	<td><?= $researchPlayer->pseudo();?></td>
                	<td>
                    	<img id="head<?= $researchPlayer->id();?>" class="l30 team_portraits" title="<?= $researchPlayer->pseudo();?> - Niv.<?= $researchPlayer->niveau();?>" src="<?= $researchPlayer->full_portrait();?>">
                        <div id="head<?= $researchPlayer->id();?>_level" class="cache"><?= $researchPlayer->niveau();?></div>
                        <div id="head<?= $researchPlayer->id();?>_element" class="cache"><?= $researchPlayer->element();?></div>
                        <div id="head<?= $researchPlayer->id();?>_portrait" class="cache"><?= $researchPlayer->portrait();?></div>
                    </td>
                	<td><?= $researchPlayer->niveau();?></td>
               		<td><?= $researchPlayer->derniere_connexion();?></td>
                </tr>
				<?php }	?>
            </tbody>
        </table>
        </div>
    </div>
    <a class="gris back" href="#"><div class="bouton form_gauche">Retour</div></a>
	<a href="#" class="back"><img class="icone_form_gauche" src="/webroot/img/icones/btn_retour.png"></a>
    <a class="blanc invite_players" href="#"><div class="bouton form_droite2">Inviter</div></a>
	<a class="invite_players" href="#"><img id="jouer_icone" class="icone_form_droite2" src="/webroot/img/icones/play.png"></a>
</div>
<?php } ?>

<!-- Boites de dialogues -->
<div id="confirm_suppr" title="Annuler l'invitation de ce chasseur de monstres ?">
</div>

<div id="plus_assez_combats" title="C'est fini pour aujourd'hui !">
</div>

<div id="too_many_players" title="Tu as invité trop de joueurs !">
</div>

<div id="participer_combat" title="Participer à ce combat ?">
</div>

<div id="refuser_combat" title="Refuser ce combat ?">
</div>

<div id="lancer_combat" title="Tous le monde doit être prêt !">
</div>

<div id="confirm_combat_solo" title="Tu es sûr<?= $feminin; ?> ?">
</div>

<!-- Div cachés pour JS -->
<div class="cache" id="nb_combats_restants"><?= $joueur->nb_combats(); ?></div>
<div class="cache" id="id_joueur"><?= $joueur->id(); ?></div>
<div class="cache" id="id_combat"><?= $combat->id(); ?></div>
<div class="cache" id="nb_chasseurs_recommandes"><?= $monstre->nb_chasseurs(); ?></div>
<div class="cache" id="count_spells"><?= $count_spells; ?></div>
<?php if($joueur->tuto() != "fini")
{ ?>
<div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
<div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
<div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
<div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
<div id="id_monstre" class="cache"><?= $monstre->id(); ?></div>
<?php }

if($monstre->nb_chasseurs() != 1 && $combat->deroulement() == "") //Chrono avant fuite
{?>
<div id="chrono" class="compte_a_rebours_monstre"><?= $monstre->temps_avant_fuite(); ?></div>
<?php } ?>

<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/prepa_combats.js?nvd_r=xxx"></script>
