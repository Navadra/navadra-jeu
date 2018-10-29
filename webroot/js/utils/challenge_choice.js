//Object containing the different number of exercises available by element, challenge and level
var exercises = {
	"fire": {
		"integers" : {
			"1" : 6,
			"2" : 4,
			"3" : 6,
			"4" : 6
		},
		"decimals" : {
			"1" : 4,
			"2" : 4,
			"3" : 5,
			"4" : 3
		},
		"multiples" : {
			"1" : 4,
			"2" : 4,
			"3" : 4,
			"4" : 4
		},
		"fractions" : {
			"1" : 3,
			"2" : 4,
			"3" : 6,
			"4" : 6
		},
		"greatNumbers" : {
			"1" : 4,
			"2" : 4,
			"3" : 3,
			"4" : 4
		},
		"divisions" : {
			"1" : 5,
			"2" : 5,
			"3" : 4,
			"4" : 4
		},
		"problemInterpretation" : {
			"1" : 6,
			"2" : 4,
			"3" : 4,
			"4" : 4
		}
	},
	"water": {
		"proportionality" : {
			"1" : 4,
			"2" : 4,
			"3" : 3,
			"4" : 4
		},
		"percentages" : {
			"1" : 4,
			"2" : 5,
			"3" : 4,
			"4" : 4
		},
		"tables" : {
			"1" : 5,
			"2" : 4,
			"3" : 4,
			"4" : 5
		},
		"graphs" : {
			"1" : 4,
			"2" : 4,
			"3" : 5,
			"4" : 5
		},
		"radars" : {
			"1" : 4,
			"2" : 4,
			"3" : 2,
			"4" : 2
		},
		"bars" : {
			"1" : 4,
			"2" : 4,
			"3" : 5,
			"4" : 4
		},
		"circulars" : {
			"1" : 4,
			"2" : 4,
			"3" : 3,
			"4" : 4
		}
	},
	"wind": {
		"lines" : {
			"1" : 5,
			"2" : 4,
			"3" : 4,
			"4" : 4
		},
		"angles" : {
			"1" : 4,
			"2" : 4,
			"3" : 4,
			"4" : 4
		},
		"circles" : {
			"1" : 5,
			"2" : 4,
			"3" : 4,
			"4" : 7
		},
		"quadrilaterals" : {
			"1" : 5,
			"2" : 4,
			"3" : 5,
			"4" : 5
		},
		"triangles" : {
			"1" : 4,
			"2" : 3,
			"3" : 4,
			"4" : 4
		},
		"bisectors" : {
			"1" : 4,
			"2" : 6,
			"3" : 2,
			"4" : 4
		},
		"symmetries" : {
			"1" : 5,
			"2" : 3,
			"3" : 3,
			"4" : 4
		}
	},
	"earth": {
		"lengths" : {
			"1" : 1,
			"2" : 4,
			"3" : 4,
			"4" : 4
		},
		"weights" : {
			"1" : 1,
			"2" : 4,
			"3" : 4,
			"4" : 4
		},
		"durations" : {
			"1" : 1,
			"2" : 5,
			"3" : 4,
			"4" : 4
		},
		"prices" : {
			"1" : 1,
			"2" : 4,
			"3" : 4,
			"4" : 4
		},
		"perimeters" : {
			"1" : 5,
			"2" : 6,
			"3" : 6,
			"4" : 4
		},
		"areas" : {
			"1" : 6,
			"2" : 5,
			"3" : 4,
			"4" : 5
		},
		"volumes" : {
			"1" : 1,
			"2" : 4,
			"3" : 4,
			"4" : 4
		}
	}
};

//Object which stores all challenges selected and their respective number of selections (to avoid repetitive challenges)
var exercisesHistoric = {};

//INPUT REQUIRED

// var potentialChallenges. Array of objects containing all challenges already practiced by the player. Each challenge (object) has the following attributes : element, name, currentMastery.
/*
var potentialChallenges = [
	{"element": "fire", "name": "integers", "currentMastery": 4},
	{"element": "fire", "name": "decimals", "currentMastery": 3},
	{"element": "fire", "name": "multiples", "currentMastery": 2},
	{"element": "fire", "name": "fractions", "currentMastery": 2},
	{"element": "fire", "name": "greatNumbers", "currentMastery": 0},
	{"element": "fire", "name": "divisions", "currentMastery": 0},
	{"element": "fire", "name": "problemInterpretation", "currentMastery": 1},
	{"element": "water", "name": "proportionality", "currentMastery": 1}
]

var spellElement : the element of the spell chosen by the player.
*/

function challenge_choice(potentialChallenges, spellElement, timeSlotDefined) {
	var elementLearnt = false;
	if(Array.isArray(timeSlotDefined)){
		elementLearnt = true;
	} else if(potentialChallenges.length > 0){
		$.each(potentialChallenges, function(index, value){
			if(value.element == spellElement){
				elementLearnt = true;
				return false;
			}
		});
	}
	if(potentialChallenges.length == 0 || spellElement == "base" || elementLearnt == false){ //Give basic arithmetic challenges
		//Random pick of the exercise focusing on exercises which has not been already selected
		var potentialExercises = 3; //Determine the number of possible exercises for this challenge
		var maxHistoric = 0;
		for(var i = 1;i <= potentialExercises;i++)
		{
			var idTemp = "base_0_0_" + String(i);
			if(exercisesHistoric[idTemp] != undefined && exercisesHistoric[idTemp] > maxHistoric)
			{
				maxHistoric = exercisesHistoric[idTemp];
			}
		}
		var sumProbabilities = 0;
		for(var i = 1;i <= potentialExercises;i++)
		{
			var idTemp = "base_0_0_" + String(i);
			if(exercisesHistoric[idTemp] != undefined)
			{
				sumProbabilities += Math.pow((1+maxHistoric-exercisesHistoric[idTemp]), 5);
			}
			else
			{
				sumProbabilities += Math.pow((1+maxHistoric), 5);
			}
		}
		var rand = math.randomInt(1, 101);
		var formerProbability = 0;
		var selectedExercise = 0;
		for(var i = 1;i <= potentialExercises;i++)
		{
			var idTemp = "base_0_0_" + String(i);
			if(exercisesHistoric[idTemp] != undefined)
			{
				var probability = Math.pow((1+maxHistoric-exercisesHistoric[idTemp]), 5)/sumProbabilities*100;
			}
			else
			{
				var probability = Math.pow((1+maxHistoric), 5)/sumProbabilities*100;
			}
			if(rand <= formerProbability + probability)
			{
				selectedExercise = i;
				break;
			}
			else
			{
				formerProbability += probability;
			}
		}
		//Avoid errors
		if(selectedExercise < 1)
		{
			selectedExercise = 1;
		}
		var mastery = 1;
		var challengeId = "base_0_0_" + String(selectedExercise);
	}
	else
	{
		//Extraction of all challenges of the element chosen by the player
		var elementChallenges = [];
		if(Array.isArray(timeSlotDefined)){
			$.each(potentialChallenges, function(index, value){
				if(timeSlotDefined.indexOf(value.name) != -1){
					elementChallenges.push(value);
				}
			});
		} else {
			$.each(potentialChallenges, function(index, value){
				if(value.element == spellElement){
					elementChallenges.push(value);
				}
			});
		}

		//Random pick of a challenge of the chosen element generally focusing on challenges with low level of mastery
		var sumProbabilities = 0;
		$.each(elementChallenges, function(index, value){
			sumProbabilities += 6.5-value.currentMastery;
		});
		var rand = math.randomInt(1, 101);
		var formerProbability = 0;
		var selectedChallenge = {};
		$.each(elementChallenges, function(index, value){
			var probability = (6.5-value.currentMastery)/sumProbabilities*100;
			if(rand <= formerProbability + probability)
			{
				selectedChallenge = value;
				return false;
			}
			else
			{
				formerProbability += probability;
			}
		});
		if(selectedChallenge.currentMastery == undefined)
		{
			selectedChallenge = elementChallenges[0];
		}
		var mastery = selectedChallenge.currentMastery; //For return to the view

		//Random pick of the challenge level but generally focusing on the next level of mastery
		var selectedLevel = 0;
		var levelsMastered = [];
		if(selectedChallenge.currentMastery > 5)
		{
			selectedChallenge.currentMastery = 5; //Same challenge generation for masteries 5 & 6
		}
		for(var i = 1;i <= selectedChallenge.currentMastery;i++)
		{
			levelsMastered.push(i);
		}
		var rand = math.randomInt(1, 101);
		if(levelsMastered.length == 1) //Player level 1
		{
			var probabilityNextLevel = 100; //100% chance to pick level 1
		}
		else if(levelsMastered.length == 5) //Player level 5 or 6
		{
			var probabilityNextLevel = 0; //0% chance to pick level 5 (doesn't exist)
		}
		else
		{
			var probabilityNextLevel = 70; //70% chance to pick next level
		}
		var probabilityPreviousLevels = 100-probabilityNextLevel;
		if(rand <= probabilityNextLevel)
		{
			selectedLevel = selectedChallenge.currentMastery;
		}
		else
		{
			var sumLevelsMastered = math.sum(levelsMastered);
			var formerProbability = probabilityNextLevel;
			$.each(levelsMastered, function(index, value){
				var probability = value/sumLevelsMastered*probabilityPreviousLevels;
				if(rand <= formerProbability + probability)
				{
					selectedLevel = value-1;
					return false;
				}
				else
				{
					formerProbability += probability;
				}
			});
		}
		//Avoid errors
		if(selectedLevel < 1 || selectedLevel > 4) {
			selectedLevel = 1;
		} else if(tutoFinishedToday == 1 && selectedLevel > 1) {
			selectedLevel --;
		}

		//Random pick of the exercise focusing on exercises which has not been already selected
		var potentialExercises = exercises[selectedChallenge.element][selectedChallenge.name][String(selectedLevel)]; //Determine the number of possible exercises for the level of this challenge
		var maxHistoric = 0;
		for(var i = 1;i <= potentialExercises;i++)
		{
			var idTemp = selectedChallenge.element + "_" + selectedChallenge.name + "_" + String(selectedLevel) + "_" + String(i);
			if(exercisesHistoric[idTemp] != undefined && exercisesHistoric[idTemp] > maxHistoric)
			{
				maxHistoric = exercisesHistoric[idTemp];
			}
		}
		var sumProbabilities = 0;
		for(var i = 1;i <= potentialExercises;i++)
		{
			var idTemp = selectedChallenge.element + "_" + selectedChallenge.name + "_" + String(selectedLevel) + "_" + String(i);
			if(exercisesHistoric[idTemp] != undefined)
			{
				sumProbabilities += Math.pow((1+maxHistoric-exercisesHistoric[idTemp]), 5);
			}
			else
			{
				sumProbabilities += Math.pow((1+maxHistoric), 5);
			}
		}
		var rand = math.randomInt(1, 101);
		var formerProbability = 0;
		var selectedExercise = 0;
		for(var i = 1;i <= potentialExercises;i++)
		{
			var idTemp = selectedChallenge.element + "_" + selectedChallenge.name + "_" + String(selectedLevel) + "_" + String(i);
			if(exercisesHistoric[idTemp] != undefined)
			{
				var probability = Math.pow((1+maxHistoric-exercisesHistoric[idTemp]), 5)/sumProbabilities*100;
			}
			else
			{
				var probability = Math.pow((1+maxHistoric), 5)/sumProbabilities*100;
			}
			if(rand <= formerProbability + probability)
			{
				selectedExercise = i;
				break;
			}
			else
			{
				formerProbability += probability;
			}
		}
		//Avoid errors
		if(selectedExercise < 1)
		{
			selectedExercise = 1;
		}

		//Determine the challenge id
		var challengeId = selectedChallenge.element + "_" + selectedChallenge.name + "_" + String(selectedLevel) + "_" + String(selectedExercise);
	}
	//Record the fact that this challenge has been selected in the historic
	if(exercisesHistoric[challengeId] != undefined)
	{
		exercisesHistoric[challengeId] ++;
	}
	else
	{
		exercisesHistoric[challengeId] = 1;
	}

	//Return the challenge id
	return [challengeId, mastery];
}
