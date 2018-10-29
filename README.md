# navadra-jeu

##################
## Windows
Téléchargez WAMP 
https://sourceforge.net/projects/wampserver/files/latest/download

Et l'installer sur votre serveur.

ATTENTION : 
- N'installez pas Wampserver PAR-DESSUS une version existante
- Installez Wampserver dans un dossier à la racine d'un disque
- Fermer Skype ou ne pas laisser ouvert le port 80
- Désactiver Microsoft IIS s'il est activé

Répertoire d'installation conseillé :
c:\wamp64

Lire attentivement l'image 12

---- 
Lancer wamp

Ouvrir le répertoire C:\wamp64\www et cloner le repository git dedans (ou extraire l'archive dedans)

Créer la base de données
Allez sur : http://localhost/phpmyadmin/
Utilisateur  : root
Mot de passe : 

Cliquez sur "Nouvelle base de données"
Nom : navadra (en minuscule !!)
utf8_general_ci

Cliquez sur "créer"
Puis importez NAVADRA_INIT.sql



!!!!! Check controller_global.php URL de base !joueurs
!!!!! Liste des collèges

##################
## Linux
