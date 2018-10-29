<?php

class Combat
{
	protected $id,
			  $id_orga,
			  $id_invites,
			  $id_monstre,
			  $ordre,
			  $id_prets,
			  $copies_joueurs,
			  $copie_monstre,
			  $endu_monstre_restante,
			  $deroulement,
			  $issue,
			  $prestige,
			  $date_combat,
			  $date_invitations,
			  $vu,
			  $prioritaire; //Non stocké dans la BDD

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

	public function id_orga()
	{
		return $this->id_orga;
	}

	public function id_invites()
	{
		return $this->id_invites;
	}

	public function id_monstre()
	{
		return $this->id_monstre;
	}

	public function ordre()
	{
		return $this->ordre;
	}

	public function id_prets()
	{
		return $this->id_prets;
	}

	public function copies_joueurs()
	{
		return $this->copies_joueurs;
	}

	public function copie_monstre()
	{
		return $this->copie_monstre;
	}

	public function endu_monstre_restante()
	{
		return $this->endu_monstre_restante;
	}

	public function deroulement()
	{
		return $this->deroulement;
	}

	public function issue()
	{
		return $this->issue;
	}

	public function prestige()
	{
		return $this->prestige;
	}

	public function date_combat()
	{
		return $this->date_combat;
	}

	public function date_invitations()
	{
		return $this->date_invitations;
	}

	public function vu()
	{
		return $this->vu;
	}

	public function prioritaire()
	{
		return $this->prioritaire;
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

	public function setId_orga($id_orga)
	{
		$id_orga = (int) $id_orga;
		if($id_orga>0)
		{
			$this->id_orga = $id_orga;
		}
	}

	public function setId_invites($id_invites)
	{
		$this->id_invites = $id_invites;
	}

	public function setId_monstre($id_monstre)
	{
		$id_monstre = (int) $id_monstre;
		if($id_monstre>0)
		{
			$this->id_monstre = $id_monstre;
		}
	}

	public function setOrdre($ordre)
	{
		$this->ordre = $ordre;
	}

	public function setId_prets($id_prets)
	{
		$this->id_prets = $id_prets;
	}

	public function setCopies_joueurs($copies_joueurs)
	{
		$this->copies_joueurs = $copies_joueurs;
	}

	public function setCopie_monstre($copie_monstre)
	{
		$this->copie_monstre = $copie_monstre;
	}

	public function setEndu_monstre_restante($endu_monstre_restante)
	{
		$this->endu_monstre_restante = $endu_monstre_restante;
	}

	public function setDeroulement($deroulement)
	{
		$deroulement = (string) $deroulement;
		$this->deroulement = $deroulement;
	}

	public function setIssue($issue)
	{
		$issue = (string) $issue;
		$this->issue = $issue;
	}

	public function setPrestige($prestige)
	{
		$prestige = (int) $prestige;
		$this->prestige = $prestige;
	}

	public function setDate_combat($date_combat)
	{
		$date_combat = (string) $date_combat;
		$this->date_combat = $date_combat;
	}

	public function setDate_invitations($date_invitations)
	{
		$this->date_invitations = $date_invitations;
	}

	public function setVu($vu)
	{
		$this->vu = $vu;
	}

	public function setPrioritaire($prioritaire)
	{
		$this->prioritaire = $prioritaire;
	}

	//Fonctions d'exportation en Json
	public function jsonSerialize()
    {
        $result = get_object_vars($this);
		return json_encode($result);
    }

	//Fonctions diverses
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
		return (int) $jours;
	}

	public function derniere_connexion()
	{
		$today = time(); // On récupère la date du jour au format time
		$log = strtotime($this->dernier_log());
		$interval = $today - $log;
		if($interval < 5*60) //Si l'utilisateur a eu une activité dans les 5 dernières minutes, on considère qu'il est en ligne
		{
			return "En ligne actuellement";
		}
		$jour = array("Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi");
		$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
		return $jour[date("w", $log)]." ".date("d", $log)." ".$mois[date("n", $log)]." ".date("Y", $log)." à ".date("H", $log)."h".date("i", $log);
	}

	public function derniere_invit_format_time()	{
		$date_invitations = array();
		foreach($this->date_invitations() as $date_invit)	{
			$date_invitations[] = strtotime($date_invit);
		}
		$date = max($date_invitations);
		return $date;
	}

	public function derniere_invit_format_date()
	{
		$date = $this->derniere_invit_format_time();
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%d/%m/%Y',$date);
		return $date;
	}

	public function date_invitation_joueur(Joueur $joueur)
	{
		$date_invitations = $this->date_invitations() ;
		$date = $date_invitations[$joueur->id()];
		$date = strtotime($date);
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%d/%m/%Y',$date);
		return $date;
	}

	public function tous_prets()
	{
		if(sizeof($this->id_prets()) == sizeof($this->ordre()))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function nb_chasseurs()
	{
		return sizeof($this->ordre());
	}

	public function nb_chasseurs_ko()
	{
		return substr_count($this->deroulement(), "changer_joueur");
	}

	public function prochain_a_jouer(){
		$nb_ko = $this->nb_chasseurs_ko();
		$ordre = $this->ordre();
		if(count($ordre) <= $nb_ko || $this->issue() != "")	{
			return 0;
		}	else	{
			$id_prochain = $ordre[$nb_ko];
			return (int) $id_prochain;
		}
	}

	public function alreadyFought(Joueur $player){
		$nb_ko = $this->nb_chasseurs_ko();
		if($nb_ko == 0){
			return false;
		} else {
			$alreadyFought = $this->ordre();
			array_splice($alreadyFought, $nb_ko);
			if(in_array($player->id(), $alreadyFought)){
				return true;
			} else {
				return false;
			}
		}
	}

	public function afficher_nb_chasseurs() {
		$nb = sizeof($this->ordre());
		if($nb == 1)
		{
			return "1 chasseur de monstres";
		}
		else
		{
			return $nb." chasseurs de monstres";
		}
	}

	public function combat_vu(Joueur $joueur)
	{
		if($this->vu() != "")
		{
			if(!in_array($joueur->id(), $this->vu(), true)) //Considère le combat comme vu si ce n'était pas le cas
			{
				$vu = $this->vu();
				$vu[] = $joueur->id();
				$this->setVu($vu);
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			$vu = array();
			$vu[] = $joueur->id();
			$this->setVu($vu);
			return false;
		}
	}

	public function date_combat_format_date()
	{
		$date = strtotime($this->date_combat());
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%d/%m/%Y',$date);
		return $date;
	}

	public function recuperer_numero_sorts()
	{
		$numero_sorts = array();
		$deroulement = explode(";", $this->deroulement());
		foreach($deroulement as $etape)
		{
			$descriptif = explode(",", $etape);
			if($descriptif[0] == "lancer_sort")
			{
				if(!in_array($descriptif[1], $numero_sorts))
				{
					$numero_sorts[] = $descriptif[1];
				}
			}
		}
		return $numero_sorts;
	}




}
