<?php

class Sort
{
	protected $id,
			  $id_joueur,
			  $num,
			  $niveau,
			  $icone, //not stored in DB
			  $element1;


	public function hydrate(array $donnees) {
		foreach ($donnees as $key => $value) {
		  $method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) {
			  $this->$method($value);
			}
		}
		$this->setIcone("/webroot/img/spells/".$this->element1()."_".$this->num().".png");
   }

	public function __construct(array $donnees) {
		$this->hydrate($donnees);
	}


	//GETTERS
	public function id() {
		return $this->id;
	}

	public function id_joueur() {
		return $this->id_joueur;
	}

	public function num() {
		return $this->num;
	}

	public function niveau() {
		return $this->niveau;
	}

	public function element1() {
		return $this->element1;
	}

	public function icone()
	{
		return $this->icone;
	}

	//SETTERS
	public function setId($id) {
		$id = (int) $id;
		if($id>0)
		{
			$this->id = $id;
		}
	}

	public function setId_joueur($id) {
		$id = (int) $id;
		if($id>0)
		{
			$this->id_joueur = $id;
		}
	}

	public function setNum($num) {
		$num = (int) $num;
		if($num>0)
		{
			$this->num = $num;
		}
	}

	public function setNiveau($niveau) {
		$niveau = (int) $niveau;
		if($niveau>0)
		{
			$this->niveau = $niveau;
		}
	}

	public function setElement1($element1) {
		$element1 = (string) $element1;
		$this->element1 = $element1;
	}

	public function setIcone($icone)
	{
		$icone = (string) $icone;
		$this->icone = $icone;
	}


	//GAME FUNCTIONS
	public function cout()
	{
		return $this->niveau()*7;
	}

	public function blackAndWhiteIcon()
	{
		$this->setIcone(preg_replace('#\.png$#', '_nb.png', $this->icone()));
	}

	public function colorIcon()
	{
		$this->setIcone(preg_replace('#_nb\.png$#', '.png', $this->icone()));
	}

	public function carac_sort()
	{
		switch($this->num()) {
			case 1: return array("nom" => "Boule de feu",           "element1" => "feu",    "categorie" => "offensif",                        );
			case 2: return array("nom" => "Brûlure",                "element1" => "feu",    "categorie" => "offensif condition",              );
			case 3: return array("nom" => "Sacrifice",              "element1" => "feu",    "categorie" => "offensif probabilite",            );
			case 4: return array("nom" => "Feu du désespoir",       "element1" => "feu",    "categorie" => "defensif condition",              );
			case 5: return array("nom" => "Mur de feu",             "element1" => "feu",    "categorie" => "offensif reussite",               );
			case 6: return array("nom" => "Pacte de flammes",       "element1" => "feu",    "categorie" => "offensif reussite",               );
			case 7: return array("nom" => "Frappe incandescente",   "element1" => "feu",    "categorie" => "defensif condition",              );
			case 8: return array("nom" => "Brouillard aveuglant",   "element1" => "eau",    "categorie" => "defensif tour",                   );
			case 9: return array("nom" => "Puissance des flots",    "element1" => "eau",    "categorie" => "defensif",                        );
			case 10: return array("nom" => "Lame de fond",          "element1" => "eau",    "categorie" => "offensif tour",                   );
			case 11: return array("nom" => "Déferlante",            "element1" => "eau",    "categorie" => "offensif reussite",               );
			case 12: return array("nom" => "Gel",                   "element1" => "eau",    "categorie" => "offensif condition",              );
			case 13: return array("nom" => "Protection aquatique",  "element1" => "eau",    "categorie" => "defensif condition",              );
			case 14: return array("nom" => "Marée haute",           "element1" => "eau",    "categorie" => "defensif reussite",               );
			case 15: return array("nom" => "Mistral",               "element1" => "vent",   "categorie" => "offensif condition",              );
			case 16: return array("nom" => "Bourrasque",            "element1" => "vent",   "categorie" => "offensif condition reussite",     );
			case 17: return array("nom" => "Vengeance fulgurante",  "element1" => "vent",   "categorie" => "offensif reussite",               );
			case 18: return array("nom" => "Rafale",                "element1" => "vent",   "categorie" => "defensif condition probabilite",  );
			case 19: return array("nom" => "Oeil du cyclone",       "element1" => "vent",   "categorie" => "offensif proportionel",           );
			case 20: return array("nom" => "Tornade",               "element1" => "vent",   "categorie" => "offensif tour",                   );
			case 21: return array("nom" => "Tourmente céleste",     "element1" => "vent",   "categorie" => "defensif probabilite",            );
			case 22: return array("nom" => "Enracinement",          "element1" => "terre",  "categorie" => "defensif proportionel",           );
			case 23: return array("nom" => "Armure d'écorce",       "element1" => "terre",  "categorie" => "defensif",                        );
			case 24: return array("nom" => "Montée de sève",        "element1" => "terre",  "categorie" => "defensif reussite",               );
			case 25: return array("nom" => "Jet de poison",         "element1" => "terre",  "categorie" => "offensif proportionel",           );
			case 26: return array("nom" => "Etau végétal",          "element1" => "terre",  "categorie" => "offensif",                        );
			case 27: return array("nom" => "Régénération",          "element1" => "terre",  "categorie" => "defensif reussite",               );
			case 28: return array("nom" => "Drain de puissance",    "element1" => "terre",  "categorie" => "defensif tour",                   );
		}
	}

	public function nom()
	{
		$caracs = $this->carac_sort();
		return $caracs["nom"];
	}

	public function categorie()
	{
		$caracs = $this->carac_sort();
		return $caracs["categorie"];
	}

	public function base_effet()
	{
    	$base = 4*pow($this->niveau(),2.19) + 1;
		return round($base);
	}

	public function effet(Joueur $joueur, $tour, $reussite) //Permet de connaitre l'effet du sort en fonction du tour de combat et du nb de réussites d'un même sort de suite
	{
		$tour = $tour - 1;
		switch($this->num())
		{
			case 1:
				return round(1.3 * $this->base_effet());
			case 2:
				return round(1.6 * $this->base_effet());
			case 3:
				return round(5+5*$this->niveau());  //Pourcentage de chance
			case 4:
				return round(2 * $this->base_effet()) ;
			case 5:
				return round( min((0.9 + 0.15*$reussite),1.5) * $this->base_effet());
			case 6:
				return round( min((0.9 + 0.15*$reussite),1.5) * $this->base_effet());
			case 7:
				return round(50 + 10*$this->niveau()) ;  //En pourcent des dégâts infligés
			case 8:
				return round( min((0.8 + 0.1*$tour), 1.2) * $this->base_effet());
			case 9:
				return round($this->base_effet());
			case 10:
				return round( min((0.8 + 0.1*$tour), 1.2) * $this->base_effet());
			case 11:
				return round( min((0.7 + 0.25*$reussite),1.7) * $this->base_effet());
			case 12:
				return round(2 * $this->base_effet()) ;
			case 13:
				return round(2 * $this->base_effet()) ;
			case 14:
				return round( min((0.7 + 0.25*$reussite),1.7) * $this->base_effet());
			case 15:
				return round( 2.86 * $this->base_effet() );
			case 16:
				return round( min((1.1 + 0.15*$reussite), 1.8) * $this->base_effet());
			case 17:
				return round( min((1.05 + 0.15*$reussite), 1.65) * $this->base_effet());
			case 18:
				return round( max(2*100* ($this->proba_esquive($joueur)+0.03*($this->niveau()-5)), 0) ) ; //Pourcentage de chance
			case 19:
				return round(2 * $this->base_effet()) ;
			case 20:
				return round( max((1.2 - 0.1*$tour) * $this->base_effet(),0.8) );
			case 21:
				return round( max(100* ($this->proba_esquive($joueur)+0.03*($this->niveau()-5)), 0) ) ; //Pourcentage de chance
			case 22:
				return round( 1.7 * $this->base_effet() );
			case 23:
				return round( $this->base_effet() );
			case 24:
				return round( min((0.9 + 0.15*$reussite), 1.5) * $this->base_effet());
			case 25:
				return round( 2 * $this->base_effet() );
			case 26:
				return round( 1.15 * $this->base_effet() );
			case 27:
				return round( min((0.9 + 0.15*$reussite), 1.5) * $this->base_effet());
			case 28:
				return round( min((0.8 + 0.1*$tour), 1.2) * $this->base_effet());
		}
	}

	public function proba_esquive(Joueur $joueur) //Renvoie un nombre entre 0 et 1
	{
		return (0.52-0.1)*pow((50-$joueur->niveau())/49, 5) + 0.1 + 0.03*($this->niveau()-5);
	}

	public function effet_elem(Joueur $joueur, $tour, $reussite, $element_monstre)
	{
		$effet = $this->effet($joueur, $tour, $reussite);
		if(is_array($effet))
		{
			$effet[0] = round( $this->facteur_elem_atq($element_monstre)/100 * $effet[0] );
			$effet[1] = round( $this->facteur_elem_atq($element_monstre)/100 * $effet[1] );
		}
		else
		{
			$effet = round( $this->facteur_elem_atq($element_monstre)/100 * $effet );
		}
		return $effet;
	}

	public function facteur_elem_atq($elem) //Détermine le facteur a appliquer aux dégâts du sort quand il attaque un monstre d'un élément donné
	{
		$bonus = 1.15;
		$malus = 0.85;
		$profil["feu"] = 0;
		$profil["eau"] = 0;
		$profil["vent"] = 0;
		$profil["terre"] = 0;
    $profil[$this->element1()] = 100;
		switch($elem)
		{
			case "feu":
				$total = $profil["feu"] + $bonus*$profil["eau"] + $profil["vent"] + $malus*$profil["terre"];
				break;
			case "eau":
				$total = $malus*$profil["feu"] + $profil["eau"] + $bonus*$profil["vent"] + $profil["terre"];
				break;
			case "vent":
				$total = $profil["feu"] + $malus*$profil["eau"] + $profil["vent"] + $bonus*$profil["terre"];
				break;
			case "terre":
				$total = $bonus*$profil["feu"] + $profil["eau"] + $malus*$profil["vent"] + $profil["terre"];
				break;
		}
		return round($total);
	}

	public function niveau_requis()	{
		return ($this->niveau()-1)*5+1;
	}

	public function isLearnable(Joueur $joueur){
		if($joueur->tuto() != "fini" && !in_array($this->num(), [1,11,15,25]) ){
			return "unfinishedTuto";
		} elseif($this->element1() == "feu" && $joueur->pyrs_feu() < $this->cout()){
			return "notEnoughPyrs";
		} elseif($this->element1() == "eau" && $joueur->pyrs_eau() < $this->cout()){
			return "notEnoughPyrs";
		} elseif($this->element1() == "vent" && $joueur->pyrs_vent() < $this->cout()){
			return "notEnoughPyrs";
		} elseif($this->element1() == "terre" && $joueur->pyrs_terre() < $this->cout()){
			return "notEnoughPyrs";
		} elseif($joueur->niveau() < $this->niveau_requis()){
			return "lowLevel";
		} else {
			return "ok";
		}
	}

	public function limitingFactor (Joueur $joueur){
		if($this->isLearnable($joueur) == "unfinishedTuto"){
			return "Plus tard...";
		} elseif($this->isLearnable($joueur) == "lowLevel"){
			return "Niv. ".$this->niveau_requis()." requis";
		} elseif($this->isLearnable($joueur) == "notEnoughPyrs"){
			return $this->cout().' <img class="mg4 img_15" src="/webroot/img/icones/pyrs_'.$this->element1().'.png"/> requises';
		} elseif($this->isLearnable($joueur) == "ok"){
			return '<img class="l100" src="/webroot/img/icones/fleche3.png"/>';
		}
	}

	public function couleur()
	{
		if($this->element1() == "feu")
			{return "rouge";}
		elseif($this->element1() == "eau")
			{return "bleu";}
		elseif($this->element1() == "vent")
			{return "jaune";}
		elseif($this->element1() == "terre")
			{return "vert";}
	}

	public function couleur_hex()
	{
		$coul = $this->couleur();
		if($coul == "rouge")
			{return "#FF0000";}
		elseif($coul == "bleu")
			{return "#0080FF";}
		elseif($coul == "jaune")
			{return "#B7B700";}
		elseif($coul == "vert")
			{return "#00A452";}
	}

	public function descriptif_categorie()
	{
		$caracs = $this->carac_sort();
		$categorie = $caracs["categorie"];
		$descriptif = "";
		if(strpos($categorie, "offensif") !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/offensif.png"></span><span class="ib l80">Ce sort est offensif.</span>';
		}
		if(strpos($categorie, "defensif")  !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/defensif.png"></span><span class="ib l80">Ce sort est défensif.</span>';
		}
		if(strpos($categorie, "tour")  !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/tour.png"></span><span class="ib l80">La puissance de ce sort évolue au fur et à mesure des tours du combat.</span>';
		}
		if(strpos($categorie, "reussite")  !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/reussite.png"></span><span class="ib l80">Ce sort gagne en puissance s\'il est invoqué avec succès plusieurs fois de suite.</span>';
		}
		if(strpos($categorie, "proportionnel")  !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/proportionnel.png"></span><span class="ib l80">La puissance de ce sort est proportionnelle à un élément.</span>';
		}
		if(strpos($categorie, "condition")  !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/condition.png"></span><span class="ib l80">Ce sort ne peut être invoqué que si une condition est vérifiée.</span>';
		}
		if(strpos($categorie, "probabilite")  !== false)
		{
			$descriptif .= '<span class="ib l10 md4"><img class="l100" src="/webroot/img/sorts/probabilite.png"></span><span class="ib l80">Même en cas de bonne réponse, ce sort n\'a qu\'une certaine probabilité d\'être invoqué.</span>';
		}
		return $descriptif;
	}



}
