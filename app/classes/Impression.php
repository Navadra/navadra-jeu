<?php

class Impression
{
	protected $id,
			  $id_player,
			  $video,
			  $quality,
			  $date_impression;


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

	public function id_player()
	{
		return $this->id_player;
	}

	public function video()
	{
		return $this->video;
	}

	public function quality()
	{
		return $this->quality;
	}

	public function date_impression()
	{
		return $this->date_impression;
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

	public function setId_player($id_player)
	{
		$id_player = (int) $id_player;
		if($id_player>0)
		{
			$this->id_player = $id_player;
		}
	}

	public function setVideo($video)
	{
		$this->video = $video;
	}

	public function setQuality($quality)
	{
		$this->quality = $quality;
	}

	public function setDate_impression($date_impression)
	{
		$this->date_impression = $date_impression;
	}

	//METHODS









}
