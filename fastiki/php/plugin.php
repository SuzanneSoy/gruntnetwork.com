<?php
	$PLUGIN = array();
	
	function enregisterPlugin($nom) {
		global $PLUGIN;
		$PLUGIN[] = $nom;
	}

	function appliquer_plugins($methode, $tags, $nom_resultat) {
		global $PLUGIN;
		
		foreach ($PLUGIN as $plg) {
			$nom_fn = $plg . "_" . $methode;
			if (function_exists($nom_fn))
				$tags = $nom_fn($tags, $nom_resultat);
		}
		
		return $tags;
	}
	
	function compiler_source($nom_page, $source, $nom_resultat) {
		preg_match("|^(.*?)\n\n(.*)$|s", $source, $matches);
		$typedoc = ($matches[1] == "") ? "page" : $matches[1];
		
		$tags = array(
			"utilisateur" => auth_get_user(),
			"date" => date("r"),
			"datenbsp" => preg_replace("| |", " ", date("r")), // space -> no-break space
			"logo" => "logo.png",
			"source" => $source,
			"nom page" => $nom_page,
			"typedoc" => $typedoc,
			$nom_resultat => $matches[2]
		);
		
		$tags = appliquer_plugins("typedoc_".$tags["typedoc"], $tags, $nom_resultat);
		$tags = appliquer_plugins("base", $tags, $nom_resultat);
		$tags = compiler_page_modele("typedoc/".$tags["typedoc"].".htmlf", $tags, $nom_resultat);
		
		return $tags;
	}
	
	function charger_plugins() {
		if (! $dossier = @opendir(get_cfg("dossier", "plugins")))
			die ("N'a pas pu ouvrir '" . get_cfg("dossier", "plugins") . "'.");
		
		while ($fichier = readdir($dossier))
			if (preg_match("/\.php$/", $fichier) != 0)
				include_once(get_cfg("dossier", "plugins") . "/" . $fichier);	
	}
?>
