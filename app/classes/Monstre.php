<?php

class Monstre
{
	protected $id,
			  $id_joueur,
			  $nom,
			  $element,
			  $niveau,
			  $nb_chasseurs,
			  $categorie,
			  $position,
			  $img,
			  $dead,
			  $date_creation;


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

	public function nom()
	{
		return $this->nom;
	}

	public function element()
	{
		return $this->element;
	}

	public function niveau()
	{
		return $this->niveau;
	}

	public function nb_chasseurs()
	{
		return $this->nb_chasseurs;
	}

	public function categorie()
	{
		return $this->categorie;
	}

	public function position()
	{
		return $this->position;
	}

	public function img()
	{
		return $this->img;
	}

	public function dead()
	{
		return $this->dead;
	}

	public function date_creation()
	{
		return $this->date_creation;
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

	public function setId_joueur($id)
	{
		$id = (int) $id;
		if($id>0)
		{
			$this->id_joueur = $id;
		}
	}

	public function setNom($nom)
	{
		$nom = (string) $nom;
		$this->nom = $nom;
	}

	public function setElement($element)
	{
		$element = (string) $element;
		$this->element = $element;
	}

	public function setNiveau($niveau)
	{
		$niveau = (int) $niveau;
		if($niveau>0)
		{
			$this->niveau = $niveau;
		}
	}

	public function setNb_chasseurs($nb_chasseurs)
	{
		$nb_chasseurs = (float) $nb_chasseurs;
		if($nb_chasseurs>0)
		{
			$this->nb_chasseurs = $nb_chasseurs;
		}
	}

	public function setCategorie($categorie)
	{
		$categorie = (string) $categorie;
		$this->categorie = $categorie;
	}

	public function setPosition($position)
	{
		if(is_array($position))
		{
			$this->position = $position;
		}
	}

	public function setImg($img)
	{
		$img = (string) $img;
		$this->img = $img;
	}

	public function setDead($dead)
	{
		$dead = (int) $dead;
		$this->dead = $dead;
	}

	public function setDate_creation($date_creation)
	{
		$date_creation = (string) $date_creation;
		$this->date_creation = $date_creation;
	}

	public function class_monstre()
	{
		if($this->nb_chasseurs() == 1)
		{
			return "monstre_petit";
		}
		elseif($this->nb_chasseurs() == 8)
		{
			return "monstre_gros";
		}
		else
		{
			return "monstre_moyen";
		}
	}

	public function info_nb_chasseurs()
	{
		switch($this->nb_chasseurs()) //Détermination du texte de l'info-bulle
		{
		case 1 :
			return "Affrontable seul";
		case 2.5 :
			return "Chasseurs recommandés : 2 à 3";
		case 4.5 :
			return "Chasseurs recommandés : 4 à 5";
		case 8 :
			return "Chasseurs recommandés : 6 à 10";
		}
	}

	public function couleur_monstre()
	{
		switch($this->element())
		{
		case "feu" :
			return "rouge";
		case "eau" :
			return "bleu";
		case "vent" :
			return "jaune";
		case "terre" :
			return "vert";
		}
	}

	public function couleur_bordure_hex()
	{
		switch($this->element())
		{
		case "feu" :
			return "#ad0101";
		case "eau" :
			return "#016dad";
		case "vent" :
			return "#f2b819";
		case "terre" :
			return "#0aae02";
		}
	}

	public function couleur_nom_hex()
	{
		switch($this->element())
		{
		case "feu" :
			return "#eec2c3";
		case "eau" :
			return "#caecf6";
		case "vent" :
			return "#f1eec1";
		case "terre" :
			return "#ccf7ca";
		}
	}

	public function element_img()
	{
		switch($this->element())
		{
			case "feu":
				return "/webroot/img/elements/feu.png";
				break;
			case "eau":
				return "/webroot/img/elements/eau.png";
				break;
			case "vent":
				return "/webroot/img/elements/vent.png";
				break;
			case "terre":
				return "/webroot/img/elements/terre.png";
				break;
		}
	}

	public function img_tete(){
		$img = $this->img();
		$motif = "#\.png$#";
		$remplacement = "_tete.png";
		$img = preg_replace($motif, $remplacement, $img);
		return $img;
	}

	public function couleur_niveau_monstre(Joueur $joueur) //Détermination de la couleur du niveau du monstre
	{
		if($this->niveau() <= $joueur->niveau() -2) //Si le monstre a au moins 2 niveaux de moins que le joueur
		{
			return "#00A452";
		}
		elseif(abs($this->niveau() - $joueur->niveau()) <= 1) //S'il y a 1 niveau d'écart max entre le monstre et le joueur
		{
			return "#FF702B";
		}
		elseif($this->niveau() >= $joueur->niveau() + 2) //Si le monstre a au moins 2 niveaux de plus que le joueur
		{
			return "#FF0000";
		}
	}

	public function gain_prestige($nb_chasseurs, $timeSlot)	{
		$base_gain = 2*pow($this->niveau(), 0.8) + 5; //Détermine le nbre de pts de prestige gagnés en fonction du niveau du monstre
		$gain = $base_gain * max(1 + 0.4*($this->nb_chasseurs() - $nb_chasseurs), 0);
		if($this->nb_chasseurs() != 1) {
			$gain = $gain*$this->nb_chasseurs()/2; //On augmente les gains si c'est un monstre multi-joueurs
		}
		if($timeSlot != "NoTimeSlot"){
			return ceil($gain/3);
		} else {
			return round($gain);
		}
	}

	public function perte_prestige($nb_chasseurs, $timeSlot) {
		$base_perte = pow($this->niveau(), 0.8) + 5/2; //Détermine le nbre de pts de prestige perdus en fonction du niveau du monstre
		$perte = $base_perte * max(1 + 0.4*($nb_chasseurs - $this->nb_chasseurs()), 0);
		if($this->nb_chasseurs() != 1) {
			$perte = $perte*$this->nb_chasseurs()/2; //On augmente les gains si c'est un monstre multi-joueurs
		}
		if($timeSlot != "NoTimeSlot"){
			return -1*ceil($perte/3);
		} else {
			return -1*round($perte);
		}
	}

	public function pm()
	{
		$joueur_fictif = new Joueur(array("niveau" => $this->niveau())); //On créé un joueur fictif
		$coeffPlayer = 0.33;
		$bonusType = 0.67;
		$base = ceil($joueur_fictif->endu()*$coeffPlayer);
		switch($this->categorie())
		{
			case "offensif":
				$base = (1+$bonusType)*$base;
				break;
			case "equilibre":
				$base = $base;
				break;
			case "defensif":
				$base = $bonusType*$base;
				break;
		}
		if($this->nb_chasseurs() == 4.5) //Si ce sont des monstres multijoueurs balèzes on leur donne un bonus
		{
			$base = $base * 1.05;
		}
		elseif($this->nb_chasseurs() == 8) //Si ce sont des monstres multijoueurs balèzes on leur donne un bonus
		{
			$base = $base * 1.1;
		}
		return round($base);
	}

	public function endu()
	{
		$joueur_fictif = new Joueur(array("niveau" => $this->niveau())); //On créé un joueur fictif
		$coeffPlayer = 3.5;
		$bonusType = 0.67;
		$base = floor($joueur_fictif->pm()*$coeffPlayer+0.07*$joueur_fictif->pm()*$this->niveau());
		switch($this->categorie())
		{
			case "offensif":
				$base = $bonusType*$base;
				break;
			case "equilibre":
				$base = $base;
				break;
			case "defensif":
				$base = (1+$bonusType)*$base;
				break;
		}
		return round($this->nb_chasseurs() * $base);
	}

	public function descriptif_pm()
	{
		$max_degats = round(1.2*$this->pm());
		$min_degats = max(round(0.8*$this->pm()), 0);

		$descriptif = "Ce monstre inflige chaque tour entre ".$min_degats." et ".$max_degats." dégâts.";

		return $descriptif;
	}

	public function descriptif_endu()
	{
		$descriptif = "Ce monstre peut encaisser ".$this->endu()." dégâts avant d'être K.O.";

		return $descriptif;
	}

	public function temps_avant_fuite()
	{
		switch($this->nb_chasseurs())
		{
			case 1 :
				return 1;
				break;
			case 2.5 :
				$duree = 3*24*60*60; //durée en secondes avant que le monstre ne disparaisse
				break;
			case 4.5 :
				$duree = 4*24*60*60;
				break;
			case 8 :
				$duree = 6*24*60*60;
				break;
		}
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$temps_restant = $duree - (time() - strtotime($this->date_creation()));
		$temps_restant = max(0, floor($temps_restant/60));
		return $temps_restant; //on renvoi le temps restant en minute
	}

	public function loading_msg(){
	  $rand = rand(1, 6);
	  switch($rand){
		  case 1 :
		  	return "Chatouillage du monstre...";
			break;
		  case 2 :
		  	return "Révision de dernière minute des sorts...";
			break;
		  case 3 :
		  	return "Taquinage du monstre...";
			break;
		  case 4 :
		  	return "Inspection des alentours...";
			break;
		  case 5 :
		  	return "Adoption de la posture du chimpanzé déchainé...";
			break;
		  case 6 :
		  	return "Adoption de la posture du rapace vorace...";
			break;
      }
	}




}
