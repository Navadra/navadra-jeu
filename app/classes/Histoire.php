<?php

class Histoire
{
	protected $id,
			  $nom,
			  $titre,
			  $lien,
			  $debloquee;


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
		$this->hydrate($this->determiner_carac());
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

	public function titre()
	{
		return $this->titre;
	}

	public function lien()
	{
		return $this->lien;
	}

	public function debloquee()
	{
		return $this->debloquee;
	}

	//SETTERS
	public function setId($id)
	{
		$id = (string) $id;
		$this->id = $id;
	}

	public function setNom($nom)
	{
		$nom = (string) $nom;
		$this->nom = $nom;
	}

	public function setTitre($titre)
	{
		$titre = (string) $titre;
		$this->titre = $titre;
	}

	public function setLien($lien)
	{
		$lien = (string) $lien;
		$this->lien = $lien;
	}

	public function setDebloquee($debloquee)
	{
		$this->debloquee = $debloquee;
	}


	public function determiner_carac()
	{
		switch($this->id())
		{
			case "0_feu":
				$nom = "Introduction";
				if($this->debloquee())
				{
					$titre = "L'arrivée sur Navadra";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "1_feu":
				$nom = "Fin Tutoriel";
				if($this->debloquee())
				{
					$titre = "L'Hydre";
					$lien = "https://player.vimeo.com/video/193887534";
				}
				break;
			case "2_feu":
				$nom = "Chapitre 2";
				if($this->debloquee())
				{
					$titre = "Le nouveau chef ";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "3_feu":
				$nom = "Chapitre 3";
				if($this->debloquee())
				{
					$titre = "Anesae l’insensible";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "4_feu":
				$nom = "Chapitre 4";
				if($this->debloquee())
				{
					$titre = "L’injustice";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "5_feu":
				$nom = "Chapitre 5";
				if($this->debloquee())
				{
					$titre = "Mes deux plus grands combats";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "0_eau":
				$nom = "Introduction";
				if($this->debloquee())
				{
					$titre = "L'arrivée sur Navadra";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "1_eau":
				$nom = "Fin Tutoriel";
				if($this->debloquee())
				{
					$titre = "Un grand présage";
					$lien = "https://player.vimeo.com/video/193887534";
				}
				break;
			case "2_eau":
				$nom = "Chapitre 2";
				if($this->debloquee())
				{
					$titre = "Un apprentissage laborieux";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "3_eau":
				$nom = "Chapitre 3";
				if($this->debloquee())
				{
					$titre = "Redécouvrir la magie";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "4_eau":
				$nom = "Chapitre 4";
				if($this->debloquee())
				{
					$titre = "Esha, l’étranger";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "5_eau":
				$nom = "Chapitre 5";
				if($this->debloquee())
				{
					$titre = "Mon dernier combat";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "0_vent":
				$nom = "Introduction";
				if($this->debloquee())
				{
					$titre = "L'arrivée sur Navadra";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "1_vent":
				$nom = "Fin Tutoriel";
				if($this->debloquee())
				{
					$titre = "Le rite du passage";
					$lien = "https://player.vimeo.com/video/193887534";
				}
				break;
			case "2_vent":
				$nom = "Chapitre 2";
				if($this->debloquee())
				{
					$titre = "L’enseignement de Faresh";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "3_vent":
				$nom = "Chapitre 3";
				if($this->debloquee())
				{
					$titre = "Une maladie étrange";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "4_vent":
				$nom = "Chapitre 4";
				if($this->debloquee())
				{
					$titre = "L’appel de l’inconnu";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "5_vent":
				$nom = "Chapitre 5";
				if($this->debloquee())
				{
					$titre = "Un vent de destruction";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "0_terre":
				$nom = "Introduction";
				if($this->debloquee())
				{
					$titre = "L'arrivée sur Navadra";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "1_terre":
				$nom = "Fin Tutoriel";
				if($this->debloquee())
				{
					$titre = "Qui va le plus loin ?";
					$lien = "https://player.vimeo.com/video/193887534";
				}
				break;
			case "2_terre":
				$nom = "Chapitre 2";
				if($this->debloquee())
				{
					$titre = "La fin d’une amitié";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "3_terre":
				$nom = "Chapitre 3";
				if($this->debloquee())
				{
					$titre = "La chasse au sanglier";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "4_terre":
				$nom = "Chapitre 4";
				if($this->debloquee())
				{
					$titre = "Le Cyclope";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "5_terre":
				$nom = "Chapitre 5";
				if($this->debloquee())
				{
					$titre = "Se réjouir et tout perdre";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "6":
				$nom = "Chapitre 6";
				if($this->debloquee())
				{
					$titre = "La découverte";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "7":
				$nom = "Chapitre 7";
				if($this->debloquee())
				{
					$titre = "Et tout bascula…";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "8":
				$nom = "Chapitre 8";
				if($this->debloquee())
				{
					$titre = "La fin d’une vie, le début d’une autre";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "9":
				$nom = "Chapitre 9";
				if($this->debloquee())
				{
					$titre = "Trop, c’est trop !";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
			case "10":
				$nom = "Chapitre 10";
				if($this->debloquee())
				{
					$titre = "Fin et renouveau";
					$lien = "https://player.vimeo.com/video/193887469";
				}
				break;
		}
		if(!$this->debloquee())
		{
			$titre = "?";
			$lien = '?';
		}
		return array("nom" => $nom, "titre" => $titre, "lien" => $lien);
	}




}
