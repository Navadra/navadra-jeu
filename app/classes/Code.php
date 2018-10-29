<?php

class Code
{
	protected $id,
			  $id_sponsor,
			  $id_invited,
			  $code,
				$max_invitations,
				$category,
				$description,
				$date_creation,
				$reward_obtained;


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

	public function id_sponsor()
	{
		return $this->id_sponsor;
	}

	public function id_invited()
	{
		return $this->id_invited;
	}

	public function code()
	{
		return $this->code;
	}

	public function max_invitations()
	{
		return $this->max_invitations;
	}

	public function category()
	{
		return $this->category;
	}

	public function description()
	{
		return $this->description;
	}

	public function date_creation()
	{
		return $this->date_creation;
	}

	public function reward_obtained()
	{
		return $this->reward_obtained;
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

	public function setId_sponsor($id_sponsor)
	{
		$id_sponsor = (int) $id_sponsor;
		if($id_sponsor>0)
		{
			$this->id_sponsor = $id_sponsor;
		}
	}

	public function setId_invited($id_invited)
	{
		$this->id_invited = $id_invited;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function setMax_invitations($max_invitations)
	{
		$this->max_invitations = $max_invitations;
	}

	public function setCategory($category)
	{
		$this->category = $category;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function setDate_creation($date_creation)
	{
		$this->date_creation = $date_creation;
	}

	public function setReward_obtained($reward_obtained)
	{
		$this->reward_obtained = $reward_obtained;
	}


	//METHODS
	public function addInvitee(Joueur $joueur){
		$invitees = $this->id_invited();
		$invitees[] = $joueur->id();
		$this->setId_invited($invitees);
	}





}
