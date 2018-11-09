<?php

class Saison
{
	protected $id,
			  $nom,
			  $classement,
			  $prestige,
			  $vues;
			  
	
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
	
	public function nom()
	{
		return $this->nom;
	}
	
	public function classement()
	{
		return $this->classement;
	}
	
	public function prestige()
	{
		return $this->prestige;
	}
	
	public function vues()
	{
		return $this->vues;
	}
	
	//SETTERS
	public function setId($id)
	{
		$id = (int) $id;
		$this->id = $id;	
	}
	
	public function setNom($nom)
	{
		$nom = (string) $nom;
		$this->nom = $nom;	
	}
	
	public function setClassement($classement)
	{
		$classement = (string) $classement;
		$this->classement = $classement;	
	}	
	
	public function setPrestige($prestige)
	{
		$prestige = (string) $prestige;
		$this->prestige = $prestige;	
	}	
	
	public function setVues($vues)
	{
		$vues = (string) $vues;
		$this->vues = $vues;	
	}	
	
	public function podium()
    {
		$classement = explode(",", $this->classement());
		$podium = array();
		if(isset($classement[0])){
			array_push($podium, (int) $classement[0]);
		}
		if(isset($classement[1])){
			array_push($podium, (int) $classement[1]);
		}
		if(isset($classement[2])){
			array_push($podium, (int) $classement[2]);
		}
		return $podium;
	}
	
	public function podium_prestige()
    {
			$prestige_saison = explode(",", $this->prestige());
			$podium = array();
		if(isset($prestige_saison[0])){
			array_push($podium, (int) $prestige_saison[0]);
		}
		if(isset($prestige_saison[1])){
			array_push($podium, (int) $prestige_saison[1]);
		}
		if(isset($prestige_saison[2])){
			array_push($podium, (int) $prestige_saison[2]);
		}
		return $podium;
	}
	
	public function classement_joueur(Joueur $joueur)
    {
		$classement = explode(",", $this->classement());
		$nb_joueurs = sizeof($classement);
		$i = 1;
		foreach($classement as $id_joueur)
		{
			if($id_joueur == $joueur->id())
			{
				return $i;
			}
			else
			{
				$i++;
			}
		}
	}

}