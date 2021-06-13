<?php
	function langue_base($tags, $nom_resultat) {
		$donnees = $tags[$nom_resultat];
		
		$donnees = preg_replace("/{{{lang:(.+)}}}/Ue",
			"traduire('\\1')",
			$donnees);
		
		$tags[$nom_resultat] = $donnees;
		return $tags;
	}
	
	function langue_pre_modele($tags, $nom_resultat) {
		return langue_base($tags, $nom_resultat);
	}
	
	enregisterPlugin("langue");
?>