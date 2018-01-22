<?php
//--------------------------------------
// D'abord, on commence par lire le fichier de configuration config.json
//--------------------------------------
define('CONFIG', 'config.json');	//définition d'une variable prenant la valeur du fichier
$data = file_get_contents(CONFIG);	//lecture des données du fichier
$data = json_decode($data);			//décodage des données .json

// S'il n'y a pas de données, on renvoie une erreur indiquant que le fichier .json n'est pas correct
if (!$data) die('Erreur de syntaxe JSON dans votre fichier "'.CONFIG.'" !');

//--------------------------------------
// Cette fonction permet d'interpréter le contenu de chaque module lu dans le fichier de configuration
//--------------------------------------
function render($module, $zindex) {
	//
	// Gestion des arguments de module
	//
	// un tableau d'arguments 'args' est-il associé au module ?
    $args = isset($module->args) ? $module->args : NULL;
    $argstr = array();
    //on récupère les arguments dans un tableau s'il y en a
    if($args != NULL) {
	    foreach($args as $key => $val) {
	        $argstr[] = "$key=" . urlencode($val);
	    }
    }
	$argstr = implode("&", $argstr);
    
    //
    // Gestion des données css associées au module
    //
    // Taille :
    $style = isset($module->width) ? "width: {$module->width};" : NULL;		//largeur du module
    $style .= isset($module->height) ? "height: {$module->height};" : NULL;	//hauteur du module
    // Placement :
    $style .= isset($module->top) ? "top: {$module->top};" : NULL;				//placement haut du module
    $style .= isset($module->left) ? "left: {$module->left};" : NULL;			//placement gauche du module
    // Classe (pas encore utilisé) :
    $class = "module";
    $class .= isset($module->class) ? " ".$module->class : '';
    // Gestion des z-index : fonction de l'ordre des modules dans le fichier .json par défaut
    $style .= "z-index: ".$zindex.";";
    
    return "<div class='$class' id='$module->name' style='$style'>
    <script type='text/javascript'>activate_module('$module->name', $module->update, '$argstr');</script>
    </div>";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" xml:lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <!-- on récupère le titre de la page dans le fichier .json, ou on lui donne une valeur fixe -->
    <title><?php echo (isset($data->title) ? $data->title : 'Tableau de bord-RPi') ?></title>
    <!-- on importe une feuille de style permettant de réduire les incohérences entre navigateurs -->
    <link rel='stylesheet' type='text/css' href='ressources/reset.css' />
    <!-- on importe la feuille de style générale au tableau de bord -->
    <link rel='stylesheet' type='text/css' href='ressources/style.css' />
    <!-- on récupère la taille du fond du tableau de bord dans le fichier .json, ou on la fixe à 100% -->
    <!-- on regarde de même s'il faut lier une image d'arrière plan ou non -->
    <style type='text/css'>
        #fond {
        	width: <?php echo isset($data->width) ? $data->width : '100%' ?>;
        	height: <?php echo isset($data->height) ? $data->height : '100%' ?>;
        	background: <?php echo isset($data->fond) ? 'transparent url("ressources/'.$data->fond.'") no-repeat top left' : 'black' ?>;
        }
    </style>
    <!-- on récupère enfin les éventuelles feuilles de style de chaque module -->
    <?php
        foreach($data->modules as $module) {
            $filename = "./modules/";
            $filename .= $module->name."/".$module->name.".css";
            if (file_exists($filename)) echo "<link rel='stylesheet' type='text/css' href='$filename'/>";
        }
    ?>
    <script type='text/javascript' src='ressources/jquery.js'></script>
    <!-- on récupère le fichier javascript qui va gérer l'actualisation de chaque module -->
    <script type='text/javascript' src='ressources/javascript.js'></script>
</head>
<body>
	<!-- Division générale du tableau de bord -->
    <div id='fond'>
    	<!-- Division propre à chaque module présent dans le fichier .json -->
        <?php
        $i = 0;								//compteur pour z-index
        foreach($data->modules as $module)
        {
            $i++;
            echo render($module, $i);
        }
        ?>
    </div>
</body>
</html>