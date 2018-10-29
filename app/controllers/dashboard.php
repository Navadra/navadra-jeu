<?php
require("include_path.php");
require("controleur_global.php");

$data = $manager->SignUpBetween("2017-06-05 00:00:00", "2017-06-24 23:59:59");
$activeUsers = $manager->activeUsers("2017-06-05 00:00:00", "2017-06-24 23:59:59");
if($joueur->admin()){
	if(isset($_GET["metric"]) && $_GET["metric"] == "medianLengthSession"){
		echo("Median length session : ".$exercises_manager->medianLengthSession("2016-10-10 00:00:00", "2017-12-31 23:59:59"));
	} elseif(isset($_GET["metric"]) && $_GET["metric"] == "maxSessionInterval"){
		echo("Duration after which players are considered losts : ".$manager->maxSessionInterval()." jours.");
	} elseif(isset($_GET["metric"]) && $_GET["metric"] == "averageSessionsWeek"){
		$startDate = "2016-10-10 00:00:00";
		$endDate = "2017-01-17 23:59:59";
		$nextWeek = $startDate;
		$countValues = array();
		$averageValues = array();
		while(strtotime($nextWeek) + 7*24*60*60 <= strtotime($endDate)){
			$lastWeek = $nextWeek;
			$nextWeek = strftime('%Y-%m-%d %H:%M:%S', strtotime($nextWeek) + 7*24*60*60);
			$data = $exercises_manager->playersActivity($lastWeek, $nextWeek);
			$logsPerPlayer = array();
			foreach($data as $d) {
				if(isset($logsPerPlayer[$d["playerId"]])){
					$logsPerPlayer[$d["playerId"]] ++;
				} else {
					$logsPerPlayer[$d["playerId"]] = 1;
				}
			}
			$countValuesTemp = array_count_values($logsPerPlayer);
			foreach($countValuesTemp as $number => $logs){
				if(isset($countValues[$number])){
					$countValues[$number] += $logs;
				} else {
					$countValues[$number] = $logs;
				}
			}
		}
		foreach($countValues as $number => $logs){
			for($i=1;$i<=$logs;$i++){
				$averageValues[] = $number;
			}
		}
		$average = round(array_sum($averageValues) / count($averageValues), 1);
		echo("Average number of connections per week : ".$average);
	} elseif(isset($_GET["metric"]) && $_GET["metric"] == "playerSponsor"){
		$countPlayersWhoSponsored = $manager->playersWhoSponsored("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		$nbPlayers = count($manager->SignUpBetween("2016-10-00 00:00:00", "2017-12-31 23:59:59"));
		$percentagePlayers = round($countPlayersWhoSponsored*100/$nbPlayers,1);
		echo("Percentage of players who sponsored someone : ".$percentagePlayers);
	} elseif(isset($_GET["metric"]) && $_GET["metric"] == "conversionOneMonster"){
		$data = $manager->SignUpBetween("2016-10-00 00:00:00", "2016-12-15 23:59:59");
		$nbPlayers = count($data);
		$passBought = 0;
		foreach($data as $d) {
			if($d["parentId"] != null && $d["parentId"] != 2 && $d["parentId"] != 3 && $d["parentId"] != 4){
				$passBought ++;
			}
		}
		echo("Conversion rate before 16/12 (1 monster in limited) : ".round($passBought/$nbPlayers*100, 1)."%");
	} elseif(isset($_GET["metric"]) && $_GET["metric"] == "conversionZeroMonster"){
		$data = $manager->SignUpBetween("2016-12-16 00:00:00", "2017-12-31 23:59:59");
		$nbPlayers = count($data);
		$passBought = 0;
		foreach($data as $d) {
			if($d["parentId"] != null && $d["parentId"] != 2 && $d["parentId"] != 3 && $d["parentId"] != 4){
				$passBought ++;
			}
		}
		echo("Conversion rate after 16/12 (0 monster in limited) : ".round($passBought/$nbPlayers*100, 1)."%");
	} elseif(isset($_GET["metric"]) && $_GET["metric"] == "retention1Month"){
		setlocale(LC_TIME, 'fr_FR');
		date_default_timezone_set('Europe/Paris');
		$limitSignUp = strftime('%Y-%m-%d %H:%M:%S', time()-60*60*24*31);
		$data = $manager->SignUpBetween("2016-10-00 00:00:00", $limitSignUp);
		$totalPlayers = 0;
		$players1Month = 0;
		foreach($data as $d) {
			if($d["playerTutorial"] == "fini"){
				if(strtotime($d["playerLastLog"]) >= strtotime($d["playerSignUp"]) + 60*60*24*30){
					$players1Month ++;
				}
				$totalPlayers ++;
			}
		}
		echo("Retention after 1 month : ".round($players1Month/$totalPlayers*100, 1)."%");
	} elseif(isset($_GET["retentionAnalysis"]) && $_GET["retentionAnalysis"] == "classroom"){
		$data = $manager->reachedLimitChallenges("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		$results = array();
		$results["6°"]["reached"] = $results["6°"]["total"] = 0;
		$results["5°"]["reached"] = $results["5°"]["total"] = 0;
		$results["4°"]["reached"] = $results["4°"]["total"] = 0;
		$results["3°"]["reached"] = $results["3°"]["total"] = 0;
		foreach($data as $d) {
			if($d["playerLevel"] >= 5){
				$results[$d["playerClassroom"]]["reached"] ++;
			}
			$results[$d["playerClassroom"]]["total"] ++;
		}
		echo("Perseverance by classroom :<br>");
		var_dump($results);
	} elseif(isset($_GET["retentionAnalysis"]) && $_GET["retentionAnalysis"] == "sex"){
		$data = $manager->reachedLimitChallenges("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		$results = array();
		$results["gars"]["reached"] = $results["gars"]["total"] = 0;
		$results["fille"]["reached"] = $results["fille"]["total"] = 0;
		foreach($data as $d) {
			if($d["playerLevel"] >= 5){
				$results[$d["playerSex"]]["reached"] ++;
			}
			$results[$d["playerSex"]]["total"] ++;
		}
		echo("Perseverance by sex :<br>");
		var_dump($results);
	} elseif(isset($_GET["retentionAnalysis"]) && $_GET["retentionAnalysis"] == "challenges"){
		$data = $manager->reachedLimitChallenges("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		$results = array();
		$results["1-2"]["reached"] = $results["1-2"]["total"] = 0;
		$results["2-3"]["reached"] = $results["2-3"]["total"] = 0;
		$results["3-4"]["reached"] = $results["3-4"]["total"] = 0;
		$results["4-5"]["reached"] = $results["4-5"]["total"] = 0;
		$results["5"]["reached"] = $results["5"]["total"] = 0;
		foreach($data as $d) {
			if($d["levelReached"] >= 1 && $d["levelReached"] < 2){
				$category = "1-2";
			} elseif($d["levelReached"] >= 2 && $d["levelReached"] < 3){
				$category = "2-3";
			} elseif($d["levelReached"] >= 3 && $d["levelReached"] < 4){
				$category = "3-4";
			} elseif($d["levelReached"] >= 4 && $d["levelReached"] < 5){
				$category = "4-5";
			} elseif($d["levelReached"] >= 5){
				$category = "5";
			}
			if($d["playerLevel"] >= 5){
				$results[$category]["reached"] ++;
			}
			$results[$category]["total"] ++;
		}
		echo("Perseverance by level reached in first 3 challenges :<br>");
		var_dump($results);
	} elseif(isset($_GET["retentionAnalysis"]) && $_GET["retentionAnalysis"] == "fights"){
		$data = $manager->reachedLimitFights("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		$results = array();
		$results["0-1"]["reached"] = $results["0-1"]["total"] = 0;
		$results["2-3"]["reached"] = $results["2-3"]["total"] = 0;
		$results["4-5"]["reached"] = $results["4-5"]["total"] = 0;
		$results["6"]["reached"] = $results["6"]["total"] = 0;
		foreach($data as $d) {
			if($d["issue"] >= 0 && $d["issue"] <= 1){
				$category = "0-1";
			} elseif($d["issue"] >= 2 && $d["issue"] <= 3){
				$category = "2-3";
			} elseif($d["issue"] >= 4 && $d["issue"] <= 5){
				$category = "4-5";
			} elseif($d["issue"] >= 6){
				$category = "6";
			}
			if($d["playerLevel"] >= 5){
				$results[$category]["reached"] ++;
			}
			$results[$category]["total"] ++;
		}
		echo("Perseverance by level reached in first 6 fights :<br>");
		var_dump($results);
	} elseif(isset($_GET["retentionAnalysis"]) && $_GET["retentionAnalysis"] == "videos"){
		$data = $manager->reachedLimitVideos("2016-12-01 00:00:00", "2017-12-31 23:59:59"); //The video were put online 1st of december 2016
		$results = array();
		$results["0"]["reached"] = $results["0"]["total"] = 0;
		$results["0-1"]["reached"] = $results["0-1"]["total"] = 0;
		$results["1-2"]["reached"] = $results["1-2"]["total"] = 0;
		$results["2-3"]["reached"] = $results["2-3"]["total"] = 0;
		$results["3"]["reached"] = $results["3"]["total"] = 0;
		foreach($data as $d) {
			if($d["qualityVideo"] == 0){
				$category = "0";
			} elseif($d["qualityVideo"] < 1){
				$category = "0-1";
			} elseif($d["qualityVideo"] < 2){
				$category = "1-2";
			} elseif($d["qualityVideo"] < 3){
				$category = "2-3";
			} elseif($d["qualityVideo"] == 3){
				$category = "3";
			}
			if($d["playerLevel"] >= 5){
				$results[$category]["reached"] ++;
			}
			$results[$category]["total"] ++;
		}
		echo("Perseverance by level of video liking :<br>");
		var_dump($results);
	} elseif(isset($_GET["retentionAnalysis"]) && $_GET["retentionAnalysis"] == "sponsored"){
		$data = $manager->reachedLimitSponsored("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		$results = array();
		$results["By another player"]["reached"] = $results["By another player"]["total"] = 0;
		$results["By teacher"]["reached"] = $results["By teacher"]["total"] = 0;
		$results["No invitation"]["reached"] = $results["No invitation"]["total"] = 0;
		$results["Others"]["reached"] = $results["Others"]["total"] = 0;
		foreach($data as $d) {
			if($d["categorySponsor"] == "inscription"){
				$category = "By another player";
			} elseif($d["categorySponsor"] == "Classe d'un prof"){
				$category = "By teacher";
			} elseif($d["categorySponsor"] == null){
				$category = "No invitation";
			} else {
				$category = "Others";
			}
			if($d["playerLevel"] >= 5){
				$results[$category]["reached"] ++;
			}
			$results[$category]["total"] ++;
		}
		echo("Perseverance by sponsorship :<br>");
		var_dump($results);
	} elseif(isset($_GET["globalProgressAnalysis"]) && $_GET["globalProgressAnalysis"] == "get"){
		$results = $scores_manager->globalChallengeProgress("2016-10-10 00:00:00", "2017-12-31 23:59:59");
		echo("Global math progress data :<br>");
		var_dump($results);
	} else {
		include_once("header.php");
		include_once("dashboard_view.php");
	}
} else {
	echo "Tu n'as pas les droits d'Admin !<br>";
}
