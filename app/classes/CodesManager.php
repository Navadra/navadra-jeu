<?php

class CodesManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
	{
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }

	public function add(Code $code)
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$dateCreation = strftime('%Y-%m-%d %H:%M:%S', time());
		$q = $this->_db_RW->prepare('INSERT INTO invitation_codes(id_sponsor, id_invited, code, max_invitations, category, description, date_creation, reward_obtained) VALUES(:id_sponsor, :id_invited, :code, :max_invitations, :category, :description, :date_creation, :reward_obtained)');
		$q->execute(array(
			'id_sponsor' => $code->id_sponsor(),
			'id_invited' => "",
			'code' => $code->code(),
			'max_invitations' => $code->max_invitations(),
			'category' => $code->category(),
			'description' => $code->description(),
			'date_creation' => $dateCreation,
			'reward_obtained' => 0
			));
		$code->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
    	));
	}

	public function generateCode(Joueur $joueur, $categoryInvitation, $maxInvitations, $descriptionInvitation)
	{
		$newCode = new Code(array("id_sponsor" => $joueur->id(), "max_invitations" => $maxInvitations, "category" => $categoryInvitation, "description" => $descriptionInvitation));
		$code = "";
		while($code == "" || $this->exists($code)){ //Verify that the code does not already exist
			if($joueur->admin()){
				$nbRandomCar = 10;
			} else {
				$code = substr($joueur->pseudo(), 0, 3); //We take the first 3 caracters of the sponsor pseudo
				$nbRandomCar = 7;
			}
			for($i=1;$i<=$nbRandomCar;$i++){ //We add random caracters
				$rand = rand(1, 3);
				switch($rand){
					case 1:
						$possibilities = "abcdefghijklmnopqrstuvwxyz";
						$choice = rand(1, 26);
						break;
					case 2:
						$possibilities = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
						$choice = rand(1, 26);
						break;
					case 3:
						$possibilities = "0123456789";
						$choice = rand(1, 10);
						break;
				}
				$code .= substr($possibilities, $choice-1, 1);
			}
		}
		$newCode->setCode($code);
		return $newCode;
	}

	public function update(Code $code)
    {
		//Serialization des arrays
		if($code->id_invited()){$id_invited = serialize($code->id_invited());}
		else{$id_invited = "";}
		$q = $this->_db_RW->prepare('UPDATE invitation_codes SET id_sponsor=:id_sponsor, id_invited=:id_invited, code=:code, max_invitations=:max_invitations, category=:category, description=:description, date_creation=:date_creation, reward_obtained=:reward_obtained WHERE id=:id');
		$q->execute(array(
			'id_sponsor' => $code->id_sponsor(),
			'id_invited' => $id_invited,
			'code' => $code->code(),
			'max_invitations' => $code->max_invitations(),
			'category' => $code->category(),
			'description' => $code->description(),
			'date_creation' => $code->date_creation(),
			'reward_obtained' => $code->reward_obtained(),
			'id' => $code->id()
		));
	}

	public function get_all()
    {
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes ORDER BY id DESC');
		$q->execute();
		$codes = array();
		while($donnees = $q->fetch())
		{
			$donnees["id_invited"] = unserialize($donnees["id_invited"]);
			$codes[] = new Code($donnees);
		}
		return $codes;
	}

	public function get_sponsor($id_sponsor) //Get the code of a sponsor
	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE id_sponsor= :id_sponsor');
		$q->execute(array(
 			'id_sponsor' => $id_sponsor,
	 		));
		$donnees = $q->fetch();
		$donnees["id_invited"] = unserialize($donnees["id_invited"]);
		$code = new Code($donnees);
		return $code;
	}

	public function get_invited($id_invited) //Get the code of an invitee
	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE id_invited LIKE :id_serialize');
		$q->execute(array(
 			'id_serialize' => '%i:'.$id_invited.';%',
	 		));
		if($donnees = $q->fetch()){
			$donnees["id_invited"] = unserialize($donnees["id_invited"]);
			$code = new Code($donnees);
			return $code;
		} else {
			return "Equipe Navadra";
		}
	}

	public function get_code($code)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE code= :code');
		$q->execute(array(
 			'code' => $code,
	 		));
		$donnees = $q->fetch();
		$donnees["id_invited"] = unserialize($donnees["id_invited"]);
		$code = new Code($donnees);
		return $code;
	}

	public function get($id)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 		));
		$donnees = $q->fetch();
		$donnees["id_invited"] = unserialize($donnees["id_invited"]);
		$code = new Code($donnees);
		return $code;
	}

	public function exists($code)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE code= :code');
		$q->execute(array(
 			'code' => $code,
	 		));
		if($donnees = $q->fetch()){
			return true;
		} else {
			return false;
		}
	}

	public function usable($code)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE code= :code');
		$q->execute(array(
 			'code' => $code,
	 		));
		$donnees = $q->fetch();
		$donnees["id_invited"] = unserialize($donnees["id_invited"]);
		$code = new Code($donnees);
		if($code->id_invited() == ""){
			$invited = 0;
		} else {
			$invited = sizeof($code->id_invited());
		}
		if($invited < $code->max_invitations()){
			return "ok";
		} else {
			return "tooMuchInvitees";
		}
	}

	public function rewardObtained(Joueur $joueur)	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE id_sponsor= :id_sponsor AND category= :category');
		$q->execute(array(
 			'id_sponsor' => $joueur->id(),
			'category' => "inscription"
	 		));
		$donnees = $q->fetch();
		$donnees["id_invited"] = unserialize($donnees["id_invited"]);
		$code = new Code($donnees);
		return $code->reward_obtained();
	}

	public function setRewardObtained(Joueur $joueur)	{
		$q = $this->_db_RO->prepare('SELECT * FROM invitation_codes WHERE id_sponsor= :id_sponsor AND category= :category');
		$q->execute(array(
 			'id_sponsor' => $joueur->id(),
			'category' => "inscription"
	 		));
		$donnees = $q->fetch();
		$donnees["id_invited"] = unserialize($donnees["id_invited"]);
		$code = new Code($donnees);
		$code->setReward_obtained(1);
		$this->update($code);
	}

	public function delete_all(Joueur $joueur) {
		$params = array(
	        'id_sponsor' => $joueur->id(),
	    );
      $q = $this->_db_RW->prepare('DELETE FROM invitation_codes WHERE id_sponsor=:id_sponsor');
      $q->execute($params);
  }

}
