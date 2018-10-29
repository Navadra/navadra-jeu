<?php

class Amelioration
{
	protected $id,
			  $id_joueur,
			  $titre,
			  $descriptif,
			  $votants,
			  $nb_votes,
			  $vues,
			  $statut,
			  $date_soumission;


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

	public function id_joueur()
	{
		return $this->id_joueur;
	}

	public function titre()
	{
		return $this->titre;
	}

	public function descriptif()
	{
		return $this->descriptif;
	}

	public function votants()
	{
		return $this->votants;
	}

	public function nb_votes()
	{
		return $this->nb_votes;
	}

	public function vues()
	{
		return $this->vues;
	}

	public function statut()
	{
		return $this->statut;
	}

	public function date_soumission()
	{
		return $this->date_soumission;
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

	public function setId_joueur($id_joueur)
	{
		$id_joueur = (int) $id_joueur;
		if($id_joueur>0)
		{
			$this->id_joueur = $id_joueur;
		}
	}

	public function setTitre($titre)
	{
		$this->titre = $titre;
	}

	public function setDescriptif($descriptif)
	{
		$this->descriptif = $descriptif;
	}

	public function setVotants($votants)
	{
		$this->votants = $votants;
	}

	public function setNb_votes($nb_votes)
	{
		$this->nb_votes = $nb_votes;
	}

	public function setVues($vues)
	{
		$this->vues = $vues;
	}

	public function setStatut($statut)
	{
		$this->statut = $statut;
	}

	public function setDate_soumission($date_soumission)
	{
		$this->date_soumission = $date_soumission;
	}

	//Fonctions de classe
	function afficher_nb_votes()
	{
		if($this->nb_votes() <= 1)
		{
			return $this->nb_votes()." vote";
		}
		else
		{
			return $this->nb_votes()." votes";
		}
	}

	function iconAccepted() {
		if($this->statut() == "realisee") {
			return "/webroot/img/icones/check.png";
		} elseif($this->statut() == "refusee") {
			return "/webroot/img/icones/refuser.png";
		}
	}








}
