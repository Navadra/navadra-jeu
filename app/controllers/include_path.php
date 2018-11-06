<?php

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
$paths[] = '../vendor/';
$paths[] = 'vendor/';
$paths[] = get_include_path();

set_include_path(join(PATH_SEPARATOR, $paths));

function chargerClasse($classname){
	  require($classname.'.php');
}

spl_autoload_register('chargerClasse');

$server = "";
if( isset($_SERVER['SERVER_NAME'])) $server = $_SERVER['SERVER_NAME'];

/* JSON LOAD DEACTIVATED ON WINDOWS */
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    //echo '...';
} 
else {
// ******************** START JSON LOAD ONLY ON LINUX
/* Chargement des JSON des defis
  if($server != "localhost"){

    $mem = new Memcached();
    $mem->addServer("127.0.0.1", 11211);

    function getDirContents($dir, &$results = array()){
      $files = scandir($dir);
      foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
          $results[] = $path;
        } else if($value != "." && $value != "..") {
          getDirContents($path, $results);
          $results[] = $path;
        }
      }
      return $results;
    }
    if ($mem->get('json_loaded') == false) {
      error_log("LOADING JSON MEMCACHED...");
      $st1 = round(microtime(true) * 1000);
      $dir_internal = 'generators/challenges/';
      $dir = '/var/www/html/' . $dir_internal;
      $jsons = getDirContents($dir);
      $i = 0;
      foreach ($jsons as $val){
        if ($i < 1){
          $check_t = file_get_contents($val);
          error_log($val . " = " . $check_t);
        }
        #$check_s = str_replace($dir, '', $val);
        $check_s = substr($val, strrpos($val, $dir_internal) + strlen($dir_internal) );
        error_log("LOADING ".$check_s);
        $mem->set( $check_s, file_get_contents($val) );
        if ($i < 1) {
          $check_u = $mem->get($val);
          error_log("CHECK_S = " .$check_s);
          error_log($val . " : CHECK = " . ($check_t == $check_u));
        }
        $i++;
      }
      $mem->set('json_loaded', true);
      error_log('All ' . $i . ' JSON LOADED IN ' . (round(microtime(true)* 1000) - $st1) . ' ms');
    }
  }
  // ******************** END JSON LOAD */
}

$db_RO = new PDO('mysql:host=localhost;port=3306;dbname=navadra;charset=utf8', 'Utilisateur_phpMyAdmin', 'VOTRE_MOT_DE_PASSE');
$db_RO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
$db_RW = new PDO('mysql:host=localhost;port=3306;dbname=navadra;charset=utf8', 'Utilisateur_phpMyAdmin', 'VOTRE_MOT_DE_PASSE');
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
