<!-- Ce module affiche l'heure et la date courante -->
<?php

	// Récupération des arguments
	if(!empty($_GET)){
		echo '<style type="text/css">';
		// Taille de l'heure
		if(!empty($_GET['taille_heure'])){
			echo '#horloge_heure { font-size: '.$_GET['taille_heure'].'; }';
		}
		// Taille de la date
		if(!empty($_GET['taille_date'])){
			echo '#horloge_date { font-size: '.$_GET['taille_date'].'; }';
		}
		echo '</style>';
	}
	
	// réglage pour être en français
	// setlocale(LC_TIME, 'fr_FR.UTF8');
	setlocale(LC_TIME, "fr_FR", "French");

?>

<!-- Affichage de l'heure -->
<div id='horloge_heure'>
	<?php
		// Heures
		echo strftime('%H');
		// Séparateur clignotant
		if(strftime('%S') % 2 == 0) {echo "<span class='horloge_grey'>:</span>";} else {echo ":";}
		// Minutes
		echo strftime('%M');
	?>
</div>

<!-- Affichage de la date -->
<div id='horloge_date'>
	<!-- Jour de la semaine, en entier -->
	<span class='horloge_grey'><?php echo strftime('%A'); ?></span>
	<!-- N° du jour -->
	<?php echo strftime('%d'); ?>
	<!-- Mois, en entier -->
	<span class='horloge_grey'><?php echo strftime('%B'); ?></span>
	<!-- Année sur 4 chiffres -->
	<?php echo strftime('%Y'); ?>
</div>