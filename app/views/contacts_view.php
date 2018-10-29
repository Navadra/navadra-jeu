<!-- Fond -->
<div class="fond l70 mh1 pb4">

<div class="titre">Contacts</div>

<div class="bordure l95 mh1 centre">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
        	<?php if(isset($id_monstre))
			 {
				 echo '<span id="action_contact" class="l10">Inviter</span>';
			 }
			 else
			 {
				 echo '<span id="action_contact" class="l10">Suppr</span>';
			 }
			 ?>
            <span class="l20">Pseudo</span>
            <span class="l10">Elément</span>
            <span class="l10">Niveau</span>
            <span class="l10">Prestige</span>
            <span class="l35">Dernière connexion</span>
        </div>
    </div>
    <div class="corps_scroll align_centre scroll_grand">
		<?php
		if($contacts)
		{
		foreach($contacts as $contact)
        {
			 if(isset($id_monstre))
			 {
				 if($combat->id_invites() != "" && in_array($contact->id(), $combat->id_invites()))
				 {
				 	$icone = '<span class="l10"><img id="'.$contact->id().'_icone" class="img_30 deja_invite" src="/webroot/img/icones/check.png"/></span>';
				 }
				 else
				 {
					$icone = '<span class="l10"><img id="'.$contact->id().'_icone" class="inviter img_30" src="/webroot/img/icones/plus.png"/></span>';
				 }
			 }
			 else
			 {
				$icone = '<span class="l10"><img id="'.$contact->id().'_icone" class="suppr img_20" src="/webroot/img/icones/faux.png"/></span>';
			 }
		?>
        <div id="<?= $contact->id(); ?>_contact" class="ligne_scroll contacts">
             <?= $icone; ?>
             <span id="<?= $contact->id(); ?>_pseudo" class="l20"><?= $contact->pseudo(); ?></span>
             <span class="l10"><img class="neutre img_20" src="<?= $contact->element_img(); ?>"/></span>
             <span class="l10"><?= $contact->niveau(); ?></span>
             <span class="l10"><?= $contact->prestige(); ?></span>
             <span class="l35"><?= $contact->derniere_connexion(); ?></span>
        </div>
		<?php
		}
		}?>
    </div>
</div>

<?php if(isset($id_monstre))
{ ?>
<a class="gris" href="/app/controllers/prepa_combats.php?idm=<?= $id_monstre; ?>"><div class="bouton form_gauche">Retour</div></a>
<a href="/app/controllers/prepa_combats.php?idm=<?= $id_monstre; ?>"><img class="icone_form_gauche" src="/webroot/img/icones/btn_retour.png"></a>

<div class="cache" id="id_monstre"><?= $id_monstre; ?></div>
<?php } ?>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
</div>

<div id="controles_contact" class="bulle_daide absolu">
    <img id="voir_contact" class="img_40 ib" src="/webroot/img/icones/oeil.png">
    <img id="ajouter_contact" class="img_40 ib" src="/webroot/img/icones/plus.png">
    <img id="supprimer_contact" class="img_40 ib" src="/webroot/img/icones/refuser.png">
    <img id="envoyer_message" class="img_40 ib" src="/webroot/img/icones/messages_normal.png">
</div>

<!-- Boites de dialogues -->
<div id="confirm_suppr" title="Supprimer ce contact ?">
</div>

<div id="trop_chasseurs" title="Trop de chasseurs de monstres !">
</div>


<script src="/webroot/js/contacts.js?nvd_r=xxx"></script>
