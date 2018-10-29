<!-- Fond contenant le formulaire -->
<div class="fond l70 mh1 pb2">

<div class="titre">Histoire</div>

<div class="ib bordure l20 mh2">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <span class="l100">Histoire</span>
        </div>
    </div>
    <div class="corps_scroll align_centre scroll_grand">
    	<?php foreach($histoires as $histoire)
		{
		if($histoire->debloquee())
		{
			$style = "p2";
			if(!$joueur->histoires_vues() || !in_array($histoire->id(), $joueur->histoires_vues())) //Si le joueur n'a pas d'histoires vues ou qu'il n'a pas vu celle là
			{
				$style .= " fond_beige_clair";
			}
		}
		else
		{
			$style = "p0 bloquees";
		}
		?>
        <div class="ligne_scroll <?= $style; ?> pb4 ph4">
             <span class="l100"><?= $histoire->nom(); ?></span>
             <span class="cache"><?= $histoire->titre(); ?></span>
             <span class="cache"><?= $histoire->lien(); ?></span>
             <span class="cache"><?= $histoire->id(); ?></span>
         </div>
         <?php
		}
		?>
    </div>
</div>

<div class="ib bordure l75 mh1">
    <div class="entetes_scroll">
    	<div class="ligne_scroll">
            <span class="l100">Introduction</span>
        </div>
    </div>
    <div class="corps_scroll2 align_centre scroll_grand">
		<!-- C'est ici qu'on créé la iframe avec JS -->
    </div>
</div>

<a href="/index.php"><img id="closeWindow" class="titles" src="/webroot/img/icones/refuser.png" title="Revenir à l'île" /></a>
</div>

<script src="/webroot/js/histoires.js?nvd_r=xxx"></script>
