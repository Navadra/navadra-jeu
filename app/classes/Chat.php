<?php 
namespace classes; 
use Ratchet\MessageComponentInterface; 
use Ratchet\ConnectionInterface; 

class Chat implements MessageComponentInterface { 

	protected $clients;
	protected $userData; 
	
	public function __construct() { 
		$this->clients = new \SplObjectStorage;
		$this->userData = array();
	}
 
	public function onOpen(ConnectionInterface $conn) {
		// Store the new connection to send messages to later
		$this->clients->attach($conn);
		$msg = array();
		$msg["type"] = "connected";
		$msg["idConnexion"] = $conn->resourceId;
		$conn->send(json_encode($msg));
		echo "New user! Id : ".$conn->resourceId.".\n";
	}
	 
	public function onMessage(ConnectionInterface $from, $msg) {
		$numRecv = count($this->clients) - 1;
		echo sprintf('User %d sent a message.\n'
		, $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
		
		$msg = json_decode($msg);
		if(isset($msg->idConnexion)){ //A new player is connected
			$newPlayer = array();
			$newPlayer["id"] = $msg->id;
			$newPlayer["pseudo"] = $msg->pseudo;
			$newPlayer["portrait"] = $msg->portrait;
			$newPlayer["level"] = $msg->level;
			$newPlayer["posX"] = $msg->posX;
			$newPlayer["posY"] = $msg->posY;
			$newPlayer["channel"] = $msg->channel;
			$this->userData[$from->resourceId] = $newPlayer;
			foreach ($this->clients as $client) {
				$client->send(json_encode($this->userData)); //Send the player currently connected
			}
		} elseif(isset($msg->newChannel)){ //A player changes channel
			$data = $this->userData[$msg->idPlayer];
			$data["channel"] = $msg->newChannel;
			$this->userData[$msg->idPlayer] = $data; 
			foreach ($this->clients as $client) {
				$client->send(json_encode($this->userData));
			}
		} else { //A player sends a message
			foreach ($this->clients as $client) {
				$client->send(json_encode($msg));
			}
		}
	}
	 
	public function onClose(ConnectionInterface $conn) {
		// Connexion closed, get rid of the client
		$this->clients->detach($conn);
		unset($this->userData[$conn->resourceId]);
		foreach ($this->clients as $client) {
			$client->send(json_encode($this->userData)); //Send the player currently connected
		}
		echo "Player ".$conn->resourceId." disconnected\n";
	}
	 
	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "An error has occurred: {$e->getMessage()}\n"; 
		$conn->close();
	}


}