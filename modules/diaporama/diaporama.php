
<script type='text/javascript' src='./modules/diaporama/diaporama.js'></script>

<?php

function resol_largeur()
{
$resol_largeur='<script type="text/javascript">
               document.write(""+screen.width+"");
</script>';
return $resol_largeur;
}

//--------------------------------
// DIAPORAMA
// Il s'agit ici d'afficher des images toutes les x secondes.
//--------------------------------

	
// Récupération du répertoire de stockage des photos
if(!empty($_GET['dossier'])) {
	$dir = $_GET['dossier'];
} else {
	$dir = './img/';
}
	
// Récupération du délai d'affichage de chaque photo
if(!empty($_GET['delai'])) {
	$delai = $_GET['delai'];
} else {
	$delai = 5000; // 5 secondes par défaut
}

// Récupération de la taille de l'écran
$largeur=1920;
//$largeur=resol_largeur();
//echo "'.$largeur.'";

// liste des extensions images affichables
$types = '*.{gif,jpg,jpeg,JPG,png}';

// récupération des fichiers associés dans un tableau
// ne pas oublier GLOB_BRACE qui permet de lister plusieurs patterns de recherche !
$diapo=glob($dir.$types, GLOB_BRACE);
// Mise à jour des chemins d'accès relatifs
$diapo=str_replace("./", "./modules/diaporama/", $diapo);
// si on veut trier le tableau dans l'ordre naturel
// = 10 après 2 et pas avant !
//usort($diapo, "strnatcmp");

// on compte les images à afficher
$nb=count($diapo);

// affichage de la 1ère image
$img=0;
//echo '<img src="'.$diapo[0].'" width="'.$largeur.'" height="" alt="diaporama" />';
echo '<img src="'.$diapo[0].'" width="'.$largeur.'" height="" alt="" />';

// Div vide permettant d'afficher l'effet gloss au dessus de l'img
echo '<div id="gloss"></div>';

// liste de toutes les images, s'il y en a plus d'une
if($nb > 1) {
	echo '<ul style="display:none">';
	while ($img < $nb) {
	    echo '<li>'.$diapo[$img].'</li>';
	    $img++;
	}
	echo '</ul>';
	
	// et execution du script gérant l'affichage du diaporama
	echo "<script type='text/javascript'>
	    window.monDiaporama = diaporama(".$delai.");
	</script>";
}

?>
