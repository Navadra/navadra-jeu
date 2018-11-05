<?php

use \Mailjet\Resources;

class Joueur implements JsonSerializable
{
	protected $id,
			  $nom,
			  $prenom,
			  $pseudo,
			  $mdp,
			  $email,
			  $email_parent,
			  $sexe,
			  $classe,
			  $avatar_tete,
			  $avatar_entier,
			  $departement,
			  $college,
			  $niveau,
			  $xp,
			  $pyrs_feu,
			  $pyrs_eau,
			  $pyrs_vent,
			  $pyrs_terre,
			  $pyrs_feu_dep,
			  $pyrs_eau_dep,
			  $pyrs_vent_dep,
			  $pyrs_terre_dep,
			  $tuteur,
			  $img_tuteur,
			  $prestige,
			  $dernier_log,
			  $position,
			  $contacts,
			  $histoires_vues,
			  $date_inscription,
			  $reinitialisation_sorts,
			  $nb_combats,
			  $tuto,
			  $date_fin_tuto,
			  $stock_challenges,
			  $bulles_daide_actives,
			  $bulles_daide_vues,
			  $connecte,
			  $advanced_description,
			  $music_settings,
			  $abonnement_ok,
			  $suivi_link,
				$email_confirme;

	protected $current_classement;

	const EMAIL_PARENT_OK = 1;
	const COMPTE_BLOQUE = 2;

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

	public function prenom()
	{
		return $this->prenom;
	}

	public function pseudo()
	{
		return $this->pseudo;
	}

	public function mdp()
	{
		return $this->mdp;
	}

	public function email()
	{
		return $this->email;
	}

	public function email_parent()
	{
		return $this->email_parent;
	}

	public function sexe()
	{
		return $this->sexe;
	}

	public function classe()
	{
		return $this->classe;
	}

	public function avatar_tete()
	{
		return $this->avatar_tete;
	}

	public function avatar_entier()
	{
		return $this->avatar_entier;
	}

	public function departement()
	{
		return $this->departement;
	}

	public function college()
	{
		return $this->college;
	}

	public function niveau()
	{
		return $this->niveau;
	}

	public function xp()
	{
		return $this->xp;
	}

	public function pyrs_feu()
	{
		return $this->pyrs_feu;
	}

	public function pyrs_eau()
	{
		return $this->pyrs_eau;
	}

	public function pyrs_vent()
	{
		return $this->pyrs_vent;
	}

	public function pyrs_terre()
	{
		return $this->pyrs_terre;
	}

	public function pyrs_feu_dep()
	{
		return $this->pyrs_feu_dep;
	}

	public function pyrs_eau_dep()
	{
		return $this->pyrs_eau_dep;
	}

	public function pyrs_vent_dep()
	{
		return $this->pyrs_vent_dep;
	}

	public function pyrs_terre_dep()
	{
		return $this->pyrs_terre_dep;
	}

	public function tuteur()
	{
		return $this->tuteur;
	}

	public function img_tuteur()
	{
		return $this->img_tuteur;
	}

	public function prestige()
	{
		return $this->prestige;
	}

	public function dernier_log()
	{
		return $this->dernier_log;
	}

	public function position()
	{
		return $this->position;
	}

	public function contacts()
	{
		return $this->contacts;
	}

	public function histoires_vues()
	{
		return $this->histoires_vues;
	}

	public function date_inscription()
	{
		return $this->date_inscription;
	}

	public function reinitialisation_sorts()
	{
		return $this->reinitialisation_sorts;
	}

	public function nb_combats()
	{
		return $this->nb_combats;
	}

	public function tuto()
	{
		return $this->tuto;
	}

	public function date_fin_tuto()
	{
		return $this->date_fin_tuto;
	}

	public function stock_challenges()
	{
		return $this->stock_challenges;
	}

	public function bulles_daide_actives()
	{
		return $this->bulles_daide_actives;
	}

	public function bulles_daide_vues()
	{
		return $this->bulles_daide_vues;
	}

	public function connecte()
	{
		return $this->connecte;
	}

	public function advanced_description()
	{
		return $this->advanced_description;
	}

	public function music_settings()
	{
		return $this->music_settings;
	}

	public function abonnement_ok()
	{
		return $this->abonnement_ok;
	}

	public function suivi_link()
	{
		return $this->suivi_link;
	}

	public function email_confirme()
	{
		return $this->email_confirme;
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

	public function setNom($nom)
	{
		$this->nom = $nom;
	}

	public function setPrenom($prenom)
	{
		$this->prenom = $prenom;
	}

	public function setPseudo($pseudo)
	{
		$this->pseudo = $pseudo;
	}

	public function setMdp($mdp)
	{
		$this->mdp = $mdp;
	}

	public function setEmail($email)
	{
		$email = strtolower($email);
		$this->email = $email;
	}

	public function setEmail_parent($email_parent)
	{
		$email_parent = strtolower($email_parent);
		$this->email_parent = $email_parent;
	}

	public function setSexe($sexe)
	{
		$this->sexe = $sexe;
	}

	public function setClasse($classe)
	{
		$this->classe = $classe;
	}

	public function setAvatar_tete($avatar_tete)
	{
		$this->avatar_tete = $avatar_tete;
	}

	public function setAvatar_entier($avatar_entier)
	{
		$this->avatar_entier = $avatar_entier;
	}

	public function setDepartement($departement)
	{
		$this->departement = $departement;
	}

	public function setCollege($college)
	{
		$this->college = $college;
	}

	public function setNiveau($niveau)
	{
		$niveau = (int) $niveau;
		if ($niveau >=1 && $niveau <= 50)
		{
			$this->niveau = $niveau;
		}
	}

	public function setXp($xp)
	{
		$xp = (int) $xp;
		if ($xp >=0)
		{
			$this->xp = $xp;
		}
	}

	public function setPyrs_feu($pyrs_feu)
	{
		$pyrs_feu = (int) $pyrs_feu;
		if ($pyrs_feu >=0)
		{
			$this->pyrs_feu = $pyrs_feu;
		}
	}

	public function setPyrs_eau($pyrs_eau)
	{
		$pyrs_eau = (int) $pyrs_eau;
		if ($pyrs_eau >=0)
		{
			$this->pyrs_eau = $pyrs_eau;
		}
	}

	public function setPyrs_vent($pyrs_vent)
	{
		$pyrs_vent = (int) $pyrs_vent;
		if ($pyrs_vent >=0)
		{
			$this->pyrs_vent = $pyrs_vent;
		}
	}

	public function setPyrs_terre($pyrs_terre)
	{
		$pyrs_terre = (int) $pyrs_terre;
		if ($pyrs_terre >=0)
		{
			$this->pyrs_terre = $pyrs_terre;
		}
	}

	public function setPyrs_feu_dep($pyrs_feu_dep)
	{
		$pyrs_feu_dep = (int) $pyrs_feu_dep;
		if ($pyrs_feu_dep >=0)
		{
			$this->pyrs_feu_dep = $pyrs_feu_dep;
		}
	}

	public function setPyrs_eau_dep($pyrs_eau_dep)
	{
		$pyrs_eau_dep = (int) $pyrs_eau_dep;
		if ($pyrs_eau_dep >=0)
		{
			$this->pyrs_eau_dep = $pyrs_eau_dep;
		}
	}

	public function setPyrs_vent_dep($pyrs_vent_dep)
	{
		$pyrs_vent_dep = (int) $pyrs_vent_dep;
		if ($pyrs_vent_dep >=0)
		{
			$this->pyrs_vent_dep = $pyrs_vent_dep;
		}
	}

	public function setPyrs_terre_dep($pyrs_terre_dep)
	{
		$pyrs_terre_dep = (int) $pyrs_terre_dep;
		if ($pyrs_terre_dep >=0)
		{
			$this->pyrs_terre_dep = $pyrs_terre_dep;
		}
	}

	public function setTuteur($tuteur)
	{
		$tuteur = (string) $tuteur;
		$tuteur = ucfirst($tuteur);
		if ($tuteur=="Katillys" || $tuteur=="Namuka" || $tuteur=="Leorn" || $tuteur=="Sivem")
		{
			$this->tuteur = $tuteur;
		}
	}

	public function setImg_tuteur($img_tuteur)
	{
		$this->img_tuteur = $img_tuteur;
	}

	public function setPrestige($prestige)
	{
		$prestige = (int) $prestige;
		if ($prestige >=0)
		{
			$this->prestige = $prestige;
		}
		else
		{
			$this->prestige = 0;
		}
	}

	public function setDernier_log($dernier_log)
	{
		$this->dernier_log = $dernier_log;
	}

	public function setPosition($position)
	{
		$this->position = $position;
	}

	public function setContacts($contacts)
	{
		$this->contacts = $contacts;
	}

	public function setHistoires_vues($histoires_vues)
	{
		$this->histoires_vues = $histoires_vues;
	}

	public function setDate_inscription($date_inscription)
	{
		$this->date_inscription = $date_inscription;
	}

	public function setReinitialisation_sorts($reinitialisation_sorts)
	{
		$this->reinitialisation_sorts = $reinitialisation_sorts;
	}

	public function setNb_combats($nb_combats)
	{
		$this->nb_combats = $nb_combats;
	}

	public function setTuto($tuto)
	{
		$this->tuto = $tuto;
	}

	public function setDate_fin_tuto($date_fin_tuto)
	{
		$this->date_fin_tuto = $date_fin_tuto;
	}

	public function setStock_challenges($stock_challenges)
	{
		$stock_challenges = (int) $stock_challenges;
		if ($stock_challenges >=0)
		{
			$this->stock_challenges = $stock_challenges;
		}
	}

	public function setBulles_daide_actives($bulles_daide_actives)
	{
		$this->bulles_daide_actives = $bulles_daide_actives;
	}

	public function setBulles_daide_vues($bulles_daide_vues)
	{
		$this->bulles_daide_vues = $bulles_daide_vues;
	}

	public function setConnecte($connecte)
	{
		$this->connecte = $connecte;
	}

	public function setAdvanced_description($advanced_description)
	{
		$advanced_description = (int) $advanced_description;
		if ($advanced_description >=0)
		{
			$this->advanced_description = $advanced_description;
		}
	}

	public function setMusic_settings($music_settings)
	{
		$this->music_settings = $music_settings;
	}

	public function setAbonnement_ok($abonnement_ok)
	{
		$this->abonnement_ok = $abonnement_ok;
	}

	public function setSuivi_link($suivi_link)
	{
		$this->suivi_link = $suivi_link;
	}

	public function setEmail_confirme($email_confirme)
	{
		$this->email_confirme = $email_confirme;
	}

	public function setCurrent_classement($current_classement){
		$this->current_classement = $current_classement;
	}
	public function getCurrent_classement(){
		return $this->current_classement;
	}

	//Fonctions d'exportation en Json
	public function jsonSerialize()
    {
        $result = get_object_vars($this);
		return json_encode($result);
    }

	//Fonctions de validation
	public function nomValide($nom)
	{
		if(preg_match("#^[a-zàéêèïîëçù'A-Z -]{3,30}$#", $nom))
		{return true;}
		else
		{return false;}
	}

	public function pseudoValide($pseudo)
	{
		if(preg_match("#^[a-zàéèïîëçù'A-Z0-9 -@_]{3,15}$#", $pseudo))
		{return true;}
		else
		{return false;}
	}

	public function mdpValide($mdp)
	{
		if(preg_match("#^[a-zàéèïîëçùA-Z0-9.!?_-]{6,30}$#", $mdp))
		{return true;}
		else
		{return false;}
	}

	public function mdpIdentiques($mdp1, $mdp2)
	{
		if($mdp1 != $mdp2 && $mdp1 != "" && $mdp2 != "")
		{return false;}
		else
		{return true;}
	}

	public function emailsDifferents($email1, $email2)
	{
		if($email1 == $email2 && $email1 != "" && $email2 != "")
		{return false;}
		else
		{return true;}
	}

	public function departementValide($CollegeList, $departement)	{
		foreach($CollegeList as $col){
			if($col["departement"] == $departement){
				return true;
			}
		}
		return false;
	}

	public function collegeValide($CollegeList, $departement, $college)	{
		foreach($CollegeList as $col){
			if($col["departement"] == $departement && $col["nom"] == $college){
				return true;
			}
		}
		return false;
	}

	public function emailValide($email)	{
		if(preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_-]{2,}\.[a-zA-Z]{2,4}$#", $email)) {
			return true;
		}	else {
			return false;
		}
	}

	public function domaineValide($email)	{
		$email = strtolower($email);
		$domainNames = array("free.fr", "wanadoo.fr", "orange.fr", "gmail.com", "hotmail.com", "bbox.fr", "aol.fr", "hotmail.fr", "laposte.net", "yahoo.fr", "icloud.com", "outlook.fr", "sfr.fr", "outlook.com", "gmx.fr", "orange.com", "numericable.com", "live.fr", "yahoo.com", "laposte.fr", "voila.fr", "hotmail.be", "aliceadsl.fr", "neuf.fr", "gmx.com", "bouyguestelecom.fr", "enteduc.fr", "numericable.fr");
		$domainEmail = preg_match("#^[a-zA-Z0-9._-]{1,}@([a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4})$#", $email, $m);
		$domainEmail = $m[1];
		if(in_array($domainEmail, $domainNames)) {
			return true;
		}	else {
			return false;
		}
	}

	public function feminine($type) {
		if($type == "e") {
			if($this->sexe() == "fille"){
				return "e";
			} else {
				return "";
			}
		} elseif($type == "3rd") {
			if($this->sexe() == "fille"){
				return "elle";
			} else {
				return "il";
			}
		} elseif($type == "3rdBis") {
			if($this->sexe() == "fille"){
				return "elle";
			} else {
				return "lui";
			}
		}
	}

	public function encrypt($data) {
	    $key = "secret";
	    $data = serialize($data);
	    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
	    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	    mcrypt_generic_init($td,$key,$iv);
	    $data = base64_encode(mcrypt_generic($td, '!'.$data));
	    mcrypt_generic_deinit($td);
	    return $data;
	}

	public function decrypt($data) {
	    $key = "secret";
	    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
	    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	    mcrypt_generic_init($td,$key,$iv);
	    $data = mdecrypt_generic($td, base64_decode($data));
	    mcrypt_generic_deinit($td);

	    if (substr($data,0,1) != '!')
	        return false;

	    $data = substr($data,1,strlen($data)-1);
	    return unserialize($data);
	}


	//Fonctions envoi d'email
	public function send_email($template, $from, $subject, $to, $params) {
		/*
    $server_add = "";
    $server = "";

		if( isset($_SERVER['SERVER_ADDR'])) $server_add = $_SERVER['SERVER_ADDR'];
    if( isset($_SERVER['SERVER_NAME'])) $server = $_SERVER['SERVER_NAME'];

		//if($server != "localhost" && $server_add != "localhost" && $server != "127.0.0.1" && $server_add != "127.0.0.1" && $to != ""){
		if($to != ""){
			if($server != "localhost" && $server_add != "localhost" && $server != "127.0.0.1" && $server_add != "127.0.0.1" && $to != ""){
	    	$log_out = "SEND EMAIL: TO " . $to . " : " . $subject . "\r\n";
	    	file_put_contents('/var/www/LOGS_DAILY_EXECUTED_'.date("Y-m").'.log', $log_out, FILE_APPEND);
			}

			// This calls sends an email to one recipient.
			require_once ('autoload.php');
			//$mj = new \Mailjet\Client(getenv('MJ_APIKEY_PUBLIC'), getenv('MJ_APIKEY_PRIVATE'));
			$mj = new \Mailjet\Client('1fe4b30f069c66c071b06c535c8f72aa', '78a5b20e1a1d173373ff6d74ddb54328');
			$FromName = $from;
			if($FromName == "Jérémie"){
				$FromEmail = "jeremie@navadra.com";
			} elseif($FromName == "Michel"){
				$FromEmail = "michel@navadra.com";
			} elseif($FromName == "Julien"){
				$FromEmail = "julien@navadra.com";
			} elseif($FromName == "Namuka"){
				$FromEmail = "namuka@navadra.com";
			} elseif($FromName == "Katillys"){
				$FromEmail = "katillys@navadra.com";
			} elseif($FromName == "Sivem"){
				$FromEmail = "sivem@navadra.com";
			} elseif($FromName == "Leorn"){
				$FromEmail = "leorn@navadra.com";
			} elseif($FromName == "Team"){
				$FromEmail = "team@navadra.com";
			} elseif($FromName == "Player"){
				if($this->emailValide($this->email())){
					$FromName = $this->firstname();
					$FromEmail = $this->email();
				} else {
					$FromName = "Navadra";
					$FromEmail = "robot@navadra.com";
				}
			} else {
				$FromEmail = "espritdenavadra@navadra.com";
			}
			$body = array(
				'FromEmail' => $FromEmail,
				'FromName' => $FromName,
				'Subject' => $subject,
				'MJ-TemplateID' => $template,
				'MJ-TemplateLanguage' => true,
				'Recipients' => array(array('Email' => $to)),
				'Vars' => json_decode($params, true)
		  );

			$response = $mj->post(Resources::$Email, array('body' => $body));
			//$response->success() && var_dump($response->getData());
		}
		*/
	}

	//Fonctions utiles pour le côté "jeu"
	public function admin(){
		if($this->classe() == "Prof"){
			return true;
		} else {
			return false;
		}
	}

	public function xp_requise()
	{
		return round(84*pow(1.0619, $this->niveau()-1));
	}

	public function xp_requise2($lvl)
	{
		if($lvl==1)
		{
			return 0;
		}
		else
		{
			return round(84*pow(1.0619, $lvl-2));
		}
	}

	public function gagner_xp($xp)
	{
		if($this->niveau() == 50 && ($this->xp() + $xp) >= $this->xp_requise())
		{
			$this->setXp($this->xp_requise());
		}
		elseif( ($this->xp() + $xp) < $this->xp_requise() ) //Si l'ajout d'XP n'est pas suffisante pour changer de niveau
		{
			$this->setXp($this->xp() + $xp);
		}
		else //Sinon on fait gagner un niveau au joueur tant qu'il a de l'xp en stock
		{
			while (  ($this->xp() + $xp) >= $this->xp_requise() )
			{
				$xp = $xp - $this->xp_requise() + $this->xp(); //On diminue la quantité d'XP à attribuer
				$this->setNiveau($this->niveau() + 1); //On fait gagner un niveau au joueur
				$this->setXp(0); //On remet l'XP du joueur à 0;
			}
			$this->setXp($this->xp() + $xp); //On attribue la valeur d'XP résiduelle au joueur
		}
	}

	public function xp_tot_gagnee($lvl)
	{
		$xp_tot = 0;
		for($i=1;$i<=$lvl;$i++)
		{
			$xp_tot += $this->xp_requise2($i);
		}
		return round($xp_tot);
	}

	public function pyrs_tot_gagnees($lvl)
	{
		return round($this->xp_tot_gagnee($lvl)/5);
	}

	public function addPyrs($pyrs, $element) //Add pyrs to player depending on the challenge element
	{
		$pyrsWon = array();
		$pyrsWon["fire"] = $pyrsWon["water"] = $pyrsWon["wind"] = $pyrsWon["earth"] = 0;
		switch($element)
		{
			case "fire" :
				$this->setPyrs_feu($this->pyrs_feu() + $pyrs);
				$pyrsWon["fire"] += $pyrs;
				break;
			case "water" :
				$this->setPyrs_eau($this->pyrs_eau() + $pyrs);
				$pyrsWon["water"] += $pyrs;
				break;
			case "wind" :
				$this->setPyrs_vent($this->pyrs_vent() + $pyrs);
				$pyrsWon["wind"] += $pyrs;
				break;
			case "earth" :
				$this->setPyrs_terre($this->pyrs_terre() + $pyrs);
				$pyrsWon["earth"] += $pyrs;
				break;
		}
		return $pyrsWon;
	}

	public function displayPyrs($pyrs, $element) //Display pyrs to player depending on the challenge element
	{
		$pyrsWon = array();
		$pyrsWon["fire"] = $pyrsWon["water"] = $pyrsWon["wind"] = $pyrsWon["earth"] = 0;
		switch($element)
		{
			case "fire" :
				$pyrsWon["fire"] += $pyrs;
				break;
			case "water" :
				$pyrsWon["water"] += $pyrs;
				break;
			case "wind" :
				$pyrsWon["wind"] += $pyrs;
				break;
			case "earth" :
				$pyrsWon["earth"] += $pyrs;
				break;
		}
		return $pyrsWon;
	}

	public function profil_elem()
	{
		$profil = array();
		$total_pyrs_depensees = $this->pyrs_feu_dep() + $this->pyrs_eau_dep() + $this->pyrs_vent_dep() + $this->pyrs_terre_dep();
		if($total_pyrs_depensees == 4) //Si le joueur n'a encore dépensé aucune Pyrs
		{
			$profil["feu"] = round($this->pyrs_feu_dep() / $total_pyrs_depensees * 100);
			$profil["eau"] = round($this->pyrs_eau_dep() / $total_pyrs_depensees * 100);
			$profil["vent"] = round($this->pyrs_vent_dep() / $total_pyrs_depensees * 100);
			$profil["terre"] = round($this->pyrs_terre_dep() / $total_pyrs_depensees * 100);
			$diff = 100 - $profil["feu"] - $profil["eau"] - $profil["vent"] - $profil["terre"]; //Permet de s'assurer que la somme fait 100
			$max = max($profil);
			$elmt = array_search($max, $profil);
			$profil[$elmt] = $profil[$elmt] + $diff;
		}
		else
		{
			$feu = $this->pyrs_feu_dep() - 1;
			$eau = $this->pyrs_eau_dep() - 1;
			$vent = $this->pyrs_vent_dep() - 1;
			$terre = $this->pyrs_terre_dep() - 1;
			$total_pyrs_depensees -= 4;
			$profil["feu"] = round($feu / $total_pyrs_depensees * 100);
			$profil["eau"] = round($eau / $total_pyrs_depensees * 100);
			$profil["vent"] = round($vent / $total_pyrs_depensees * 100);
			$profil["terre"] = round($terre / $total_pyrs_depensees * 100);
			$diff = 100 - $profil["feu"] - $profil["eau"] - $profil["vent"] - $profil["terre"]; //Permet de s'assurer que la somme fait 100
			$max = max($profil);
			$elmt = array_search($max, $profil);
			$profil[$elmt] = $profil[$elmt] + $diff;
		}
		return $profil;
	}

	public function profil_elem_classe()
	{
		$profil = $this->profil_elem();
		$elmt = $this->element();
		$profil_tri = array($elmt => $profil[$elmt]); //On place l'élément principal du joueur en premier
		$profil[$elmt] = -1;
		for($i=1;$i<=3;$i++)
		{
			$max = max($profil);
			$elmt = array_search($max, $profil);
			$profil_tri[$elmt] = $profil[$elmt];
			$profil[$elmt] = -1;

		}
		return $profil_tri;
	}

	public function profil_elem_decompose()
	{
		$profil_elem = $this->profil_elem_classe();
		$elems = array_keys($profil_elem);
		return $elems[0].",".$profil_elem[$elems[0]].",".$elems[1].",".$profil_elem[$elems[1]].",".$elems[2].",".$profil_elem[$elems[2]].",".$elems[3].",".$profil_elem[$elems[3]];
	}

	public function element()
	{
		$profil = $this->profil_elem();
		$maxs = array_keys($profil, max($profil));
		if(sizeof($maxs)>1) //S'il y a plusieurs maximums à égalité dont l'élément de l'ancien tuteur du joueur
		{
			if($this->tuteur() == "Namuka" && max($profil) == $profil["feu"])
				{return "feu";}
			elseif($this->tuteur() == "Katillys" && max($profil) == $profil["eau"])
				{return "eau";}
			elseif($this->tuteur() == "Sivem" && max($profil) == $profil["vent"])
				{return "vent";}
			elseif($this->tuteur() == "Leorn" && max($profil) == $profil["terre"])
				{return "terre";}
		}
		//Sinon on prend le premier élément qui est égal au max
		if(max($profil) == $profil["feu"])
			{return "feu";}
		elseif(max($profil) == $profil["eau"])
			{return "eau";}
		elseif(max($profil) == $profil["vent"])
			{return "vent";}
		elseif(max($profil) == $profil["terre"])
			{return "terre";}
	}

	public function element_force()
	{
		switch($this->element())
		{
			case "feu":
				return "terre";
				break;
			case "eau":
				return "feu";
				break;
			case "vent":
				return "eau";
				break;
			case "terre":
				return "vent";
				break;
		}
	}

	public function element_faiblesse()
	{
		switch($this->element())
		{
			case "feu":
				return "eau";
				break;
			case "eau":
				return "vent";
				break;
			case "vent":
				return "terre";
				break;
			case "terre":
				return "feu";
				break;
		}
	}

	public function element_neutre()
	{
		switch($this->element())
		{
			case "feu":
				return "vent";
				break;
			case "eau":
				return "terre";
				break;
			case "vent":
				return "feu";
				break;
			case "terre":
				return "eau";
				break;
		}
	}

	public function element_article()
	{
		switch($this->element())
		{
			case "feu":
				return "du Feu";
				break;
			case "eau":
				return "de l'Eau";
				break;
			case "vent":
				return "du Vent";
				break;
			case "terre":
				return "de la Terre";
				break;
		}
	}

	public function element_article2()
	{
		switch($this->element())
		{
			case "feu":
				return "de Feu";
				break;
			case "eau":
				return "d'Eau";
				break;
			case "vent":
				return "de Vent";
				break;
			case "terre":
				return "de Terre";
				break;
		}
	}

	public function generer_nouvelles_pyrs($elmt1, $elmt2) //Lors de la réinitialisation des pyrs dépensées
	{
		$total_pyrs_dep = $this->pyrs_feu_dep() + $this->pyrs_eau_dep() + $this->pyrs_vent_dep() + $this->pyrs_terre_dep() - 4;
		$total_pyrs_dep += $this->pyrs_feu() + $this->pyrs_eau() + $this->pyrs_vent() + $this->pyrs_terre(); //On additionne les pyrs restantes et on les remets à 0
		$this->setPyrs_feu(0);
		$this->setPyrs_eau(0);
		$this->setPyrs_vent(0);
		$this->setPyrs_terre(0);
		switch ($elmt1)
		{
			case "feu" :
				$this->setPyrs_feu($this->pyrs_feu() + round(0.7*$total_pyrs_dep));
				break;
			case "eau" :
				$this->setPyrs_eau($this->pyrs_eau() + round(0.7*$total_pyrs_dep));
				break;
			case "vent" :
				$this->setPyrs_vent($this->pyrs_vent() + round(0.7*$total_pyrs_dep));
				break;
			case "terre" :
				$this->setPyrs_terre($this->pyrs_terre() + round(0.7*$total_pyrs_dep));
				break;
		}
		switch ($elmt2)
		{
			case "feu" :
				$this->setPyrs_feu($this->pyrs_feu() + round(0.3*$total_pyrs_dep));
				break;
			case "eau" :
				$this->setPyrs_eau($this->pyrs_eau() + round(0.3*$total_pyrs_dep));
				break;
			case "vent" :
				$this->setPyrs_vent($this->pyrs_vent() + round(0.3*$total_pyrs_dep));
				break;
			case "terre" :
				$this->setPyrs_terre($this->pyrs_terre() + round(0.3*$total_pyrs_dep));
				break;
		}
	}

	public function changement_element($ancienne_valeur, $nouvelle_valeur)
	{
		if($this->pyrs_feu_dep() == 1 && $this->pyrs_eau_dep() == 1 && $this->pyrs_vent_dep() == 1 && $this->pyrs_terre_dep() == 1) //Cas particulier du joueur qui vient de réinitialiser ses sorts
		{
			if($this->tuteur() == "Namuka")
				{$ancienne_valeur = "feu";}
			elseif($this->tuteur() == "Katillys")
				{$ancienne_valeur = "eau";}
			elseif($this->tuteur() == "Sivem")
				{$ancienne_valeur = "vent";}
			elseif($this->tuteur() == "Leorn")
				{$ancienne_valeur = "terre";}
		}
		if($nouvelle_valeur == $ancienne_valeur)
		{
			return false;
		}
		else
		{
			if($nouvelle_valeur == "feu")
				{$tuteur = "Namuka";}
			elseif($nouvelle_valeur == "eau")
				{$tuteur = "Katillys";}
			elseif($nouvelle_valeur == "vent")
				{$tuteur = "Sivem";}
			elseif($nouvelle_valeur == "terre")
				{$tuteur = "Leorn";}
			$this->setTuteur($tuteur);
			$this->setImg_tuteur("/webroot/img/personnages/".strtolower($tuteur).".png");
			return true;
		}
	}

	public function cout_reinitalisation(){
		$cout = min(5 * (1 + $this->reinitialisation_sorts()), 30);
		$profil = $this->profil_elem();
		$retour = array();
		$retour[] = round($cout * $profil["feu"]/100);
		$retour[] = round($cout * $profil["eau"]/100);
		$retour[] = round($cout * $profil["vent"]/100);
		$retour[] = round($cout * $profil["terre"]/100);
		return implode(",", $retour);
	}

	public function pm()
	{
		$lvl = 1;
		$pm = 7;
		while($lvl < $this->niveau()){
			$lvl++;
			$pm = ceil($pm + $lvl*1.12);
		}
		return $pm;
	}

	public function endu()
	{
		$lvl = 1;
		$endu = 10;
		while($lvl < $this->niveau()){
			$lvl++;
			$endu= ceil($endu + $lvl*3.75);
		}
		return $endu;
	}

	public function descriptif_pm(Joueur $joueur_actif)
	{
		if($joueur_actif->id() == $this->id())
		{
			$descriptif = "<span class='g ib l100 mb2'>Puissance Magique : ".$this->pm()."</span><span class='ib l100'>- Détermine la puissance de tes sorts</span><span class='ib l100'>- Augmente avec ton niveau</span>";
		}
		else
		{
			$descriptif = "<span class='g ib l100 mb2'>Puissance Magique : ".$this->pm()."</span><span class='ib l100'>- Détermine la puissance des sorts de ".$this->pseudo()."</span><span class='ib l100'>- Augmente avec son niveau</span>";
		}
		return $descriptif;
	}

	public function descriptif_endu(Joueur $joueur_actif)
	{
		if($joueur_actif->id() == $this->id())
		{
			$descriptif = "<span class='g ib l100 mb2'>Points de vie : ".$this->endu()."</span><span class='ib l100'>Ce sont les dégâts que tu peux encaisser avant d'être K.O.</span>";
		}
		else
		{
			$descriptif = "<span class='g ib l100 mb2'>Points de vie : ".$this->endu()."</span><span class='ib l100'>Ce sont les dégâts que ".$this->pseudo()." peut encaisser avant d'être K.O.</span>";
		}
		return $descriptif;
	}

	public function nb_sorts_debloques()
	{
		return 40;
	}

	public function niv_max_sort()
	{
		return min(floor(($this->niveau()-1)/5)+1,10);
	}

	public function next_level_newSpells()
	{
		$fictive_player = new Joueur(array("niveau" => $this->niveau())); //On créé un joueur fictif
		$level = $this->niveau();
		while($fictive_player->niv_max_sort() == $this->niv_max_sort())
		{
			$fictive_player->setNiveau($fictive_player->niveau() + 1);
		}
		return $fictive_player->niveau();
	}

	public function powerSpells()
	{
		$countSpells = 3;
		if($this->niveau==1)
		{
			$countSpells = 2;
		}
		$powerSpells = $countSpells * $this->pm();
		return round($powerSpells);
	}

	public function facteur_elem_atq($elem) //Détermine le facteur a appliquer aux dégâts du joueur quand il attaque un monstre d'un élément donné
	{
		$profil = $this->profil_elem();
		$bonus = 1.15;
		$malus = 0.85;
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

	public function facteur_elem_def($elem) //Détermine le facteur a appliquer aux dégâts du monstre quand un monstre d'un élément donné l'attaque
	{
		$profil = $this->profil_elem();
		$bonus = 0.85;
		$malus = 1.15;
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

	public function histoires_debloques() //Détermine les histoires débloquées par le joueur
	{
		$histoires_vues = $this->histoires_vues();
		$histoires = array($histoires_vues[0]); //Le joueur a forcément débloqué la cinématique d'introduction
		if($this->niveau() >= 4) //La première histoire est débloquée au niveau 4
		{
			$histoires[] = "1_".$this->element();
		}
		if($this->niveau() >= 8)
		{
			$histoires[] = "2_".$this->element();
		}
		if($this->niveau() >= 12)
		{
			$histoires[] = "3_".$this->element();
		}
		if($this->niveau() >= 16)
		{
			$histoires[] = "4_".$this->element();
		}
		if($this->niveau() >= 20)
		{
			$histoires[] = "5_".$this->element();
		}
		if($this->niveau() >= 25)
		{
			$histoires[] = "6";
		}
		if($this->niveau() >= 30)
		{
			$histoires[] = "7";
		}
		if($this->niveau() >= 35)
		{
			$histoires[] = "8";
		}
		if($this->niveau() >= 40)
		{
			$histoires[] = "9";
		}
		if($this->niveau() >= 45)
		{
			$histoires[] = "10";
		}
		return $histoires;
	}

	public function reinitialiser_histoires($ancien_element) //Réinitialise les histoires suite à un changement d'élément
	{
		$histoires_vues = $this->histoires_vues();
		if($histoires_vues) //S'il a au moins une histoire vue
		{
			for($i=1;$i<=5;$i++) //On efface les histoires liées à son ancien élément dans l'array "histoires_vues" si elles existent mais pas l'introduction
			{
				if(in_array($i."_".$ancien_element, $histoires_vues))
				{
					array_splice( $histoires_vues, array_search($i."_".$ancien_element, $histoires_vues), 1 );
				}
			}
		}
		$this->setHistoires_vues($histoires_vues);
	}

	public function recompense_saison($joueurs_classement, $classement_joueur) {
		$nb_joueurs = sizeof($joueurs_classement);
		switch($this->sexe) {
			case "gars" :
				$e = "";
				$chass = "chasseur";
				break;
			case "fille" :
				$e = "e";
				$chass = "chasseuse";
				break;
		}
		$percentile = 100*$classement_joueur/$nb_joueurs;
		if($percentile <= 1)
			{$percentile = "Tu fais partie du top 1% des meilleurs joueurs de Navadra, FELICITATIONS ! Ton entraînement t'a rendu LEGENDAIRE... tout simplement !";
			$recompense = 200;}
		elseif($percentile <= 3)
			{$percentile = "Tu fais partie du top 3% des meilleurs joueurs de Navadra, FELICITATIONS ! Ton entraînement t'a rendu LEGENDAIRE... tout simplement !";
			$recompense = 150;}
		elseif($percentile <= 5)
			{$percentile = "Tu fais partie du top 5% des meilleurs joueurs de Navadra, FELICITATIONS ! Ton entraînement t'a rendu LEGENDAIRE... tout simplement !";;
			$recompense = 120;}
		elseif($percentile <= 10)
			{$percentile = "Tu fais partie des 10% des meilleurs joueurs de Navadra, BRAVO ! Ton entraînement a fait de toi un".$e." ".$chass." hors pair !";
			$recompense = 100;}
		elseif($percentile <= 15)
			{$percentile = "Tu fais partie des 15% des meilleurs joueurs de Navadra, BRAVO ! Ton entraînement a fait de toi un".$e." ".$chass." hors pair !";
			$recompense = 90;}
		elseif($percentile <= 20)
			{$percentile = "Tu fais partie des 20% des meilleurs joueurs de Navadra, BRAVO ! Ton entraînement a fait de toi un".$e." ".$chass." hors pair !";
			$recompense = 80;}
		elseif($percentile <= 30)
			{$percentile = "Tu fais partie des 30% des meilleurs joueurs de Navadra, BRAVO ! Ton entraînement a fait de toi un".$e." ".$chass." hors pair !";
			$recompense = 70;}
		elseif($percentile <= 40)
			{$percentile = "Tu fais partie des 40% des meilleurs joueurs de Navadra, BRAVO ! Ton entraînement a fait de toi un".$e." ".$chass." hors pair !";
			$recompense = 60;}
		elseif($percentile <= 50)
			{$percentile = "Tu fais partie des 50% des meilleurs joueurs de Navadra, BRAVO ! Ton entraînement a fait de toi un".$e." ".$chass." hors pair !";
			$recompense = 50;}
		elseif($percentile <= 60)
			{$percentile = "Tu fais partie des 60% des meilleurs joueurs de Navadra, bien joué ! Penses-tu pouvoir faire mieux la prochaine saison ?";
			$recompense = 40;}
		elseif($percentile <= 70)
			{$percentile = "Tu fais partie des 70% des meilleurs joueurs de Navadra, bien joué ! Penses-tu pouvoir faire mieux la prochaine saison ?";
			$recompense = 30;}
		elseif($percentile <= 80)
			{$percentile = "Tu fais partie des 80% des meilleurs joueurs de Navadra, bien joué ! Penses-tu pouvoir faire mieux la prochaine saison ?";
			$recompense = 20;}
		else
			{$percentile = "Tu ne fais pas partie des meilleurs joueurs de Navadra pour la saison passée mais une nouvelle commence : à toi de jouer !";;
			$recompense = 10;}
		$profile = $this->profil_elem();
		if($profile["feu"] == max($profile)){
			$recompense_pyrs = $this->addPyrs($recompense, "fire");
		} elseif($profile["eau"] == max($profile)){
			$recompense_pyrs = $this->addPyrs($recompense, "water");
		} elseif($profile["vent"] == max($profile)){
			$recompense_pyrs = $this->addPyrs($recompense, "wind");
		} elseif($profile["terre"] == max($profile)){
			$recompense_pyrs = $this->addPyrs($recompense, "earth");
		}
		if($recompense_pyrs["fire"] > 0){
			$msg_recompense = 'Récompense : +'.$recompense_pyrs["fire"].' <img class="img_20" src="/webroot/img/icones/pyrs_feu.png"/>';
		}	elseif($recompense_pyrs["water"] > 0)	{
			$msg_recompense = 'Récompense : +'.$recompense_pyrs["water"].' <img class="img_20" src="/webroot/img/icones/pyrs_eau.png"/>';
		}	elseif($recompense_pyrs["wind"] > 0){
			$msg_recompense = 'Récompense : +'.$recompense_pyrs["wind"].' <img class="img_20" src="/webroot/img/icones/pyrs_vent.png"/>';
		}	elseif($recompense_pyrs["earth"] > 0)	{
			$msg_recompense = 'Récompense : +'.$recompense_pyrs["earth"].' <img class="img_20" src="/webroot/img/icones/pyrs_terre.png"/>';
		}
		return array($percentile, $msg_recompense);
	}



	//Fonctions diverses (img, gestion des dates)
	public function portrait_tuteur()
	{
		$img = $this->img_tuteur();
		$motif = "#\.png$#";
		$remplacement = "_portrait.png";
		$img = preg_replace($motif, $remplacement, $img);
		return $img;
	}

	public function icone_sexe()
	{
		return "/webroot/img/icones/".$this->sexe().".png";
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

	public function portrait()
	{
		$portrait = preg_replace("#avatars/([\w_]+).png#", "avatars/portraits/tete_$1.png", $this->avatar_entier());
		return $portrait;
	}

	public function ornment_lvl() {
		if($this->niveau() >=40){
			$ornment_lvl = 5;
		} elseif($this->niveau() >=30){
			$ornment_lvl = 4;
		} elseif($this->niveau() >=20){
			$ornment_lvl = 3;
		} elseif($this->niveau() >=10){
			$ornment_lvl = 2;
		} elseif($this->niveau() >=5){
			$ornment_lvl = 1;
		} else {
			$ornment_lvl = 0;
		}
		return $ornment_lvl;
	}

	public function full_portrait()	{
		$ornment_lvl = $this->ornment_lvl();
		$model = preg_replace("#.+avatars/([\w_]+)\.png#", "$1", $this->avatar_entier());
		return "/webroot/img/players_img/".$this->id()."_".$this->element()."_".$model."_".$ornment_lvl.".png";
	}

	public function update_portrait(){
		// Loading images
		$background = imagecreatefrompng("../../webroot/img/cadres_avatars/cadres_ronds/".$this->element()."_fond.png");
		$portrait = preg_replace("#avatars/([\w_]+).png#", "avatars/portraits/tete_$1.png", $this->avatar_entier());
		$portrait = imagecreatefrompng("../..".$portrait);
		$ornment_lvl = $this->ornment_lvl();
		$ornment = imagecreatefrompng("../../webroot/img/cadres_avatars/cadres_ronds/".$this->element()."_niv_".$ornment_lvl.".png");

		//Determination of base size
		$base_width = imagesx($ornment);
		$base_height = imagesy($ornment);

		//Creation of the base
		$base = imagecreatetruecolor($base_width, $base_height);
		imagesavealpha($base, true);
    	$transparent_colour = imagecolorallocatealpha($base, 0, 0, 0, 127);
    	imagefill($base, 0, 0, $transparent_colour);

		//Determination of background and portrait size (identical)
		$incrustation_width = imagesx($background);
		$incrustation_height = imagesy($background);

		//Vertical offset if no big ornment
		if($ornment_lvl == 5){
			$offset = 0;
		} else {
			$offset = -28;
		}
		//Determination of the incrustation coordinates to have it centered
		$destination_x = 0.5*($base_width - $incrustation_width);
		$destination_y = 0.75*($base_height - $incrustation_height) + $offset;

		// Inscrustation of background and portrait in the ornments
		imagecopy($base, $background, $destination_x, $destination_y, 0, 0, $incrustation_width, $incrustation_height);
		imagecopy($base, $portrait, $destination_x, $destination_y, 0, 0, $incrustation_width, $incrustation_height);
		imagecopy($base, $ornment, 0, $offset, 0, 0, $base_width, $base_height);

		$model = preg_replace("#.+avatars/([\w_]+)\.png#", "$1", $this->avatar_entier());
		$filename = "../../webroot/img/players_img/".$this->id()."_".$this->element()."_".$model."_".$ornment_lvl.".png";

		imagepng($base, $filename, 9, PNG_NO_FILTER);
	}

	public function profile_background()
	{
		$ornment_lvl = $this->ornment_lvl();
		return "/webroot/img/cadres_avatars/cadres_profil/".$this->element()."_niv_".$ornment_lvl.".png";
	}

	public function element_couleur()
	{
		if($this->element() == "feu")
			{return "rouge";}
		elseif($this->element() == "eau")
			{return "bleu";}
		elseif($this->element() == "vent")
			{return "jaune";}
		elseif($this->element() == "terre")
			{return "vert";}
	}

	public function echeance_email_parent()	{
		if($this->email_parent() != "" || $this->classe() == "Prof")	{
			return self::EMAIL_PARENT_OK;
		}	else	{
			/* FOR ACCESS BASED ON TIME
			setlocale(LC_TIME, 'fr_FR');
			date_default_timezone_set('Europe/Paris');
			$today = time(); // On récupère la date du jour au format time
			$date_inscr = preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $this->date_inscription()); // On récupère la date d'inscription mais on considère que le joueur s'est inscrit à minuit pile
			$date_inscr = strtotime($date_inscr); //On la convertit au format time
			$interval = $today - $date_inscr;
			$secondes = $interval % 60;
			$interval = floor(($interval - $secondes)/60);
			$minutes  = $interval % 60;
			$interval = floor(($interval - $minutes)/60);
			$heures  = $interval % 24;
			$interval = floor(($interval - $heures)/24);
			$jours  = $interval;
			$echeance = 15 - $jours;
			if($echeance >= 2)
			{
				return "Attention, dans ".$echeance." jours, tu ne pourras plus jouer si tu n'as pas renseigné l'email d'un de tes parents.";
			}
			else if ($echeance == 1)
			{
				return "Attention, DEMAIN tu ne pourras plus jouer si tu n'as pas renseigné l'email d'un de tes parents.";
			}
			if($this->niveau()<4){
				return "Attention, à partir du niveau 4, tu ne pourras plus jouer si tu n'as pas renseigné l'email d'un de tes parents.";
			} else	{
				return self::COMPTE_BLOQUE;
			} */
			return "Renseigne l'email d'un de tes parents pour qu'il puisse suivre tes progrès.";
		}
	}

	public function percentageCompletionProfile(){
		$percentage = 0;
		$increment = round(100/7);
		if($this->nom() != ""){
			$percentage += $increment;
		}
		if($this->prenom() != ""){
			$percentage += $increment;
		}
		if($this->departement() != ""){
			$percentage += $increment;
		}
		if($this->college() != ""){
			$percentage += $increment;
		}
		if($this->email() != ""){
			$percentage += $increment;
		}
		if($this->email_parent() != "" || $this->classe() == "Prof"){
			$percentage += $increment;
		}
		if($this->pseudo() != ""){
			$percentage += $increment;
		}
		if(abs($percentage-100)<4){
			$percentage = 100;
		}
		return $percentage;
	}

	public function colorCompletionProfile(){
		if($this->percentageCompletionProfile() <= 50){
			return "rouge";
		} else if($this->percentageCompletionProfile() < 100){
			return "orange";
		} else if($this->percentageCompletionProfile() == 100){
			return "vert";
		}
	}

	public function requireProfileCompletion(){
		if($this->percentageCompletionProfile() < 100 && $this->niveau >= 2){
			return true;
		} else {
			return false;
		}
	}

	public function requireEmailCompletion($timeslot){
		/*
		if($this->tuto() == "fini" && $this->nb_jours_fin_tuto() > 0 && $this->email() == "" && $timeslot == "NoTimeSlot"){
			return true;
		} else {
			return false;
		} */
		return false;
	}

	public function requireEmailConfirmation($timeslot){
		/*
		if($this->tuto() == "fini" && $this->nb_jours_fin_tuto() > 0 && $this->email_confirme() == 0 && $timeslot == "NoTimeSlot"){
			return true;
		} else {
			return false;
		} */
		return false;
	}

	public function fieldRequired($name){
		if($name == "nom" && $this->nom() == ""){
			return "champRequis";
		} elseif($name == "prenom" && $this->prenom() == ""){
			return "champRequis";
		} elseif($name == "departement" && $this->departement() == ""){
			return "champRequis";
		} elseif($name == "college" && $this->college() == ""){
			return "champRequis";
		} elseif($name == "email" && $this->email() == ""){
			return "champRequis";
		} elseif($name == "email_parent" && $this->email_parent() == ""){
			return "champRequis";
		} else {
			return "champ";
		}
	}

	public function nb_jours_inscription()
	{
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = time(); // On récupère la date du jour au format time
		$date_inscr = preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $this->date_inscription()); // On récupère la date d'inscription mais on considère que le joueur s'est inscrit à minuit pile
		$date_inscr = strtotime($date_inscr); //On la convertit au format time
		$interval = $today - $date_inscr;
		$secondes = $interval % 60;
		$interval = floor(($interval - $secondes)/60);
		$minutes  = $interval % 60;
		$interval = floor(($interval - $minutes)/60);
		$heures  = $interval % 24;
		$interval = floor(($interval - $heures)/24);
		$jours  = $interval;
		return $jours;
	}

	public function nb_jours_fin_tuto()	{
		if($this->tuto() != "fini"){
			return 0;
		}
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = time(); // On récupère la date du jour au format time
		$date_fin_tuto = preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $this->date_fin_tuto()); // On récupère la date d'inscription mais on considère que le joueur a fini son tuto à minuit pile
		$date_fin_tuto = strtotime($date_fin_tuto); //On la convertit au format time
		$interval = $today - $date_fin_tuto;
		$secondes = $interval % 60;
		$interval = floor(($interval - $secondes)/60);
		$minutes  = $interval % 60;
		$interval = floor(($interval - $minutes)/60);
		$heures  = $interval % 24;
		$interval = floor(($interval - $heures)/24);
		$jours  = $interval;
		return $jours;
	}

	public function nb_jours_dernier_log()
	{
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = time(); // On récupère la date du jour au format time
		$date_dernier_log = preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $this->dernier_log()); // On récupère la date du dernier_log mais on considère que le joueur s'est connecté à minuit pile
		$date_dernier_log = strtotime($date_dernier_log); //On la convertit au format time
		$interval = $today - $date_dernier_log;
		$secondes = $interval % 60;
		$interval = floor(($interval - $secondes)/60);
		$minutes  = $interval % 60;
		$interval = floor(($interval - $minutes)/60);
		$heures  = $interval % 24;
		$interval = floor(($interval - $heures)/24);
		$jours  = $interval;
		$echeance = 15 - $jours;
		return (int) $jours;
	}

	public function derniere_connexion()
	{
		$today = time(); // On récupère la date du jour au format time
		$log = strtotime($this->dernier_log());
		$interval = $today - $log;
		if($this->connecte() == "oui" && $interval < 5*60){
			return "En ligne actuellement";
		} elseif($interval < 2*60){
			return "Il y a 1 minute";
		} elseif($interval < 60*60){
			return "Il y a ".floor($interval/60)." minutes";
		} elseif($interval < 2*60*60){
			return "Il y a 1 heure";
		} elseif($interval < 24*60*60){
			return "Il y a ".floor($interval/3600)." heures";
		} elseif($interval < 2*24*60*60){
			return "Il y a 1 jour";
		} elseif($interval < 30*24*60*60){
			return "Il y a ".floor($interval/(24*3600))." jours";
		} else {
			$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
			$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
			return "Il y a plus d'un mois";
			//return $jour[date("w", $log)]." ".date("d", $log)." ".$mois[date("n", $log)]." ".date("Y", $log)." à ".date("H", $log)."h".date("i", $log);
		}
	}

	public function convertDate($date){
		$date = strtotime($date);
		$mois = array("","janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre");
		return date("d", $date)." ".$mois[date("n", $date)];
	}

	public function determiner_position()
	{
		switch($this->element())
		{
			case "feu" :
					$xmin = 530;
					$xmax = 680;
					$ymin = 500;
					$ymax = 680;
					break;
				case "eau" :
					$xmin = 210;
					$xmax = 400;
					$ymin = 210;
					$ymax = 400;
					break;
				case "vent" :
					$xmin = 250;
					$xmax = 450;
					$ymin = 550;
					$ymax = 720;
					break;
				case "terre" :
					$xmin = 500;
					$xmax = 680;
					$ymin = 170;
					$ymax = 380;
					break;
		}
		$X = rand($xmin, $xmax)/10;
		$Y = rand($ymin, $ymax)/10;
		$position = array();
		$position["posX"] = $X;
		$position["posX"] = (string) $position["posX"]."%";
		$position["posY"] = $Y;
		$position["posY"] = (string) $position["posY"]."%";
		$this->setPosition($position);
	}

	public function date_inscription_txt()
	{
		$inscr = $this->date_inscription();
		$inscr = substr($inscr, 8, 2)."/".substr($inscr, 5, 2)."/".substr($inscr, 0, 4);
		return $inscr;
	}

	public function bilan_saison()
	{
		$today = time(); // On récupère la date du jour au format time
		$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		return "Bilan Saison ".$mois[date("n", $today)]." ".date("Y", $today);
	}

	public function surligner_bonus_elem($facteur, $type)
	{
		switch($type)
		{
			case "atq":
				if($facteur > 100)
					{return "<span class='g p1'><font color='#00A452'>".$facteur."%</font></span>";}
				elseif($facteur < 100)
					{return "<span class='g p1'><font color='#FF0000'>".$facteur."%</font></span>";}
				else
					{return $facteur."%";}
				break;
			case "def":
				if($facteur < 100)
					{return "<span class='g p1'><font color='#00A452'>".$facteur."%</font></span>";}
				elseif($facteur > 100)
					{return "<span class='g p1'><font color='#FF0000'>".$facteur."%</font></span>";}
				else
					{return $facteur."%";}
				break;
		}
	}

	public function ranking_change($old_week_individual_ranking, $new_week_individual_ranking, $old_global_individual_ranking, $new_global_individual_ranking)
	{
		$diff_week_ranking = $old_week_individual_ranking - $new_week_individual_ranking;
		$msg_end_fight = "";
		if($diff_week_ranking > 0){
			if($diff_week_ranking == 1){
				$plural = "";
			} else {
				$plural = "s";
			}
			$msg_end_fight .= "<span class='ib l100 mh2 p2'>Tu as gagné ".$diff_week_ranking." place".$plural." au classement de la semaine.</span><a class='gris' href='/app/controllers/classement.php?period=semaine'><span class='ib l100 g p2 under'>Tu es maintenant ".$new_week_individual_ranking."° !</span></a>";
		}
		$diff_global_ranking = $old_global_individual_ranking - $new_global_individual_ranking;
		if($diff_global_ranking > 0){
			if($diff_global_ranking == 1){
				$plural = "";
			} else {
				$plural = "s";
			}
			$msg_end_fight .= "<span class='ib l100 mh2 p2'>Tu as gagné ".$diff_global_ranking." place".$plural." au classement mensuel.</span><a class='gris' href='/app/controllers/classement.php?period=global'><span class='ib l100 g p2 under'>Tu es maintenant ".$new_global_individual_ranking."° !</span></a>";
		}
		return $msg_end_fight;
	}

	public function msg_tuteur($page, $nb, $realises_aujourdhui, $precisions)
	{
		if($this->sexe()=="gars")
			{$e = "";}
		elseif($this->sexe()=="fille")
			{$e = "e";}
		if($nb == 1){
			$s = "";
			$x = "";
		}	else {
			$s = "s";
			$x = "x";
		}
		switch($page)
		{
			case "index" :
				if($this->tuteur() == "Namuka")
				{
					if(!$realises_aujourdhui) //Si aucun défi n'a été réalisé aujourd'hui
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Salut ".$this->pseudo().", l'entraînement n'attend pas, je t'ai sélectionné ".$nb." défi".$s." aujourd'hui !";
								break;
							case "2" :
								$msg_tuteur = "Tu m’aurais presque manqué".$e." !<br>Je t’ai préparé ".$nb." défi".$s." pour aujourd'hui !";
								break;
							case "3" :
								$msg_tuteur = "Tiens mais te revoilà ".$this->pseudo()." !<br>Prêt".$e." pour ".$nb." nouveau".$x." défi".$s." ?";
								break;
						}
					}
					elseif($nb > 1) //Si le joueur a déjà fait un défi aujourd'hui mais qu'il lui en reste encore plus d'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus que ".$nb." défi".$s." aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste encore ".$nb."!";
								break;
							case "3" :
								$msg_tuteur = "Un autre pour la route?<br>Il m’en reste ".$nb.".";
								break;
						}
					}
					elseif($nb == 1) //Si le joueur a déjà fait un défi aujourd'hui et qu'il lui en reste qu'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus qu'un seul défi aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste un dernier!";
								break;
							case "3" :
								$msg_tuteur = "Un dernier pour la route?";
								break;
						}
					}
					elseif($nb == 0) //Si le joueur a fait tous ses défis
					{
						$rand=rand(1,1);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Désolé ".$this->pseudo()." mais je n’ai plus de défis pour toi aujourd’hui.<br>En revanche, tu peux continuer à t'entraîner sur les défis que tu as déjà rencontrés !";
								break;
						}
					}
				}
				if($this->tuteur() == "Katillys")
				{
					if(!$realises_aujourdhui) //Si aucun défi n'a été réalisé aujourd'hui
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Salut ".$this->pseudo().", l'entraînement n'attend pas, je t'ai sélectionné ".$nb." défi".$s." aujourd'hui !";
								break;
							case "2" :
								$msg_tuteur = "Tu m’aurais presque manqué".$e." !<br>Je t’ai préparé ".$nb." défi".$s." pour aujourd'hui !";
								break;
							case "3" :
								$msg_tuteur = "Tiens mais te revoilà ".$this->pseudo()." !<br>Prêt".$e." pour ".$nb." nouveau".$x." défi".$s." ?";
								break;
						}
					}
					elseif($nb > 1) //Si le joueur a déjà fait un défi aujourd'hui mais qu'il lui en reste encore plus d'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus que ".$nb." défi".$s." aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste encore ".$nb."!";
								break;
							case "3" :
								$msg_tuteur = "Un autre pour la route?<br>Il m’en reste ".$nb.".";
								break;
						}
					}
					elseif($nb == 1) //Si le joueur a déjà fait un défi aujourd'hui et qu'il lui en reste qu'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus qu'un seul défi aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste un dernier!";
								break;
							case "3" :
								$msg_tuteur = "Un dernier pour la route?";
								break;
						}
					}
					elseif($nb == 0) //Si le joueur a fait tous ses défis
					{
						$rand=rand(1,1);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Désolé ".$this->pseudo()." mais je n’ai plus de défis pour toi aujourd’hui.<br>En revanche, tu peux continuer à t'entraîner sur les défis que tu as déjà rencontrés !";
								break;
						}
					}
				}
				if($this->tuteur() == "Sivem")
				{
					if(!$realises_aujourdhui) //Si aucun défi n'a été réalisé aujourd'hui
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Salut ".$this->pseudo().", l'entraînement n'attend pas, je t'ai sélectionné ".$nb." défi".$s." aujourd'hui !";
								break;
							case "2" :
								$msg_tuteur = "Tu m’aurais presque manqué".$e." !<br>Je t’ai préparé ".$nb." défi".$s." pour aujourd'hui !";
								break;
							case "3" :
								$msg_tuteur = "Tiens mais te revoilà ".$this->pseudo()." !<br>Prêt".$e." pour ".$nb." nouveau".$x." défi".$s." ?";
								break;
						}
					}
					elseif($nb > 1) //Si le joueur a déjà fait un défi aujourd'hui mais qu'il lui en reste encore plus d'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus que ".$nb." défi".$s." aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste encore ".$nb."!";
								break;
							case "3" :
								$msg_tuteur = "Un autre pour la route?<br>Il m’en reste ".$nb.".";
								break;
						}
					}
					elseif($nb == 1) //Si le joueur a déjà fait un défi aujourd'hui et qu'il lui en reste qu'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus qu'un seul défi aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste un dernier!";
								break;
							case "3" :
								$msg_tuteur = "Un dernier pour la route?";
								break;
						}
					}
					elseif($nb == 0) //Si le joueur a fait tous ses défis
					{
						$rand=rand(1,1);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Désolé ".$this->pseudo()." mais je n’ai plus de défis pour toi aujourd’hui.<br>En revanche, tu peux continuer à t'entraîner sur les défis que tu as déjà rencontrés !";
								break;
						}
					}
				}
				if($this->tuteur() == "Leorn")
				{
					if(!$realises_aujourdhui) //Si aucun défi n'a été réalisé aujourd'hui
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Salut ".$this->pseudo().", l'entraînement n'attend pas, je t'ai sélectionné ".$nb." défi".$s." aujourd'hui !";
								break;
							case "2" :
								$msg_tuteur = "Tu m’aurais presque manqué".$e." !<br>Je t’ai préparé ".$nb." défi".$s." pour aujourd'hui !";
								break;
							case "3" :
								$msg_tuteur = "Tiens mais te revoilà ".$this->pseudo()." !<br>Prêt".$e." pour ".$nb." nouveau".$x." défi".$s." ?";
								break;
						}
					}
					elseif($nb > 1) //Si le joueur a déjà fait un défi aujourd'hui mais qu'il lui en reste encore plus d'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus que ".$nb." défi".$s." aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste encore ".$nb."!";
								break;
							case "3" :
								$msg_tuteur = "Un autre pour la route?<br>Il m’en reste ".$nb.".";
								break;
						}
					}
					elseif($nb == 1) //Si le joueur a déjà fait un défi aujourd'hui et qu'il lui en reste qu'un à faire
					{
						$rand=rand(1,3);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Allez, plus qu'un seul défi aujourd’hui!";
								break;
							case "2" :
								$msg_tuteur = "On remet ça?<br>Il m’en reste un dernier!";
								break;
							case "3" :
								$msg_tuteur = "Un dernier pour la route?";
								break;
						}
					}
					elseif($nb == 0) //Si le joueur a fait tous ses défis
					{
						$rand=rand(1,1);
						switch($rand)
						{
							case "1" :
								$msg_tuteur = "Désolé ".$this->pseudo()." mais je n’ai plus de défis pour toi aujourd’hui.<br>En revanche, tu peux continuer à t'entraîner sur les défis que tu as déjà rencontrés !";
								break;
						}
					}
				}
				break;
			case "grimoire" :
				if($precisions == "achat")
				{
					$rand=rand(1,4);
					switch($rand)
					{
						case "1" :
							$msg_tuteur = "Bien vu, j'aime bien ce sort moi aussi.";
							break;
						case "2" :
							$msg_tuteur = "Ce n'est pas mon sort préféré mais j'imagine que tu sais ce que tu fais...";
							break;
						case "3" :
							$msg_tuteur = "Quoi ce sort ? Autant jouer aux billes avec tes Pyrs !";
							break;
						case "4" :
							$msg_tuteur = "Excellent, ce sort déchire !";
							break;
					}
				}
				if($precisions == "conseil")
				{
					$rand=rand(1,2);
					switch($rand)
					{
						case "1" :
							$msg_tuteur = 'Certains sorts deviennent plus puissants si tu réussis à les invoquer plusieurs fois de suite en combat. Applique toi pour ne pas briser la série !';
							break;
						case "2" :
							$msg_tuteur = "Certains sorts ont des effets puissants lorsqu'ils sont combinés mais c'est à toi de trouver les synergies.";
							break;
					}
				}
				break;
			case "accueil_defi" :
				$rand=rand(1,2);
				switch($rand)
				{
					case "1" :
						$msg_tuteur = "Bon, on y va ou on se regarde dans le blanc des yeux ?";
						break;
					case "2" :
						$msg_tuteur = "L'entraînement ne va pas se faire tout seul, à un moment donné faut se lancer !";
						break;
				}
				break;
			case "fin_defi" :
				if($nb > 1) {
					$rand=rand(1,3);
					switch($rand)	{
						case "1" :
							$msg_tuteur = "Allez, plus que ".$nb." défi".$s." aujourd’hui!";
							break;
						case "2" :
							$msg_tuteur = "On remet ça?<br>Il m’en reste encore ".$nb."!";
							break;
						case "3" :
							$msg_tuteur = "Un autre pour la route?<br>Il m’en reste ".$nb.".";
							break;
					}
				}
				elseif($nb == 1) {
					$rand=rand(1,3);
					switch($rand)	{
						case "1" :
							$msg_tuteur = "Allez, plus qu'un seul défi aujourd’hui!";
							break;
						case "2" :
							$msg_tuteur = "On remet ça?<br>Il m’en reste un dernier!";
							break;
						case "3" :
							$msg_tuteur = "Un dernier pour la route?";
							break;
					}
				}
				elseif($nb == 0) {
					$rand=rand(1,1);
					switch($rand)	{
						case "1" :
							$msg_tuteur = "Et si tu allais acheter des sorts avec toutes ces Pyrs ?";
							break;
					}
				}
				break;
			case "prepa_combats" :
				$rand=rand(1,2);
				switch($rand)
				{
					case "1" :
						$msg_tuteur = "Moins vous êtes nombreux pour affronter un monstre, plus vous gagnez de prestige si vous gagnez. Mais parfois, il n'y a pas le choix, vous devez être nombreux pour pouvoir triompher.";
						break;
					case "2" :
						$msg_tuteur = "Il parait qu'il existe sur Navadra des monstres extrêmement puissants et rares...<br>Mais c'est sûrement juste une légende !";
						break;
				}
				break;
			case "combats_decks" :
				$rand=rand(1,2);
				switch($rand)
				{
					case "1" :
						$msg_tuteur = "Un de ces jours, faudra que je t'explique ma légendaire combinaison de sorts.";
						break;
					case "2" :
						$msg_tuteur = "Alors aujourd'hui, tu préfères un style plutôt offensif ou défensif ?";
						break;
				}
				break;
			case "combattre" :
				if($nb == 1)
				{
					$rand=rand(1,2);
					switch($rand)
					{
						case "1" :
							$msg_tuteur = "Rien ne vaut un petit face à face avec un monstre pour savoir ce que l'on vaut...";
							break;
						case "2" :
							$msg_tuteur = "Un duel comme je les aime, ah si seulement je pouvais aussi débarasser l'île de ces bestioles...";
							break;
					}
				}
				if($nb > 1)
				{
					$rand=rand(1,2);
					switch($rand)
					{
						case "1" :
							$msg_tuteur = "Dans la course pour le Prestige, mieux vaut gagner à plusieurs que perdre tout seul ! Mais... il est aussi possible de perdre à plusieurs.";
							break;
						case "2" :
							$msg_tuteur = "Ce combat s'annonce épique... J'ai hâte de voir ça !";
							break;
					}
				}
				break;
		}
		return $msg_tuteur;
	}

	public function days_since_inscription() {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d %H:%M:%S', time());
		$today = preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $today); //We take midnight as the reference time
		$today = strtotime($today);

		$inscription = strtotime(preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $this->date_inscription())); //We take midnight as the reference time
		$daysInterval = round( ($today - $inscription) / 60/60/24 );
		return $daysInterval;
	}

	public function days_since_last_connexion() {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d %H:%M:%S', time());
		$today = preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $today); //We take midnight as the reference time
		$today = strtotime($today);

		$last_connexion = strtotime(preg_replace('/\d\d:\d\d:\d\d/', "00:00:00", $this->dernier_log())); //We take midnight as the reference time
		$daysInterval = round( ($today - $last_connexion) / 60/60/24 );
		return $daysInterval;
	}

	public function gameLimitation(){
		/*
		if($this->abonnement_ok() == 1 || $this->niveau()< 5 || $this->classe() == "Prof"){
			return 0;
		} else {
			return 1;
		} */
		return 0;
	}

	public function restrictedFree(){
		if($this->date_inscription() > "2016-12-14 23:59:59"){
			return 1;
		} else {
			return 0;
		}
	}

	public function nbFightsRestricted(){
		if($this->restrictedFree() == 1) {
			return 0;
		} else {
			return 1;
		}
	}


	public function sameEmail(){
		if($this->email() == $this->email_parent() && $this->email() != ""){
			return true;
		} else {
			return false;
		}
	}

	public function rootLink(){
		$server_add = "";
    $server = "";
		if( isset($_SERVER['SERVER_ADDR'])) $server_add = $_SERVER['SERVER_ADDR'];
    if( isset($_SERVER['SERVER_NAME'])) $server = $_SERVER['SERVER_NAME'];
		if($server != "localhost" && $server_add != "localhost" && $server != "127.0.0.1" && $server_add != "127.0.0.1"){
			return "https://jeu.navadra.com";
		} else {
			return "http://localhost";
		}
	}

	public function firstname() {
		if($this->prenom() == ""){
			return $this->pseudo();
		} else {
			return $this->prenom();
		}
	}

	public function sendEmailEndFreePeriod($recipient, $countChallenges, $teacher){
		if($teacher == "NoTeacher"){
			$inscription = "s'est inscrit sur Navadra le ".$this->convertDate($this->date_inscription()).".";
		} else {
			if($teacher->sexe() == "gars"){
				$teacherName = "M. ".$teacher->nom();
			} else {
				$teacherName = "Mme ".$teacher->nom();
			}
			$inscription = "a découvert Navadra en cours de mathématiques avec ".$teacherName." et a continué à s'entraîner à la maison.";
		}

		$link = $this->rootLink()."/app/controllers/suivi_joueur.php?source=emailParent1&link=".$this->suivi_link();
		if($this->echeance_email_parent() != Joueur::EMAIL_PARENT_OK){
			$EmailParent = "Nous n'avons pas pu envoyer d'email à tes parents parce que tu ne l'as toujours pas renseigné. <a href='".$this->rootLink()."'>Connecte-toi sur Navadra</a> et va dans tes paramètres pour envoyer à tes parents un lien pour continuer l'aventure en illimité.";
		} else {
			$EmailParent = "Nous avons envoyé un email récapitulatif à l’un de tes parents (".$this->email_parent().") pour l’inviter à prendre un Pass Navadra. Si tes parents n’ont toujours rien reçu, c’est probablement que l’adresse email n’est pas la bonne. Rends-toi alors dans tes paramètres de Navadra pour la modifier.";
		}
		if($recipient == "parent" && $this->echeance_email_parent() == Joueur::EMAIL_PARENT_OK){
			$this->send_email("129345", "Michel", $this->firstname()." et Navadra", $this->email_parent(), $params = '{ "Prenom": "'.$this->firstname().'", "DateInscription": "'.$inscription.'", "NbDefis": "'.$countChallenges.'", "Link": "'.$link.'" }');
		} elseif($recipient == "player" && !$this->sameEmail()){
			$this->send_email("129359", "Navadra", "Niveau 5 atteint sur Navadra", $this->email(), $params = '{ "Prenom": "'.$this->firstname().'", "Tuteur": "'.$this->tuteur().'", "EmailParent": "'.$EmailParent.'", "Link": "'.$link.'" }');
		}
	}

	public function sendReminderEndFreePeriod($recipient, $countChallenges, $timeSlots){
		$inscription = $this->convertDate($this->date_inscription());
		$link = $this->rootLink()."/app/controllers/suivi_joueur.php?source=emailParent2&link=".$this->suivi_link();
		if($recipient == "parent" && $this->echeance_email_parent() == Joueur::EMAIL_PARENT_OK){
			$this->send_email("129350", "Michel", $this->firstname()." et Navadra", $this->email_parent(), $params = '{ "Prenom": "'.$this->firstname().'", "DateInscription": "'.$inscription.'", "NbDefis": "'.$countChallenges.'", "timeSlots": "'.$timeSlots.'", "Link": "'.$link.'" }');
		}
	}

	public function sendEmailActivationLink(){
		if(!$this->sameEmail() && $this->classe() != "Prof"){ //Mail player
			$link = $this->rootLink()."/app/controllers/ajax_disconnected.php?confirmEmail=".$this->suivi_link();
			$this->send_email("120444", $this->tuteur(), "Confirme ton email", $this->email(), $params = '{ "Pseudo": "'.$this->pseudo().'", "Link": "'.$link.'" }');
		} elseif(!$this->sameEmail() && $this->classe() == "Prof"){ //Mail teacher
			$link = $this->rootLink()."/app/controllers/ajax_disconnected.php?confirmEmail=".$this->suivi_link();
			$this->send_email("123079", "Jérémie", "Confirmez votre email", $this->email(), $params = '{ "Pseudo": "'.$this->pseudo().'", "Link": "'.$link.'" }');
		} else { //Mail parent
			$link = $this->rootLink()."/app/controllers/ajax_disconnected.php?confirmEmailParent=".$this->suivi_link();
			if($this->sexe() == "gars"){
				$child = "fils";
			} else {
				$child = "fille";
			}
			$this->send_email("120462", "Jérémie", "Confirmez votre email", $this->email_parent(), $params = '{ "Pseudo": "'.$this->firstname().'", "Sex": "'.$this->feminine("e").'", "Person": "'.$this->feminine("3rd").'", "Child": "'.$child.'", "Link": "'.$link.'" }');
		}
	}


	public function emailFeedbackFreePlan(){
		$content = "Salut ".$this->firstname()." !<br><br>";
		$content .= "Je vois que tu as déjà un niveau avancé dans le jeu, c'est super !
		Comme tu le sais, à partir du niveau 5, le contenu devient limité à 1 Défi et ".$this->nbFightsRestricted()." Monstre par jour.
		Le Pass Navadra est nécessaire pour avoir 100% du contenu quotidien.

		Est-ce que tu pourrais me dire ce que tu penses de ce système stp ?
		Tu peux répondre par oui/non aux questions ci-dessous ou écrire un peu plus si tu veux ^^.
		- La version gratuite te suffit ?
		- Tes parents ne veulent pas te prendre de Pass ?
		- Tu n'oses pas en parler avec eux ?
		- Autre raison ?

		Merci d'avance pour ta réponse, et à bientôt ;)

		Jérémie, un des créateurs.";
		return $content;
	}

	public function msgProgressPlayer(){
		$content = "Salut ".$this->firstname()." !<br><br>";
		$content .= "Tu as atteint le niveau 10 dans Navadra : FELICITATIONS !
		Tu as dû faire un bon paquet de défis pour en arriver là...

		J'aimerais te poser une question : 
		As-tu l'impression d'avoir progressé en maths en jouant à Navadra ? Ton regard sur la matière a-t-il changé ? Ta moyenne a-t-elle évolué ?

		Merci d'avance et à bientôt ;)

		Jérémie, un des créateurs.";
		return $content;
	}

}
