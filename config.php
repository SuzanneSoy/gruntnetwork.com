<?php
	cfg("localisation", "langue",  "fr");
	
	cfg("url", "base",    "http://gruntnetwork.com/");
	cfg("url", "accueil", get_cfg("url", "base") . "Accueil.html");
	cfg("url", "licence", get_cfg("url", "base") . "Licence.html");
	cfg("url", "page",    "ne:" . get_cfg("url", "base") . "{nom}.html");
?>
