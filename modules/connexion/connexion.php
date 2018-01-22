<?php

//--------------------------------
// CONNEXION
// Il s'agit ici de tester l'état de la connexion à internet en 2 phases.
//--------------------------------

//--------------------------------
// Fonctions pour le test de vitesse de la connexion
// = simplification du script de http://test.haydont.net/
//--------------------------------
function testbw($koofdata)
{
	// On enregistre le temps avant le test, en secondes.millisecondes
	// NB : le paramètre "true" permet de récupérer un nombre en virgule flottante et non une chaîne de caractères
	$t1=microtime(true);
	
	// on fait le test = lecture des données d'un fichier de X Ko
	echo "<!--";
	// Version d'origine : téléchargement en local :
	//include("./".$koofdata."K.inc");
	// Version modifiée : téléchargement d'un fichier sur le serveur de test de free.fr
	file_get_contents("http://test-debit.free.fr/".$koofdata.".rnd");
	echo "-->";
	
	// on enregistre le temps après le test
	$t2=microtime(true);
	
	// on calcule le débit en Ko/s = Nb de Ko lus / (temps après test - temps avant test)
	$kbps = $koofdata/($t2 - $t1);
	// on le converti en Mb/s, pour être conforme aux valeurs des autres outils de test
	$kbps = $kbps*8/1000;
	return($kbps);
}


function speedtest()
{
	// série de trois tests de 512Ko
	// NB : des fichiers de 512 à 1 048 576 (1Go !) sont disponibles sur le serveur de test de Free
	// plus le fichier est gros, plus le résultat est précis... mais plus c'est long !
	$results = testbw(512);
	$resultsa = testbw(1024);
	$resultsb = testbw(512);
		
	// selection des meilleurs résultats
	if ($results < $resultsa) $results = $resultsa;
	if ($results < $resultsb) $results = $resultsb;
	
	return($results);
}


	//----------------------------
	// 1. vérification du raccordement à la box côté utilisateur
	//    et à un site web de référence (google.com par défaut) côté internet
	//----------------------------
	
	// Récupération des arguments du module s'il y a
	// 1. La box
	if(!empty($_GET['ip_box'])) {
		$box = $_GET['ip_box'];
	} else {
		$box = '192.168.44.254';	//valeur de la plupart des box internet par défaut
	}
	// 2. Internet
	if(!empty($_GET['ip_internet'])) {
		$internet = $_GET['ip_internet'];
	} else {
		$internet = 'www.google.com';	//site de google.com par défaut
		//$internet = '216.58.212.196';	//adresse IP de google.com, car avec le nom de domaine,
										//ça marche, même sans connexion internet...
	}
	// 3. La vitesse de l'abonnement
	if(!empty($_GET['abo_Mbs'])) {
		$abo = $_GET['abo_Mbs'];
	} else {
		$abo = 8;	//valeur par défaut de 8Mb/s
	}
	
	// Tentative d'ouverture du port 80 sur la box pendant 3 secondes
	$socket = 0;
	
	//$socket = @fsockopen($box, '80', $errno, $errstr, 1);
	// Si OK...
	//if($socket && !$errno) {
	if($socket == 0) {
		$box = 'up';
		// ...on teste aussi internet
		$socket = 0;
		$socket = @fsockopen($internet, '80', $errno, $errstr, 3);
		// Si OK...
		if($socket && !$errno) {
			//----------------------------
			// 2. mesure de la vitesse de connexion en mesurant le temps de téléchargement d'un fichier type
			//    et mise en forme selon résultat
			//----------------------------
			$vitesse=speedtest();
			// cas d'une vitesse > abonnement = ligne OK, mais pas de synchro Adsl
			if($vitesse > $abo) {
				$internet = 'sync';
			}
			else {
				// Sinon, internet est OK
				$internet = 'up';
				// On classe en fonction de la vitesse
				// "High" si supérieur à la moitié de l'abonnement
				if($vitesse > ($abo / 2)){
					$class='hi';
				}
				// "Medium" si supérieur au quart de l'abonnement
				elseif($vitesse > ($abo / 4)){
					$class = 'mid';
				}
				// "Low" sinon
				else {
					$class='lo';
				}
				// Affichage de la vitesse
				echo '<div id="speedtest">';
				echo '<div id="speedtest_'.$class.'">'.round($vitesse, 1).' Mb/s</div>';
				echo '</div>';
			}
			// Fermeture du port
			fclose($socket);
		// Sinon, on ne teste pas la vitesse de connexion
		}
		else {
			$internet = 'down';
		}
	// Sinon, tout est 'down' et on ne teste pas la vitesse de connexion
	} else {
		$box = 'down';
		$internet = 'down';
	}
	
	// On affiche les résultats
	echo '<div id="box_'.$box.'"><img src="modules/connexion/box_'.$box.'.png"></div>';
	echo '<div id="internet_'.$internet.'"><img src="modules/connexion/internet_'.$internet.'.png"></div>';
	
