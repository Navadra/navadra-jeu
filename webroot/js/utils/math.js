//Entier relatif aléatoire entre minimum et maximum
function entier_aleatoire(minimum, maximum) {
  return Math.floor(Math.random() * (maximum - minimum + 1 )) + minimum
}

//Entier relatif aléatoire entre minimum et maximum
function get_random_percent() {
  return entier_aleatoire(1, 100);
}

/**
 * If the generated percent is within threshold : returns true.
 * Ex :
 * threshold 25% and random = 35 -> false
 * threshold % and random = 3 -> true
 * @param threshold a percentage to
 * @returns {boolean}
 */
function is_within_threshold_percent(threshold) {
  return entier_aleatoire(1, 100) <= threshold;
}

//Génère un texte aléatoire parmi un array de plusieurs string
function texte_aleatoire(array) {
  var taille = array.length;
  var rand = entier_aleatoire(0, taille - 1);
  return array[rand];
}
