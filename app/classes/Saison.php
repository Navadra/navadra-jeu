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
		return array((int) $classement[0], (int) $classement[1], (int) $classement[2]);
	}
	
	public function podium_prestige()
    {
		$prestige_saison = explode(",", $this->prestige());
		return array((int) $prestige_saison[0], (int) $prestige_saison[1], (int) $prestige_saison[2]);
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