<?php

class Timeslot {

	protected $id,
			  $idTeacher,
			  $idClassroom,
			  $startTime,
				$endTime,
				$notion1,
				$notion2,
				$notion3,
				$dateCreation;


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

	public function __construct(array $donnees)	{
		$this->hydrate($donnees);
	}


	//GETTERS
	public function id() {
		return $this->id;
	}

	public function idTeacher() {
		return $this->idTeacher;
	}

	public function idClassroom() {
		return $this->idClassroom;
	}

	public function startTime() {
		return $this->startTime;
	}

	public function endTime()	{
		return $this->endTime;
	}

	public function notion1() {
		return $this->notion1;
	}

	public function notion2()	{
		return $this->notion2;
	}

	public function notion3()	{
		return $this->notion3;
	}

	public function dateCreation()	{
		return $this->dateCreation;
	}

	//SETTERS
	public function setId($id) {
		$id = (int) $id;
		if($id>0)	{
			$this->id = $id;
		}
	}

	public function setIdTeacher($idTeacher) {
		$idTeacher = (int) $idTeacher;
		if($idTeacher>0)	{
			$this->idTeacher = $idTeacher;
		}
	}

	public function setIdClassroom($idClassroom) {
		$idClassroom = (int) $idClassroom;
		if($idClassroom>0)	{
			$this->idClassroom = $idClassroom;
		}
	}

	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	public function setNotion1($notion1) {
		$this->notion1 = $notion1;
	}

	public function setNotion2($notion2) {
		$this->notion2 = $notion2;
	}

	public function setNotion3($notion3) {
		$this->notion3 = $notion3;
	}

	public function setDateCreation($dateCreation) {
		$this->dateCreation = $dateCreation;
	}

	//METHODS




}
