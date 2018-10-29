<?php

class Message
{
	protected $id,
			  $id_conversation,
			  $expediteur,
			  $destinataire,
			  $date_envoi,
			  $contenu,
			  $lu;
			  
	
	public function hydrate(array $donnees)
  	{
    	foreach ($donnees as $key => $value)
    	{
      	$method = 'set'.ucfirst($key);
      		if (method_exists($this, $method))
      		{
        		$this->$method($value);
      		}
    	}
  	}
	
	public function __construct(array $donnees)
	{
		$this->hydrate($donnees);
	}
	
	
	//GETTERS
	public function id()
	{
		return $this->id;
	}
	
	public function id_conversation()
	{
		return $this->id_conversation;
	}
	
	public function expediteur()
	{
		return $this->expediteur;
	}
	
	public function destinataire()
	{
		return $this->destinataire;
	}
	
	public function date_envoi()
	{
		return $this->date_envoi;
	}
	
	public function contenu()
	{
		return $this->contenu;
	}
	
	public function lu()
	{
		return $this->lu;
	}
	
	//SETTERS
	public function setId($id)
	{
		$id = (int) $id;
		if($id>0)
		{
			$this->id = $id;	
		}
	}
	
	public function setId_conversation($id_conversation)
	{
		$id_conversation = (int) $id_conversation;
		if($id_conversation>0)
		{
			$this->id_conversation = $id_conversation;	
		}
	}
	
	public function setExpediteur($expediteur)
	{
		$expediteur = (int) $expediteur;
		if($expediteur>0)
		{
			$this->expediteur = $expediteur;	
		}
	}
	
	public function setDestinataire($destinataire)
	{
		$destinataire = (int) $destinataire;
		if($destinataire>0)
		{
			$this->destinataire = $destinataire;	
		}	
	}
	
	public function setDate_envoi($date_envoi)
	{
		$date_envoi = (string) $date_envoi;
		$this->date_envoi = $date_envoi;	
	}
	
	public function setContenu($contenu)
	{
		$contenu = (string) $contenu;
		$this->contenu = $contenu;	
	}
	
	public function setLu($lu)
	{
		$lu = (string) $lu;
		$this->lu = $lu;	
	}
	
	public function date_envoi_format_fr()
	{
		$date_envoi = strtotime($this->date_envoi());
		$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
		$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		return $jour[date("w", $date_envoi)]." ".date("d", $date_envoi)." ".$mois[date("n", $date_envoi)].", ".date("H", $date_envoi)."h".date("i", $date_envoi);
	}
	
	

	
}