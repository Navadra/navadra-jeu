<?php

class Question
{
	protected $id,
			  $idPlayer,
			  $category,
			  $question,
			  $answer,
			  $choice2,
			  $choice3,
				$choice4,
				$timer,
				$goodAnswers,
				$wrongAnswers,
			  $dateCreation;


	public function hydrate(array $donnees)	{
    foreach ($donnees as $key => $value) {
      $method = 'set'.ucfirst($key);
      	if (method_exists($this, $method)) {
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

	public function idPlayer(){
		return $this->idPlayer;
	}

	public function category() {
		return $this->category;
	}

	public function question() {
		return $this->question;
	}

	public function answer() {
		return $this->answer;
	}

	public function choice2() {
		return $this->choice2;
	}

	public function choice3() {
		return $this->choice3;
	}

	public function choice4() {
		return $this->choice4;
	}

	public function timer() {
		return $this->timer;
	}

	public function goodAnswers() {
		return $this->goodAnswers;
	}

	public function wrongAnswers() {
		return $this->wrongAnswers;
	}

	public function dateCreation() {
		return $this->dateCreation;
	}

	//SETTERS
	public function setId($id) {
		$id = (int) $id;
		if($id>0)	{
			$this->id = $id;
		}
	}

	public function setIdPlayer($idPlayer) {
		$this->idPlayer = (int) $idPlayer;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function setQuestion($question) {
		$this->question = $question;
	}

	public function setAnswer($answer) {
		$this->answer = $answer;
	}

	public function setChoice2($choice2) {
		$this->choice2 = $choice2;
	}

	public function setChoice3($choice3) {
		$this->choice3 = $choice3;
	}

	public function setChoice4($choice4) {
		$this->choice4 = $choice4;
	}

	public function setTimer($timer) {
		$this->timer = (int) $timer;
	}

	public function setGoodAnswers($goodAnswers) {
		$this->goodAnswers = (int) $goodAnswers;
	}

	public function setWrongAnswers($wrongAnswers) {
		$this->wrongAnswers = (int) $wrongAnswers;
	}

	public function setDateCreation($dateCreation) {
		$this->dateCreation = $dateCreation;
	}


	//METHODS

	







}
