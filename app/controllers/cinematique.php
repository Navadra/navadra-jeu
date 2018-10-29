<?php
require("include_path.php");
require("controleur_global.php");

if($joueur->tuto() != "fini"){
	$histoire = new Histoire(array("id"=>"0_".$joueur->element(), "debloquee"=>true));
} else {
	$histoire = new Histoire(array("id"=>"1_".$joueur->element(), "debloquee"=>true));
}


include_once("head_references.php"); //Inclure la partie Head de la page HTML avec les différents liens
include_once("cinematique_view.php");
include_once("footer_deco_view.php");
