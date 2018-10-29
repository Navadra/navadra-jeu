<!-- Fond -->
<div class="fond l70 mh2 pb1">

<div class="titre">Historique des Combats</div>

<!-- Affichage des résultats -->
<div id="liste_combats_passes" class="bordure centre l95 mh1">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <span class="l10">Date</span>
            <span class="l20">Monstre</span>
            <span class="l10">Élément</span>
            <span class="l20">Organisateur</span>
            <span class="l15">Nb chasseurs</span>
            <span class="l10">Issue</span>
            <span class="l10">Prestige</span>
        </div>
    </div>
    <div class="corps_scroll align_centre scroll_grand">
		<?php
			foreach($liste_combats_passes as $combat)
			{
				$monstre = $combat->copie_monstre();
				if($combat->prioritaire() == "oui")
					{$surligne = "fond_beige_clair";}
				else
					{$surligne = "";}
				if($combat->issue() == "victoire")
					{$couleur_prestige = "vert";}
				else
					{$couleur_prestige = "rouge";}
			?>
			<a class="gris" href="#">

			<div id="<?= $combat->id(); ?>" class="ligne_scroll <?= $surligne; ?>">
                 <span class="l10"><?= $combat->date_combat_format_date(); ?></span>
				 <span class="l20"><?= $monstre->nom(); ?></span>
				 <span class="l10"><img class="neutre img_30" src="<?= $monstre->element_img(); ?>"/></span>

                 <?php if($combat->id_orga() == $joueur->id()) //Détermination de ce qu'il faut mettre dans la colonne Orga
				 {
					 echo('<span class="l20">Toi</span>');
				 }
				 else
				 {
					 $orga = $manager->get($combat->id_orga());
					 echo('<span class="l20">'.$orga->pseudo().'</span>');
				 } ?>

				 <span class="l15"><?= sizeof($combat->ordre()); ?></span>
                 <span class="l10"><img class="neutre img_30" src="/webroot/img/icones/<?= $combat->issue(); ?>.png"/></span>
                 <span class="g l10 <?= $couleur_prestige; ?>"><?= $combat->prestige(); ?></span>
			 </div>

			 </a>
			 <?php
			 }
		?>
    </div>
</div>

<!--
<div class="titre_petit">
	<span class="ib l100">Invitations en cours</span>
    <span id="info_combats" class="ib p0 <?php if($joueur->nb_combats() == 0){echo "rouge";}else{echo "gris";}?>">(Combats restants aujourd'hui : <?= $joueur->nb_combats(); ?>)</span>
</div>

<div id="liste_invitations" class="bordure centre l95 mh1">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <span class="l10">Date</span>
            <span class="l20">Monstre</span>
            <span class="l10">Élément</span>
            <span class="l20">Organisateur</span>
            <span class="l15">Nb chasseurs</span>
            <span class="l10">Participer</span>
            <span class="l10">Prêts</span>
        </div>
    </div>
    <div class="corps_scroll align_centre scroll_petit">
		<?php
		if(isset($liste_invitations))
		{
			foreach($liste_invitations as $invit)
			{
				$monstre = $invit->copie_monstre();
				if($monstre == "")
				{
					$monstre = $monstres_manager->get_id($invit->id_monstre());
				}
				if($invit->prioritaire() == "oui")
					{$surligne = "fond_beige_clair";}
				else
					{$surligne = "";}
			?>
			<a class="gris" href="/app/controllers/prepa_combats.php?idm=<?= $invit->id_monstre(); ?>">

			<div id="<?= $invit->id(); ?>" class="ligne_scroll <?= $surligne; ?>">
            	 <?php if($invit->id_orga() == $joueur->id()) //Détermination de la date
				 {
					 echo('<span class="l10">'.$invit->derniere_invit_format_date().'</span>');
				 }
				 else
				 {
					 echo('<span class="l10">'.$invit->date_invitation_joueur($joueur).'</span>');
				 } ?>

				 <span class="l20"><?= $monstre->nom(); ?></span>
				 <span class="l10"><img class="neutre img_30" src="<?= $monstre->element_img(); ?>"/></span>

                 <?php if($invit->id_orga() == $joueur->id()) //Détermination de ce qu'il faut mettre dans la colonne Orga
				 {
					 echo('<span class="l20">Toi</span>');
				 }
				 else
				 {
					 $orga = $manager->get($invit->id_orga());
					 echo('<span class="l20">'.$orga->pseudo().'</span>');
				 } ?>

				 <span class="l15"><?= sizeof($invit->ordre()); ?></span>

                 <?php if(in_array($joueur->id(), $invit->id_prets(), true)) //Si le joueur a déjà accepté l'invitation
				 {
					 echo('<span class="l10"><img id="participer_'.$invit->id().'" class="img_30 neutre" src="/webroot/img/icones/check.png"/></span>');
				 }
				 else
				 {
					 echo('<span class="l10"><img id="accepter_'.$invit->id().'" class="img_30 cliquable accepter" src="/webroot/img/icones/check.png"/><img id="refuser_'.$invit->id().'" class="img_30 cliquable refuser" src="/webroot/img/icones/refuser.png"/></span>');
				 }

				 if($invit->tous_prets()) //Si tout le monde a déjà accepté l'invitation
				 {
					 echo('<span class="l10"><img id="prets_'.$invit->id().'" class="img_30 neutre" src="/webroot/img/icones/check.png"/></span>');
				 }
				 else
				 {
					 echo('<span class="l10"><img id="prets_'.$invit->id().'" class="img_30 neutre" src="/webroot/img/icones/sablier2.png"/></span>');
				 }
				 ?>
			 </div>

			 </a>
			 <?php
			 }
		}
		?>
    </div>
</div> -->

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin du fond -->
</div>

<!-- Boites de dialogues -->
<div id="plus_assez_combats" title="Tu ne peux plus combattre aujourd'hui !">
</div>

<div id="participer_combat" title="Participer à ce combat ?">
</div>

<div id="refuser_combat" title="Refuser ce combat ?">
</div>

<!-- Div cachés pour JS -->
<div class="cache" id="nb_combats_restants"><?= $joueur->nb_combats(); ?></div>


<script src="/webroot/js/liste_combats.js?nvd_r=xxx"></script>
