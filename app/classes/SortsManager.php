<?php

class SortsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }

  public function add(Sort $sort, Joueur $joueur) //Ajoute un sort dans la BDD
  {
    $q = $this->_db_RW->prepare('INSERT INTO sorts(id_joueur, num, niveau, element1) VALUES(:id_joueur, :num, :niveau, :element1)');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
        'num' => $sort->num(),
        'niveau' => 1,
        'element1' => $sort->element1()
    ));
    $sort->hydrate(array(
        'id' => $this->_db_RW->lastInsertId(),
        'niveau' => 1,
    ));
  }

  public function update(Sort $sort) //Modifie un sort (tous les paramètres au cas où modifs par admin)
  {
    $q = $this->_db_RW->prepare('UPDATE sorts SET num=:num, niveau=:niveau, element1=:element1 WHERE id=:id');
    $q->execute(array(
        'id' => $sort->id(),
        'num' => $sort->num(),
        'niveau' => $sort->niveau(),
        'element1' => $sort->element1()
    ));
  }

  public function create_ultimate(Joueur $joueur, $element) //Add the ultimate of a given element
  {
    switch($element)
	{
		case "feu" :
			$num = 7;
			break;
		case "eau" :
			$num = 14;
			break;
		case "vent" :
			$num = 21;
			break;
		case "terre" :
			$num = 28;
			break;
	}
	$ultimate = new Sort(array("id_joueur"=>$joueur->id(), "num"=>$num, "niveau"=>1, "element1"=>$element));
	$q = $this->_db_RW->prepare('INSERT INTO sorts(id_joueur, num, niveau, element1) VALUES(:id_joueur, :num, :niveau, :element1)');
    $q->execute(array(
        'id_joueur' => $ultimate->id_joueur(),
        'num' => $ultimate->num(),
        'niveau' => $ultimate->niveau(),
        'element1' => $ultimate->element1()
    ));
    $ultimate->hydrate(array(
        'id' => $this->_db_RW->lastInsertId()
    ));
  }

  public function nb_sorts(Joueur $joueur) //Permet de compter le nombre de sorts du joueur
  {
    $q = $this->_db_RO->prepare('SELECT COUNT(*) FROM sorts WHERE id_joueur= :id_joueur AND num!=7 AND num!=14 AND num!=21 AND num!=28');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $donnees = $q->fetch();
    return $donnees[0]; //Pour retourner directement le nombre et pas un array
  }

  public function delete_all(Joueur $joueur) //Supprime tous les sorts d'un joueur (s'il supprime son compte ou réinitialise ses sorts)
  {
    $q = $this->_db_RW->prepare('DELETE FROM sorts WHERE id_joueur=:id_joueur');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
  }

  //Permet de récupérer tous les sorts du joueur classés par num croissant
  public function get(Joueur $joueur, $exclude_critical = false) {
    $query = 'SELECT * FROM sorts WHERE id_joueur= :id_joueur ';
    if ($exclude_critical == true) {
      $query = $query.' AND num!=7 AND num!=14 AND num!=21 AND num!=28 ';
    }
    $query = $query.' ORDER BY num ASC';
    $q = $this->_db_RO->prepare($query);
    $q->execute(array(
        'id_joueur' => $joueur->id(),
    ));
    $sorts = array();
    while ($donnees = $q->fetch()) {
      // Using carac table
      $spell = new Sort($donnees);
      $spell->hydrate($spell->carac_sort()); //On l'hydrate avec ses caractéristiques de base
      $sorts[] = $spell;
    }
    return $sorts;
  }

  //Get all player's spells of a given element
  public function get_element(Joueur $joueur, $element)
  {
    $query = 'SELECT * FROM sorts WHERE id_joueur= :id_joueur AND element1= :element1';
    $q = $this->_db_RO->prepare($query);
    $q->execute(array(
        'id_joueur' => $joueur->id(),
		'element1' => $element,
    ));
    $sorts = array();
    while ($donnees = $q->fetch())
	{
      // Using carac table
      $spell = new Sort($donnees);
      $spell->hydrate($spell->carac_sort()); //On l'hydrate avec ses caractéristiques de base
      $sorts[] = $spell;
    }
    return $sorts;
  }

  //Permet de récupérer tous les sorts du joueur classés par élément
  public function get_order_by_elem(Joueur $joueur) {
    $sorts = $this->get($joueur);
    return order_by_elem($sorts);
  }

  //Permet de récupérer tous les sorts du joueur classés par élément
  public function order_by_elem(array $sorts) {
    $sorts_feu = array();
    $sorts_eau = array();
    $sorts_vent = array();
    $sorts_terre = array();

    foreach ($sorts as $sort) {
      //On trie les sorts en fonction du profil élémentaire du joueur
      switch ($sort->element1()) {
        case "feu":
          $sorts_feu[] = $sort;
          break;
        case "eau":
          $sorts_eau[] = $sort;
          break;
        case "vent":
          $sorts_vent[] = $sort;
          break;
        case "terre":
          $sorts_terre[] = $sort;
          break;
      }
    }
    $liste_sorts = array();
    $liste_sorts["feu"] = $sorts_feu;
    $liste_sorts["eau"] = $sorts_eau;
    $liste_sorts["vent"] = $sorts_vent;
    $liste_sorts["terre"] = $sorts_terre;
    return $liste_sorts;
  }

  //Vérifie si un sort existe déjà parmi les sorts appartenant au joueur
  public function sort_existant($num, Joueur $joueur) {
    $q = $this->_db_RO->prepare('SELECT * FROM sorts WHERE id_joueur= :id_joueur AND num= :num');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
        'num' => $num,
    ));
    $sort = $q->fetch();
    if ($sort) {
      return true;
    } else {
      return false;
    }
  }

  //Vérifie si un sort est connu par le joueur (si il a déjà le même sort d'un niveau supérieur ou égal)
  public function sort_connu(Sort $sort, Joueur $joueur) {
    $q = $this->_db_RO->prepare('SELECT * FROM sorts WHERE id_joueur= :id_joueur AND num= :num');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
        'num' => $sort->num(),
    ));
    $donnees = $q->fetch();
    if ($donnees) {
      $spell = new Sort($donnees);
      $spell->hydrate($spell->carac_sort()); //On l'hydrate avec ses caractéristiques de base
      if ($spell->niveau() >= $sort->niveau()) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  //Récupérer un sort par son numéro parmi les sorts appartenant au joueur
  public function recuperer_sort($num, Joueur $joueur) {
    $q = $this->_db_RO->prepare('SELECT * FROM sorts WHERE id_joueur= :id_joueur AND num= :num');
    $q->execute(array(
        'id_joueur' => $joueur->id(),
        'num' => $num,
    ));
    $donnees = $q->fetch();
    $spell = new Sort($donnees);
    $spell->hydrate($spell->carac_sort()); //On l'hydrate avec ses caractéristiques de base
    return $spell;
  }

  //Récupérer des sorts par les numéros parmi les sorts appartenant au joueur
  public function recuperer_sorts(Joueur $joueur, $nums) {
    $query = 'SELECT * FROM sorts WHERE id_joueur= :id_joueur AND (';

    $first = true;
    foreach($nums as $num) {
      if (! $first) {
        $query = $query.' or ';
      }
      $query = $query.'num='.$num;
      $first = false;
    }
    $query = $query.')';

    $q = $this->_db_RO->prepare($query);
    $q->execute(array(
        'id_joueur' => $joueur->id()
    ));
    $spells = array();
    while ($donnees = $q->fetch()) {
      $spell = new Sort($donnees);
      $spell->hydrate($spell->carac_sort()); //On l'hydrate avec ses caractéristiques de base
      $spells[] = $spell;
    }
    return $spells;
  }

  //Détermine le niveau d'un sort à proposer à un joueur (non encore possédé)
  public function determiner_niveau($num, Joueur $joueur) {
    if ($this->sort_existant($num, $joueur)) {
      $sort = $this->recuperer_sort($num, $joueur);

      return $sort->niveau() + 1;
    } else {
      return 1;
    }
  }

  public function determiner_id($num, Joueur $joueur) //Détermine l'id d'un sort déjà possédé par un joueur
  {
    if ($this->sort_existant($num, $joueur)) {
      $sort = $this->recuperer_sort($num, $joueur);

      return $sort->id();
    } else {
      return "";
    }
  }

  public function liste_sorts(Joueur $joueur) //Permet de récupérer la liste des sorts que peut apprendre le joueur
  {
    $sorts_feu = array();
    $sorts_eau = array();
    $sorts_vent = array();
    $sorts_terre = array();
    for ($i = 1; $i <= 28; $i++) {
		if($i != 7 && $i != 14 && $i != 21 && $i != 28)
		{
			$sort = new Sort(array("num" => $i)); //On créé un objet Sort en lui attribuant un numéro de 1 à 28
			$sort->hydrate($sort->carac_sort()); //On l'hydrate avec ses caractéristiques de base
			$sort->setNiveau($this->determiner_niveau($i, $joueur)); //On détermine son niveau en fonction des sorts déjà possédés par le joueur
			$sort->setId($this->determiner_id($i, $joueur)); //On détermine son id si le sort est déjà possédé
			if($sort->isLearnable($joueur) != "ok")	{
				$sort->blackAndWhiteIcon();
			}
			switch ($sort->element1()) //On trie les sorts en fonction du profil élémentaire du joueur
			{
			  case "feu":
				$sorts_feu[] = $sort;
				break;
			  case "eau":
				$sorts_eau[] = $sort;
				break;
			  case "vent":
				$sorts_vent[] = $sort;
				break;
			  case "terre":
				$sorts_terre[] = $sort;
				break;
			}
		}
    }
    $liste_sorts = array();
    $liste_sorts["feu"] = $sorts_feu;
    $liste_sorts["eau"] = $sorts_eau;
    $liste_sorts["vent"] = $sorts_vent;
    $liste_sorts["terre"] = $sorts_terre;
    return $liste_sorts;
  }

  public function premier_sort(Joueur $joueur) //Permet de récupérer le premier sort que peut apprendre le joueur (tuto)
  {
    $liste_sorts = array();
    switch ($joueur->tuteur()) {
      case "Namuka":
        $num_sort = 1;
        break;
      case "Katillys":
        $num_sort = 10;
        break;
      case "Sivem":
        $num_sort = 15;
        break;
      case "Leorn":
        $num_sort = 23;
        break;
    }

    $sort = new Sort(array("num" => $num_sort)); //On créé un objet Sort en lui attribuant un numéro de 1 à 28
    $sort->hydrate($sort->carac_sort()); //On l'hydrate avec ses caractéristiques de base
    $sort->setNiveau($this->determiner_niveau($num_sort, $joueur)); //On détermine son niveau en fonction des sorts déjà possédés par le joueur
    $sort->setId($this->determiner_id($num_sort, $joueur)); //On détermine son id si le sort est déjà possédé
    $liste_sorts[] = $sort;
    return $liste_sorts;
  }

  // Returns the weakest element against the given one
  public function get_weak_element_for($elem) {
    switch ($elem) {
      case "feu":
        return "terre";
      case "eau":
        return "feu";
      case "vent":
        return "eau";
      case "terre":
        return "vent";
    }
  }

  // Returns the element strongest against the given one
  public function get_strong_element_for($elem) {
    switch ($elem) {
      case "feu":
        return "eau";
      case "eau":
        return "vent";
      case "vent":
        return "terre";
      case "terre":
        return "feu";
    }
  }

  // Returns the element neither weak nor strong against the given one
  public function get_equals_element_for($elem) {
    switch ($elem) {
      case "feu":
        return "vent";
      case "eau":
        return "terre";
      case "vent":
        return "feu";
      case "terre":
        return "eau";
    }
  }

  /**
   * @param $joueur
   * @param $monstre
   * @param $spells_by_elem
   * @return array Selected best spells
   */
  function get_best_spells($joueur, $monstre, $spells_by_elem) {
    $selected_spells = array();

    // get existing spells if not given
    if ( is_array($spells_by_elem) == false ) {
      $spells_by_elem = $this->get_order_by_elem($joueur);
    }

    // Best element against monster's elemetn
    $strong_elem_against_monster = $this->get_strong_element_for($monstre->element());

    // Look if player has any strong spells for this monster
    $temp_strong_elem_spells = $spells_by_elem[$strong_elem_against_monster];
    if (count($temp_strong_elem_spells) > 0) {
      // Get them until 5
      foreach ($temp_strong_elem_spells as $temp_strong_spell) {
        // If still less than 5
        if (count($selected_spells) < 5) {
          $selected_spells[] = $temp_strong_spell;
        }
      }
    }

    // If still less than 5
    if (count($selected_spells) < 5) {
      // Choose inside element not in strenght spells for monster :
      // First try monster's element
      $temp_monster_elem_spells = $spells_by_elem[$monstre->element()];
      if (count($temp_monster_elem_spells) > 0) {
        // Get them until 5
        foreach ($temp_monster_elem_spells as $temp_monster_spell) {
          // If still less than 5
          if (count($selected_spells) < 5) {
            $selected_spells[] = $temp_monster_spell;
          }
        }
      }
    }

    // If still less than 5
    if (count($selected_spells) < 5) {
      // Normal element
      $temp_normal_elem_spells = $spells_by_elem[$this->get_equals_element_for($monstre->element())];
      if (count($temp_normal_elem_spells) > 0) {

        // Get them until 5
        foreach ($temp_normal_elem_spells as $temp_normal_spell) {
          // If still less than 5
          if (count($selected_spells) < 5) {
            $selected_spells[] = $temp_normal_spell;
          }
        }
      }
    }

    // If still less than 5
    if (count($selected_spells) < 5) {
      // Choose in weak spells :(
      $temp_weak_elem_spells = $spells_by_elem[$this->get_weak_element_for($monstre->element())];
      if (count($temp_weak_elem_spells) > 0) {
        // Get them until 5
        foreach ($temp_weak_elem_spells as $temp_weak_spell) {
          // If still less than 5
          if (count($selected_spells) < 5) {
            $selected_spells[] = $temp_weak_spell;
          }
        }
      }
    }

    return $selected_spells;
  }

	public function spellsToBuy(Joueur $player) {
		if($player->nb_jours_fin_tuto() > 0 || $player->tuto() != "fini"){
			return 0;
		}
		$minSpellsTheoric = 3;
		$spellLvl1 = new Sort(array("id_joueur"=>$player->id(), "num"=>1, "niveau"=>1, "element1"=>"feu"));
		$minSpellsPractice = $this->nb_sorts($player) + floor($player->pyrs_feu() / $spellLvl1->cout()) + floor($player->pyrs_eau() / $spellLvl1->cout()) + floor($player->pyrs_vent() / $spellLvl1->cout()) + floor($player->pyrs_terre() / $spellLvl1->cout());
		$minSpells = min($minSpellsTheoric, $minSpellsPractice);
		return $minSpells - $this->nb_sorts($player);
  }

  public static function comparer($a, $b)
  {
    return strcmp($a->num(), $b->num());
  }

}
