<!-- Fond contenant le formulaire -->
<div class="fond l70 mh1 pb4">

<div class="titre">Messages</div>

<div class="ib bordure_sans_pos l20 mh1">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <span class="l100">Conversations</span>
        </div>
    </div>
    <div id="conversation_list" class="corps_scroll align_centre scroll_grand">
    	<?php foreach($conversations as $conversation)
		{
		if($conversation->nouveau_msg()=="oui")	{
			$style = "fond_beige_clair";
		}
		else {
			$style = "";
		}
		$correspondant = $manager->get($conversation->determiner_correspondant($joueur)); //Détermine le joueur avec qui le joueur connecté dialogue
		$liste_id_correspondants .= $correspondant->id().",";
		?>
        <div id="contact_<?= $correspondant->id(); ?>" class="ligne_scroll <?= $style; ?> pb2 ph2">
             <span class="l100 p1"><?= $correspondant->pseudo(); ?></span>
             <span class="cache"><?= $conversation->affichage_html(); ?></span>
             <span class="cache"><?= $correspondant->id(); ?></span>
             <span class="cache"><?= $conversation->id(); ?></span>
         </div>
         <?php
		}
		?>
        <span class="cache" id="liste_id_correspondants"><?= $liste_id_correspondants; ?></span>
    </div>
</div>

<div class="ib bordure_sans_pos l75 mh1">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <span class="l100"><?= $titre; ?></span>
        </div>
    </div>
    <div class="corps_scroll2 align_centre scroll_grand">
		<span class="ib mg10 l90 mh1 mb1 align_gauche p3">Message :</span>

        <form action="/app/controllers/messages.php" method="post">
        <span class="ib mg10 l70 md20 align_gauche"><textarea id="message" autocomplete="off" name="message" title="Entre 2 et 800 caractères"></textarea></span>
        <span><input type="hidden" name="id_destinataire" value="" /></span>


        <span class="ib mg10 l90 mh2 mb1 align_gauche p3">Conversation :</span>
        <span id="conversation" class="ib mg10 l70 md20 align_gauche"><?= $contenu_initial; ?></span>
    </div>
</div>

<span id="destinataire_initial" class="cache"><?= $id_destinataire_initial; ?></span>

<!-- Boutons pour valider le formulaire -->
    <input class="bouton form_droite" id="send_mesage" type="submit" name="valider" value="Envoyer" />
	<a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>
	</form>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
<!-- Fin du fond -->
</div>

<div id="liste_contacts_conv" class="bordure_sans_pos l20 absolu cache">
	<div class="entetes_scroll">
		<div class="ligne_scroll">
        	<span class="ib l7 mg5"><img id="masquer_contacts" class="img_20"src="/webroot/img/icones/faux.png"/></span>
            <span class="ib l80">Contacts</span>
        </div>
    </div>
    <div class="corps_scroll align_centre scroll_moyen">
    	<?php
		if($contacts)	{
			foreach($contacts as $contact) { ?>
			<div class="ligne_scroll pb2 ph2 contact">
				 <span class="l100 p1"><?= $contact->pseudo(); ?></span>
				 <span id="<?= $contact->id(); ?>" class="cache"></span>
			</div>
			<?php
			}
		}?>
	</div>
</div>

<img id="nouvelle_conv" class="absolu cache img_70" title="Nouvelle conversation" src="/webroot/img/icones/message_joueur.png"/>
<div id="info_input"></div>

<script src="/webroot/js/utils/formValidation.js?nvd_r=xxx"></script>
<script src="/webroot/js/messages.js?nvd_r=xxx"></script>
