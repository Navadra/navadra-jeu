<?php

class Conversation
{
	protected $id,
			  $joueur1,
			  $joueur2,
			  $date_dernier_msg,
			  $nouveau_msg,
			  $affichage_html;
			  
	
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
	
	public function joueur1()
	{
		return $this->joueur1;
	}
	
	public function joueur2()
	{
		return $this->joueur2;
	}
	
	public function date_dernier_msg()
	{
		return $this->date_dernier_msg;
	}
	
	public function nouveau_msg()
	{
		return $this->nouveau_msg;
	}
	
	public function affichage_html()
	{
		return $this->affichage_html;
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
	
	public function setJoueur1($joueur1)
	{
		$joueur1 = (int) $joueur1;
		if($joueur1>0)
		{
			$this->joueur1 = $joueur1;
		}
	}
	
	public function setJoueur2($joueur2)
	{
		$joueur2 = (int) $joueur2;
		if($joueur2>0)
		{
			$this->joueur2 = $joueur2;
		}	
	}
	
	public function setDate_dernier_msg($date_dernier_msg)
	{
		$date_dernier_msg = (string) $date_dernier_msg;
		$this->date_dernier_msg = $date_dernier_msg;	
	}
	
	public function setNouveau_msg($nouveau_msg)
	{
		$nouveau_msg = (string) $nouveau_msg;
		$this->nouveau_msg = $nouveau_msg;	
	}
	
	public function setAffichage_html($affichage_html)
	{
		$affichage_html = (string) $affichage_html;
		$this->affichage_html = $affichage_html;	
	}
	
	
	
	public function determiner_correspondant(Joueur $joueur)
	{
		if($joueur->id()== $this->joueur1())
			{return $this->joueur2();}
		elseif($joueur->id()== $this->joueur2())
			{return $this->joueur1();}
	}
	
	

	
}