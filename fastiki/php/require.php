<?php
	require_once("securite.php");
	require_once("configuration.php");
	require_once("fonctions.php");
	require_once("fichiers.php");
	require_once("cache.php");
	require_once("modele.php");
	require_once("plugin.php");
	require_once("historique.php");
	require_once("authentification.php");
	require_once("langues.php");
	
	charger_config();
	charger_plugins();
	charger_langues();
?>