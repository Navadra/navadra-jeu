<?php

class ConversationsManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
	{
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  }
	
	public function add(Conversation $conversation) //Ajoute une conversation dans la BDD
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO conversations(joueur1, joueur2, date_dernier_msg) VALUES(:joueur1, :joueur2, :date_dernier_msg)');
		$q->execute(array(
			'joueur1' => $conversation->joueur1(),
			'joueur2' => $conversation->joueur2(),
			'date_dernier_msg' => $date
			));
		$conversation->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),	
			'date_dernier_msg' => $date
    	));
	}
	
	public function update(Conversation $conversation) //Permet de mettre à jour la date du dernier msg
    {
		$q = $this->_db_RW->prepare('UPDATE conversations SET date_dernier_msg=:date_dernier_msg WHERE id=:id');
		$q->execute(array(
			'date_dernier_msg' => $conversation->date_dernier_msg(),
			'id' => $conversation->id(),
			));
	}
	
	public function delete(Conversation $conversation) //Supprime une conversation
    {
		$q = $this->_db_RW->prepare('DELETE FROM conversations WHERE id=:id');
		$q->execute(array(
			'id' => $conversation->id(),
			));
	}
	
	public function delete_all(Joueur $joueur) //Supprime tous les conversations d'un joueur (s'il supprime son compte)
    {
		$q = $this->_db_RW->prepare('DELETE FROM conversations WHERE joueur1= :joueur OR joueur2= :joueur');
		$q->execute(array(
			'joueur' => $joueur->id(),
			));
	}
		
	public function get_all(Joueur $joueur) //Permet de récupérer toutes les conversations d'un joueur
    {
		$q = $this->_db_RO->prepare('SELECT * FROM conversations WHERE joueur1= :joueur OR joueur2= :joueur ORDER BY date_dernier_msg DESC');
		$q->execute(array(
 			'joueur' => $joueur->id(),
	 		));
		$conversations = array();
		while($donnees = $q->fetch())
		{
		$conversations[] = new Conversation($donnees);
		}
		return $conversations;
	}
	
	public function conversation_existante($id_joueur1, $id_joueur2) //Vérifie si il existe déjà une conversation engagée entre 2 joueurs
	{
		$q = $this->_db_RO->prepare('SELECT * FROM conversations WHERE (joueur1= :joueur1 AND joueur2= :joueur2) OR (joueur1= :joueur2 AND joueur2= :joueur1)');
		$q->execute(array(
 			'joueur1' => $id_joueur1,
			'joueur2' => $id_joueur2,
	 		));
		$conv = $q->fetch();
		if($conv)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function get($id_joueur1, $id_joueur2) //Récupère la conversation engagée entre 2 joueurs
	{
		$q = $this->_db_RO->prepare('SELECT * FROM conversations WHERE (joueur1= :joueur1 AND joueur2= :joueur2) OR (joueur1= :joueur2 AND joueur2= :joueur1)');
		$q->execute(array(
 			'joueur1' => $id_joueur1,
			'joueur2' => $id_joueur2,
	 		));
		$donnees = $q->fetch();
		return new Conversation($donnees);
	}
	
}