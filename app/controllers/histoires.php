<?php
require("include_path.php");
require("controleur_global.php");

//Détermination des histoires à afficher
$histoires_debloques = $joueur->histoires_debloques();
$histoires_vues = $joueur->histoires_vues();
$histoires = array();
$elem = $joueur->element();
for($i=0;$i<=10;$i++)
{
	if($i==0)
		{$id = $histoires_vues[0];}
	else if($i>0 && $i<=5)
		{$id = $i."_".$elem;}
	else
		{$id = (string) $i;}
	if(in_array($id, $histoires_debloques)) //Si l'histoire a été débloquée par le joueur
	{
		$histoire = new Histoire(array("id"=>$id, "debloquee"=>true));
	}
	else //Si l'histoire n'a pas été débloquée par le joueur
	{
		$histoire = new Histoire(array("id"=>$id, "debloquee"=>false));
	}
	$histoires[] = $histoire;
}


include_once("header.php");
include_once("histoires_view.php");
include_once("footer_view.php");
