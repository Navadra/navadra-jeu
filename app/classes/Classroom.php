<?php

class Classroom {

	protected $id,
			  $idTeacher,
			  $idStudents,
			  $name,
				$level,
				$nbStudents,
				$maxStudents,
				$code,
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

	public function idStudents() {
		return $this->idStudents;
	}

	public function name() {
		return $this->name;
	}

	public function level()	{
		return $this->level;
	}

	public function nbStudents() {
		return $this->nbStudents;
	}

	public function maxStudents()	{
		return $this->maxStudents;
	}

	public function code()	{
		return $this->code;
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

	public function setIdStudents($idStudents) {
		$this->idStudents = $idStudents;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setLevel($level) {
		$this->level = $level;
	}

	public function setNbStudents($nbStudents) {
		$this->nbStudents = $nbStudents;
	}

	public function setMaxStudents($maxStudents) {
		$this->maxStudents = $maxStudents;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function setDateCreation($dateCreation) {
		$this->dateCreation = $dateCreation;
	}

	//METHODS
	public function addStudent(Joueur $joueur){
		if($this->nbStudents() < $this->maxStudents()){
			$students = $this->idStudents();
			$students[] = $joueur->id();
			$this->setIdStudents($students);
			$this->setNbStudents($this->nbStudents() + 1);
		}
	}

	public function removeStudent(Joueur $joueur){
		if($this->nbStudents() > 0){
			$students = $this->idStudents();
			array_splice($students, array_search($joueur->id(), $students), 1);
			$this->setIdStudents($students);
			$this->setNbStudents($this->nbStudents() - 1);
		}
	}

	public function notFull () {
		if($this->idStudents() == ""){
			$students = 0;
		} else {
			$students = sizeof($this->idStudents());
		}
		if($students < $this->maxStudents()){
			return "ok";
		} else {
			return "tooMuchStudents";
		}
	}




}
