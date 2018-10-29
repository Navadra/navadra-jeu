<?php

class MessagesManager
{
	private $_db_RO;
	private $_db_RW;

  public function __construct($db_RO, $db_RW)
	{
 		$this->_db_RO = $db_RO;
 		$this->_db_RW = $db_RW;
  	}

	public function add(Message $message) //Ajoute un message dans la BDD
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = strftime('%Y-%m-%d %H:%M:%S',time());
		$q = $this->_db_RW->prepare('INSERT INTO messages(id_conversation, expediteur, destinataire, date_envoi, contenu, lu) VALUES(:id_conversation, :expediteur, :destinataire, :date_envoi, :contenu, :lu)');
		$q->execute(array(
			'id_conversation' => $message->id_conversation(),
			'expediteur' => $message->expediteur(),
			'destinataire' => $message->destinataire(),
			'date_envoi' => $date,
			'contenu' => $message->contenu(),
			'lu' => "non"
			));
		$message->hydrate(array(
      		'id' => $this->_db_RW->lastInsertId(),
			'date_envoi' => $date,
			'lu' => "non"
    	));
	}
	
	public function update($id_conversation, Joueur $joueur) //Modifie tous les messages non-lus dont le joueur actif était le destinataire d'une conversation pour les considérer comme lus
    {
		$q = $this->_db_RW->prepare('UPDATE messages SET lu=:lu WHERE id_conversation=:id_conversation AND destinataire=:destinataire');
		$q->execute(array(
			'id_conversation' => $id_conversation,
			'destinataire' => $joueur->id(),
			'lu' => "oui",
			));
	}
	
	public function delete(Message $message) //Supprime un message
    {
		$q = $this->_db_RW->prepare('DELETE FROM messages WHERE id=:id');
		$q->execute(array(
			'id' => $message->id(),
			));
	}
	
	public function delete_all(Joueur $joueur) //Supprime tous les conversations d'un joueur (s'il supprime son compte)
    {
		$q = $this->_db_RW->prepare('DELETE FROM messages WHERE expediteur= :joueur OR destinataire= :joueur');
		$q->execute(array(
			'joueur' => $joueur->id(),
			));
	}
		
	public function get_all(Conversation $conversation) //Permet de récupérer tous les messages d'une conversation
    {
		$q = $this->_db_RO->prepare('SELECT * FROM messages WHERE id_conversation= :id_conversation ORDER BY date_envoi DESC');
		$q->execute(array(
 			'id_conversation' => $conversation->id(),
	 		));
		$messages = array();
		while($donnees = $q->fetch())
		{
		$messages[] = new Message($donnees);
		}
		return $messages;
	}
	
	public function get_non_lu(Conversation $conversation)
	{
		$q = $this->_db_RO->prepare('SELECT * FROM messages WHERE id_conversation= :id_conversation AND lu= :lu AND (destinataire= :joueur1 OR destinataire= :joueur2) ORDER BY date_envoi DESC');
		$q->execute(array(
 			'id_conversation' => $conversation->id(),
			'lu' => "non",
			'joueur1' => $conversation->joueur1(),
			'joueur2' => $conversation->joueur2(),
	 		));
		$messages = array();
		while($donnees = $q->fetch())
		{
		$messages[] = new Message($donnees);
		}
		return $messages;
	}
	
	public function nbre_non_lu(Conversation $conversation, Joueur $joueur)
	{
		$q = $this->_db_RO->prepare('SELECT COUNT(*) FROM messages WHERE id_conversation= :id_conversation AND lu= :lu AND destinataire= :joueur ORDER BY date_envoi DESC');
		$q->execute(array(
 			'id_conversation' => $conversation->id(),
			'lu' => "non",
			'joueur' => $joueur->id(),
	 		));
		$donnees = $q->fetch();
		return $donnees[0]; //Pour retourner directement le nombre et pas un array
	}
	
	public function delete_vieux_msgs() //Supprime tous les msgs de plus de 60 jours
    {
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$date = time() - 60*24*60*60; //On se place 60 jours en arrière
		$date = strftime('%Y-%m-%d %H:%M:%S',$date);
		$q = $this->_db_RW->prepare('DELETE FROM messages WHERE date_envoi < :date');
		$q->execute(array(
			'date' => $date,
			));
	}
	
}