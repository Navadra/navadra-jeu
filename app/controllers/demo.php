<?php
require("include_path_sans_autoload.php");

if(isset($_POST["email"]))
{
	$email = strtolower($_POST["email"]);
	if(!preg_match("#^[a-zA-Z0-9._-]{1,}@[a-zA-Z0-9_.-]{2,}\.[a-zA-Z]{2,4}$#", $email))
	{
		$msg_err = "L'adresse email n'est pas au bon format !";
	}
	else
	{
		//On vérifie que l'adresse email n'existe pas déjà
		$q = $db_RO->prepare('SELECT * FROM demo WHERE email=:email');
		$q->execute(array(
			'email' => $email,
		));
		$donnees = (bool) $q->fetch();
		if($donnees)
		{
			$msg_err = "Nous avons déjà ton email dans notre base de données.";
		}
		else
		{
			setlocale(LC_TIME, 'fr_FR');
			date_default_timezone_set('Europe/Paris');
			$date = strftime('%Y-%m-%d %H:%M:%S',time());
			$q = $db_RW->prepare('INSERT INTO demo(email, date_inscription) VALUES(:email, :date_inscription)');
			$q->execute(array(
				'email' => $email,
				'date_inscription' => $date,
			));
			$msg_conf = "Email enregistré, nous t'informerons dès qu'il y aura du nouveau !";
			$_POST["email"] = "";
		}
	}

	include_once("header.php");
	include_once("demo_view.php");
	include_once("footer_deco_view.php");

}
else
{
	$_POST["email"] = "";
	include_once("header.php");
	include_once("demo_view.php");
	include_once("footer_deco_view.php");
}