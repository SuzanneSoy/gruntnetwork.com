<?php
	function url($type, $nom) {
		preg_match("|^([^:]*):(.*)$|", get_cfg("url", $type), $modele);
		
		$nom = securiser_chaine($nom);
		if ($modele[1] == "ue")
			$nom = urlencode($nom);
		
		return htmlentities(preg_replace("/{nom}/", $nom, $modele[2]), ENT_QUOTES, "UTF-8");
	}
	
	function url_base($tags, $nom_resultat) {
		$donnees = $tags[$nom_resultat];
		
		$donnees = preg_replace("/{{{url:(.+):(.*):}}}/Ue",
			"url('\\1', replacevars('\\2', \$tags))",
			$donnees);
		
		$tags[$nom_resultat] = $donnees;
		return $tags;
	}
	
	function url_pre_modele($tags, $nom_resultat) {
		return url_base($tags, $nom_resultat);
	}
	
	enregisterPlugin("url");
?>