<?php
	$LANGUES = array();
	
	function nouvelle_langue($langue, $langue_base = NULL) {
		global $LANGUES;
		
		if (isset($LANGUES[$langue])) {
			die ("Langue déjà définie : $langue");		
		} else {
			if (is_null($langue_base)) {
				$LANGUES[$langue] = array();
			} else {
				/* TODO : Il faut cloner le tableau, là on fait une référence seulement */
				$LANGUES[$langue] = $LANGUES[$langue_base];
			}
		}
	}
	
	function traduction($langue, $orig, $trad) {
		global $LANGUES;
		
		if (! isset($LANGUES[$langue]))
			die ("Langue inexistante : $langue");
		else if (! isset($LANGUES[$langue][$orig]))
			$LANGUES[$langue][$orig] = $trad;
	}
	
	function traduire($orig, $langue = NULL) {
		global $LANGUES;
		
		if (is_null($langue))
			$langue = get_cfg("localisation", "langue");
		
		if (! isset($LANGUES[$langue]))
			die ("Langue inexistante : $langue");
		
		return isset_in($LANGUES[$langue], $orig, $orig);
	}

	function charger_langues() {
		if (! $dossier = @opendir(get_cfg("dossier", "langues")))
			die ("N'a pas pu ouvrir '" . get_cfg("dossier", "langues") . "'.");
		
		while ($fichier = readdir($dossier))
			if (preg_match("/\.php$/", $fichier) != 0)
				include_once(get_cfg("dossier", "langues") . "/" . $fichier);	
	}
?>