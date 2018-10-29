<?php

class FlowManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }


public function save_or_update(Flow $flow) //Modifie un défi (tous les paramètres au cas où modifs par admin)
  {
    $params = array(
        'joueur_id' => $flow->getJoueur_id(),
        'combat_id' => $flow->getCombat_id(),
        'turn_player' => $flow->getTurn_player(),
        'round' => $flow->getRound(),
        'spell_level' => $flow->getSpell_level(),
        'spell_num' => $flow->getSpell_num(),
        'spell_reussite' => $flow->getSpell_reussite(),
        'success' => $flow->getSuccess(),
        'hit' => $flow->getHit(),
        'pm_monster' => $flow->getPm_monster(),
        'pm_player' => $flow->getPm_player(),
        'absorb' => $flow->getAbsorb(),
        'dodge' => $flow->getDodge(),
        'heal' => $flow->getHeal(),
        'sendback' => $flow->getSendback(),
        'skipturn' => $flow->getSkipturn()
    );
    if ($flow->getId() > 0) {
      $params['id'] = $flow->getId();
      // UPDATE
      $q = $this->_db_RW->prepare('UPDATE flow SET joueur_id=:joueur_id, combat_id=:combat_id, turn_player=:turn_player, round=:round, spell_num=:spell_num, spell_level=:spell_level, spell_reussite=:spell_reussite, success=:success, hit=:hit, pm_player=:pm_player, pm_monster=:pm_monster, absorb=:absorb, dodge=:dodge, heal=:heal, sendback=:sendback, skipturn=:skipturn WHERE id=:id');
      $q->execute($params);
    }
    else {
      // We just check that the flow doesn't already exists : bug loop combattre
      // If it already exists : go back to island...
      $qtest = $this->_db_RO->prepare('SELECT count(*) as count_flow FROM flow WHERE joueur_id=:joueur_id AND combat_id=:combat_id AND turn_player=:turn_player AND round=:round AND spell_num=:spell_num AND spell_reussite=:spell_reussite');
      $qtest->execute(array(
          'joueur_id' => $flow->getJoueur_id(),
          'combat_id' => $flow->getCombat_id(),
          'turn_player' => $flow->getTurn_player(),
          'round' => $flow->getRound(),
          'spell_num' => $flow->getSpell_num(),
          'spell_reussite' => $flow->getSpell_reussite()
      ));
      $count = $qtest->fetch();
      if ($count["count_flow"] == 0) {
        // SAVE only if flow does not exist
        $query2 = 'INSERT INTO flow (joueur_id, combat_id, turn_player, round, spell_num, spell_level, spell_reussite, success, hit, pm_player, pm_monster, absorb, dodge, heal, sendback, skipturn) VALUES ( :joueur_id, :combat_id, :turn_player, :round, :spell_num, :spell_level, :spell_reussite, :success, :hit, :pm_player, :pm_monster, :absorb, :dodge, :heal, :sendback, :skipturn )';
        $q2 = $this->_db_RW->prepare($query2);
        $q2->execute($params);

        $flow->setId($this->_db_RW->lastInsertId());
//        $flow->hydrate(array(
//            'id' => $this->_db_RW->lastInsertId(),
//        ));
      }
      else{
        error_log("KLOUG - FIGHT LOOP BUG ! with data : " . explode(',',$params) );
      }
    }
  }

  public function get($id) //Permet de récupérer un flow par son id
  {
    $q = $this->_db_RO->prepare('SELECT * FROM flow WHERE id= :id');
    $q->execute(array(
        'id' => $id,
    ));
    $donnees = $q->fetch();
    $flow = new Flow($donnees);
    return $flow;
  }

	public function deleteFlowsFight($idFight) {
    $q = $this->_db_RW->prepare('DELETE FROM flow WHERE combat_id= :combat_id');
    $q->execute(array(
        'combat_id' => $idFight,
    ));
  }

  public function fight_won_against_elem(Joueur $joueur, $elem)
  {
    $q = $this->_db_RO->prepare('SELECT count(*) AS total FROM flow WHERE joueur_id=:joueur_id AND elem_monster=:elem_monster');
    $q->execute(array(
        'joueur_id' => $joueur->id(),
        'elem_monster' => $elem,
    ));
    $donnees = $q->fetch();
    return $donnees["total"];
  }

	public function delete_all(Joueur $joueur) {
		$q = $this->_db_RW->prepare('DELETE FROM flow WHERE joueur_id= :joueur_id');
		$q->execute(array(
			'joueur_id' => $joueur->id(),
			));
	}


}
