<?php
/*
header("Access-Control-Allow-Origin: navadra.com", true);
header("Access-Control-Allow-Methods: GET,POST,PUT,OPTIONS", true);
header("Access-Control-Allow-Headers: x-requested-with", true);
*/
//if (session_status() == PHP_SESSION_NONE) {
//  header("Location: https://jeu.navadra.com");
//  exit;
//}

$paths[] = '.';
$paths[] = './';
$paths[] = 'app/controllers/';
$paths[] = 'app/views/';
$paths[] = 'app/classes/';
$paths[] = '../controllers/';
$paths[] = '../views/';
$paths[] = '../classes/';
$paths[] = '../../';
$paths[] = '../';
$paths[] = '../../vendor/';
$paths[] = '../../vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/';
$paths[] = '../vendor/';
$paths[] = '../vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/';
$paths[] = 'vendor/';
$paths[] = 'vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/';
$paths[] = get_include_path();

set_include_path(join(PATH_SEPARATOR, $paths));

$db_RO = new PDO('mysql:host=localhost;port=3306;dbname=navadra;charset=utf8', 'root', '');
$db_RO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$db_RW = new PDO('mysql:host=localhost;port=3306;dbname=navadra;charset=utf8', 'root', '');
$db_RW->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

session_start();

$manager = new JoueursManager($db_RO,$db_RW);
$abonnements_manager = new AbonnementsManager($db_RO,$db_RW);
$achievement_manager = new AchievementsManager($db_RO,$db_RW);
$ameliorations_manager = new AmeliorationsManager($db_RO,$db_RW);
$challenges_manager = new ChallengesManager($db_RO,$db_RW);
$classrooms_manager = new ClassroomsManager($db_RO,$db_RW);
$codes_manager = new CodesManager($db_RO,$db_RW);
$combats_manager = new CombatsManager($db_RO,$db_RW);
$conversations_manager = new ConversationsManager($db_RO,$db_RW);
$exercises_manager = new ExercisesManager($db_RO,$db_RW);
$flow_manager = new FlowManager($db_RO,$db_RW);
$impressions_manager = new ImpressionsManager($db_RO,$db_RW);
$messages_manager = new MessagesManager($db_RO,$db_RW);
$monstres_manager = new MonstresManager($db_RO,$db_RW);
$questions_manager = new QuestionsManager($db_RO,$db_RW);
$saisons_manager = new SaisonsManager($db_RO,$db_RW);
$scores_manager = new ScoresManager($db_RO,$db_RW);
$sorts_manager = new SortsManager($db_RO,$db_RW);
$timeslots_manager = new TimeslotsManager($db_RO,$db_RW);
$titles_manager = new TitlesManager($db_RO,$db_RW);
