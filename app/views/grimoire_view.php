
<!-- Tuteur -->
<img alt="" id="tuteur_cote" src="<?= $mentor; ?>" />

<!-- Bulle du personnage-->
<div id="bulle_bas" class="bulle">
    <!-- le texte dans la bulle -->
    <span id="txt_bulle"><?= $msg_tuteur; ?></span>
</div>

<?php if($joueur->tuto() != "fini"){ ?>
<div id="commandes_tuto_bas">
	<img id="tuto_precedent" src="/webroot/img/icones/chevron1.png" />
    <img id="tuto_suivant" src="/webroot/img/icones/chevron2.png" />
</div>
<?php } ?>

<!-- Boutons pour changer les dialogues du tuteur en cas de changement d'élément -->
<div id="commandes_grimoire">
</div>

<!-- Fond -->
<div class="fond l70 mh1 h70">

    <div class="titre">Grimoire</div>

    <!-- <div id="info_sorts" class="p2 md4">Sorts connus : <?= $nb_sorts; ?></div> -->

    <div id="elements_grimoire" class="ib l80">
        <img id="feu" class="md4" title="" src="/webroot/img/elements/feu.png"/>
        <img id="eau" class="md4" title="" src="/webroot/img/elements/eau.png"/>
        <img id="vent" class="md4" title="" src="/webroot/img/elements/vent.png"/>
        <img id="terre" title="" src="/webroot/img/elements/terre.png"/>
    </div>

    <div id="sorts_feu" class="ib l80 mh1">
        <?php foreach($sorts["feu"] as $sort)
        {
            $caracs = $sort->carac_sort();
        ?>
        <div id="<?= $sort->num(); ?>" class="ib l12 align_haut">
            <img id="icone_<?= $sort->num(); ?>" class="icones_sorts_grimoire" src="<?= $sort->icone(); ?>"/>
						<span class="limitingFactor ib l80 p1 g <?= $sort->couleur(); ?>"><?= $sort->limitingFactor($joueur); ?></span>
            <span id="nom_<?= $sort->num(); ?>" class="cache"><?= $caracs["nom"]; ?></span>
            <span id="niveau_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau(); ?></span>
            <span id="element_<?= $sort->num(); ?>" class="cache"><?= $sort->element1(); ?></span>
            <span id="cout_<?= $sort->num(); ?>" class="cache"><?= $sort->cout(); ?></span>
            <span id="niveau_requis_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau_requis(); ?></span>
            <span id="descriptif_categorie_<?= $sort->num(); ?>" class="cache"><?= $sort->descriptif_categorie(); ?></span>
        </div>
        <?php } ?>
    </div>

    <div id="sorts_eau" class="ib l80 mh1">
        <?php foreach($sorts["eau"] as $sort)
        {
            $caracs = $sort->carac_sort();
        ?>
        <div id="<?= $sort->num(); ?>" class="ib l12 align_haut">
            <img id="icone_<?= $sort->num(); ?>" class="icones_sorts_grimoire" src="<?= $sort->icone(); ?>"/>
						<span class="limitingFactor ib l80 p1 g <?= $sort->couleur(); ?>"><?= $sort->limitingFactor($joueur); ?></span>
            <span id="nom_<?= $sort->num(); ?>" class="cache"><?= $caracs["nom"]; ?></span>
            <span id="niveau_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau(); ?></span>
            <span id="element_<?= $sort->num(); ?>" class="cache"><?= $sort->element1(); ?></span>
            <span id="cout_<?= $sort->num(); ?>" class="cache"><?= $sort->cout(); ?></span>
            <span id="niveau_requis_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau_requis(); ?></span>
            <span id="descriptif_categorie_<?= $sort->num(); ?>" class="cache"><?= $sort->descriptif_categorie(); ?></span>
        </div>
        <?php } ?>
    </div>

    <div id="sorts_vent" class="ib l80 mh1">
        <?php foreach($sorts["vent"] as $sort)
        {
            $caracs = $sort->carac_sort();
        ?>
        <div id="<?= $sort->num(); ?>" class="ib l12 align_haut">
            <img id="icone_<?= $sort->num(); ?>" class="icones_sorts_grimoire" src="<?= $sort->icone(); ?>"/>
						<span class="limitingFactor ib l80 p1 g <?= $sort->couleur(); ?>"><?= $sort->limitingFactor($joueur); ?></span>
            <span id="nom_<?= $sort->num(); ?>" class="cache"><?= $caracs["nom"]; ?></span>
            <span id="niveau_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau(); ?></span>
            <span id="element_<?= $sort->num(); ?>" class="cache"><?= $sort->element1(); ?></span>
            <span id="cout_<?= $sort->num(); ?>" class="cache"><?= $sort->cout(); ?></span>
            <span id="niveau_requis_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau_requis(); ?></span>
            <span id="descriptif_categorie_<?= $sort->num(); ?>" class="cache"><?= $sort->descriptif_categorie(); ?></span>
        </div>
        <?php } ?>
    </div>

    <div id="sorts_terre" class="ib l80 mh1">
        <?php foreach($sorts["terre"] as $sort)
        {
            $caracs = $sort->carac_sort();
        ?>
        <div id="<?= $sort->num(); ?>" class="ib l12 align_haut">
            <img id="icone_<?= $sort->num(); ?>" class="icones_sorts_grimoire" src="<?= $sort->icone(); ?>"/>
						<span class="limitingFactor ib l80 p1 g <?= $sort->couleur(); ?>"><?= $sort->limitingFactor($joueur); ?></span>
            <span id="nom_<?= $sort->num(); ?>" class="cache"><?= $caracs["nom"]; ?></span>
            <span id="niveau_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau(); ?></span>
            <span id="element_<?= $sort->num(); ?>" class="cache"><?= $sort->element1(); ?></span>
            <span id="cout_<?= $sort->num(); ?>" class="cache"><?= $sort->cout(); ?></span>
            <span id="niveau_requis_<?= $sort->num(); ?>" class="cache"><?= $sort->niveau_requis(); ?></span>
            <span id="descriptif_categorie_<?= $sort->num(); ?>" class="cache"><?= $sort->descriptif_categorie(); ?></span>
        </div>
        <?php } ?>
    </div>

		<div id="upgradeSpells" class="ib l80 mh1">
		<?php for($i=1;$i<=6;$i++) { ?>
			<div class="ib l12">
    		<img id="upgradeSpell<?= $i; ?>" class="l80 learnableSpells" src="/webroot/img/spells/feu_1.png"/>
				<span class="numUpgrade cache"></span>
				<span class="nameUpgrade cache"></span>
				<span class="lvlUpgrade cache"></span>
			</div>
    	<?php } ?>
    </div>

		<div id="costUpgrades" class="ib l80 mh1">
		<?php for($i=1;$i<=6;$i++) { ?>
			<div class="ib l12">
				<span class="g p4"></span>
			</div>
    	<?php } ?>
    </div>

    <div id="displayMsg" class="ib l100">
        <span class="g p4">Sélectionne un des 4 éléments pour afficher ses sorts</span>
    </div>

    <!-- Message d'erreur si l'utilisateur n'a pas assez de Pyrs-->
    <div id="error_msg_spells" class="div_centrale p1 rouge msg_err_grimoire"></div>

    <!-- Validate button -->
    <div id="validate_learning" class="div_centrale mh05">
			<input class="bouton form_droite" type="submit" name="valider" value="Apprendre" />
      <a href="#"><img class="icone_form_droite" src="/webroot/img/icones/play.png"></a>
    </div>

    <!-- Return button -->
    <a class="gris" href="#"><div id="reinitialiser_sort" class="bouton form_gauche">Réinitialiser</div></a>
    <a href="#"><img class="icone_form_gauche" src="/webroot/img/icones/btn_reinitialiser.png"></a>

    <!-- Form to submit spell learning -->
    <form action="/app/controllers/grimoire.php" method="post">
        <input type="hidden" name="num_sort" value="<?= $_POST["num_sort"]; ?>">
        <input type="hidden" name="reset" value="<?= $_POST["reset"]; ?>">
        <input type="hidden" name="pyrs_feu" value="<?= $joueur->pyrs_feu(); ?>">
        <input type="hidden" name="pyrs_eau" value="<?= $joueur->pyrs_eau(); ?>">
        <input type="hidden" name="pyrs_vent" value="<?= $joueur->pyrs_vent(); ?>">
        <input type="hidden" name="pyrs_terre" value="<?= $joueur->pyrs_terre(); ?>">
        <input type="hidden" name="achat" value="<?= $achat; ?>">
        <input type="hidden" name="chgt_elem" value="<?= $chgt_elem; ?>">
        <input type="hidden" name="reinitialiser" value="<?= $reinitialiser; ?>">
        <input type="hidden" name="pseudo" value="<?= $joueur->pseudo(); ?>">
        <input type="hidden" name="niveau" value="<?= $joueur->niveau(); ?>">
        <input type="hidden" name="element" value="<?= $joueur->element(); ?>">
        <input type="hidden" name="ancien_tuteur" value="<?= $mentor; ?>">
        <input type="hidden" name="nouveau_tuteur" value="<?= $joueur->tuteur(); ?>">
        <input type="hidden" name="element1" value="<?= $element1; ?>">
        <input type="hidden" name="element2" value="<?= $element2; ?>">
        <input type="hidden" name="nb_sorts" value="<?= $sorts_manager->nb_sorts($joueur); ?>">
        <input type="hidden" name="nb_sorts_debloques" value="<?= $joueur->nb_sorts_debloques(); ?>">
        <input type="hidden" name="niv_pour_nouveau_sort" value="<?= $niv_pour_nouveau_sort; ?>">
    </form>

    <!-- Boites de dialogues -->
    <div id="confirm_apprendre" title="Apprendre ce sort ?"></div>
    <div id="confirm_reinitialiser" title="Oublier tous tes sorts ?"></div>
    <div id="info_reinitialiser" title="Tu n'as pas assez de Pyrs !"></div>

		<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>

		<?php if($sorts_manager->spellsToBuy($joueur) == 1){
			echo('<div id="spellsToBuy" class="p4">Apprends encore <span class="p6 g">'.$sorts_manager->spellsToBuy($joueur).'</span> sort.</div>');
		} elseif($sorts_manager->spellsToBuy($joueur) > 1){
			echo('<div id="spellsToBuy" class="p4">Apprends encore <span class="p6 g">'.$sorts_manager->spellsToBuy($joueur).'</span> sorts.</div>');
		}?>

</div>

<div id="spellDescription" class="descriptif_sort absolu">
		<span class="p0"></span>
</div>

<?php if($achat == "oui"){
	$caracs = $spellBought->carac_sort();	?>
<div id="displaySpellBought">
	<span class="p3 ib l100">Tu viens d'apprendre le sort :</span>
	<span id="spellBoughtName" class="p4 g ib l100 mh4 <?= $spellBought->couleur(); ?>"><?= $caracs["nom"]; ?> niv.<?= $spellBought->niveau(); ?></span>
	<span class="ib l100 mh2"><img id="spellBoughtIcon" class="l20" src="<?= $spellBought->icone(); ?>"/></span>
	<span id="spellBoughtElement" class="cache"><?= $spellBought->element1(); ?></span>
</div>
<?php } ?>

<!-- Div cachées utilisées par JS-->
<div id="etape_tuto" class="cache"><?= $joueur->tuto(); ?></div>
<div id="nom_tuteur" class="cache"><?= $joueur->tuteur(); ?></div>
<div id="sexe_joueur" class="cache"><?= $joueur->sexe(); ?></div>
<div id="pseudo" class="cache"><?= $joueur->pseudo(); ?></div>
<?php if(isset($challenge)){
	echo('<div id="challengeElement" class="cache">'.$challenge->getElement().'</div>');
} ?>

<audio preload="auto" id="son_nouveau_sort">
	 <source src = "/webroot/sons/nouveau_sort.ogg" type="audio/ogg" />
     <source src = "/webroot/sons/nouveau_sort.mp3" type="audio/mp3" />
</audio>

<script src="/webroot/js/utils/anim.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/grimoireTools.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/math.js?nvd_r=xxx"></script>
<script src="/webroot/js/utils/spells.js?nvd_r=xxx"></script>
<script src="/webroot/js/grimoire.js?nvd_r=xxx"></script>
