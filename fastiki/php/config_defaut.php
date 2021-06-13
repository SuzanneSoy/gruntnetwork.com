<?php
	cfg("localisation", "langue", "fr");
	
	/* Dossiers */
	cfg("dossier", "base",        "../..");
	cfg("dossier", "source page", get_cfg("dossier", "base") . "/source_pages");
	cfg("dossier", "modeles",     get_cfg("dossier", "base") . "/fastiki/modeles");
	cfg("dossier", "plugins",     get_cfg("dossier", "base") . "/fastiki/plugins");
	cfg("dossier", "langues",     get_cfg("dossier", "base") . "/fastiki/langues");
	cfg("dossier", "historique",  get_cfg("dossier", "base") . "/historique");
	cfg("dossier", "historique",  get_cfg("dossier", "base") . "/historique");
	
	cfg("fichier", "log",         get_cfg("dossier", "base") . "/historique/log.txt");

/*	cfg("dossier", "page",        get_cfg("dossier", "base") . "/pages");
	cfg("dossier", "cache",       get_cfg("dossier", "base") . "/cache");
	cfg("dossier", "historique",  get_cfg("dossier", "base") . "/historique");
	cfg("dossier", "langue",      get_cfg("dossier", "base") . "/langues");
	cfg("dossier", "php",         get_cfg("dossier", "base") . "/php");
	cfg("dossier", "utilisateur", get_cfg("dossier", "base") . "/utilisateurs"); */
	
	/* Dossiers de cache */
//	cfg("dossier cache", "source compilee", get_cfg("dossier", "cache") . "/source_compilee");
	cfg("dossier cache", "page",            get_cfg("dossier", "base") . "/site/page");
//	cfg("dossier cache", "historique",      get_cfg("dossier", "cache") . "/historique");
//	cfg("dossier cache", "diff",            get_cfg("dossier", "cache") . "/diff");
	
	/* Mise en cache */
	cfg("cache", "source compilee", TRUE);
	cfg("cache", "page",            TRUE);
	cfg("cache", "historique",      FALSE);
	cfg("cache", "diff",            FALSE);
	
	/* URLs */
	cfg("url", "base",    "http://www.example.com/site/");
	cfg("url", "accueil", "ne:" . get_cfg("url", "base") . "page/Accueil.html");
	cfg("url", "licence", "ne:" . get_cfg("url", "base") . "page/Licence.html");
	cfg("url", "editer",  "ue:" . get_cfg("url", "base") . "editer/?page={nom}");
	cfg("url", "page",    "ne:" . get_cfg("url", "base") . "page/{nom}.html");
	cfg("url", "style",   "ne:" . get_cfg("url", "base") . "style/{nom}");
	cfg("url", "image",   "ne:" . get_cfg("url", "base") . "image/{nom}");
?>
