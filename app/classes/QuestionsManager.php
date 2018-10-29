<?php

class QuestionsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
  {
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
	}

	public function add(Question $question) {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$today = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO questions (idPlayer, category, question, answer, choice2, choice3, choice4, timer, goodAnswers, wrongAnswers, dateCreation) VALUES(:idPlayer, :category, :question, :answer, :choice2, :choice3, :choice4, :timer, :goodAnswers, :wrongAnswers, :dateCreation)');
		$q->execute(array(
			'idPlayer' => $question->idPlayer(),
			'category' => $question->category(),
			'question' => $question->question(),
			'answer' => $question->answer(),
			'choice2' => $question->choice2(),
			'choice3' => $question->choice3(),
			'choice4' => $question->choice4(),
			'timer' => 30,
			'goodAnswers' => 0,
			'wrongAnswers' => 0,
			'dateCreation' => $today
			));
		$question->hydrate(array(
      'id' => $this->_db_RW->lastInsertId(),
			'timer' => 30,
			'goodAnswers' => 0,
			'wrongAnswers' => 0,
			'date_question' => $today,
    	));
	}

	public function update(Question $question) {
		$q = $this->_db_RW->prepare('UPDATE questions SET idPlayer=:idPlayer, category=:category, question=:question, answer=:answer, choice2=:choice2, choice3=:choice3, choice4=:choice4, timer=:timer, goodAnswers=:goodAnswers, wrongAnswers=:wrongAnswers, dateCreation=:dateCreation WHERE id=:id');
		$q->execute(array(
			'idPlayer' => $question->idPlayer(),
			'category' => $question->category(),
			'question' => $question->question(),
			'answer' => $question->answer(),
			'choice2' => $question->choice2(),
			'choice3' => $question->choice3(),
			'choice4' => $question->choice4(),
			'timer' => $question->timer(),
			'goodAnswers' => $question->goodAnswers(),
			'wrongAnswers' => $question->wrongAnswers(),
			'dateCreation' => $question->dateCreation(),
			'id' => $question->id()
			));
	}

	public function getAll() {
		$q = $this->_db_RO->prepare('SELECT * FROM questions ORDER BY dateCreation DESC');
		$q->execute();
		$questions = array();
		while($donnees = $q->fetch())	{
			$questions[] = new Question($donnees);
		}
		return $questions;
	}

	public function getPlayer(Joueur $player) {
		$q = $this->_db_RO->prepare('SELECT * FROM questions WHERE idPlayer= :idPlayer ORDER BY dateCreation DESC');
		$q->execute(array(
 			'idPlayer' => $player->id(),
	 	));
		$questions = array();
		while($donnees = $q->fetch())	{
			$questions[] = new Question($donnees);
		}
		return $questions;
	}

	public function get($id) {
		$q = $this->_db_RO->prepare('SELECT * FROM questions WHERE id= :id');
		$q->execute(array(
 			'id' => $id,
	 	));
		$donnees = $q->fetch();
		$question = new Question($donnees);
		return $question;
	}

	public function delete_all(Joueur $joueur) {
		$q = $this->_db_RW->prepare('DELETE FROM questions WHERE id_player= :id_player');
		$q->execute(array(
			'id_player' => $joueur->id(),
			));
	}

}
