<?php
	require_once("../../fastiki/php/require.php");
	require_once("../../fastiki/php/editer.php");
	
	$nom_article = isset_in($_GET, "page", "Nouvelle page");
	$nouveau_nom_article = isset_in($_POST, "titre", $nom_article);
	
	echo editer($nom_article, $nouveau_nom_article);
?>