<?php
	function wiki_formater_liste($donnees) {
		$donnees = preg_split('/\n/', $donnees);
		$resultat = '';
		$niveau_precedent = 0;
		
		foreach ($donnees as $ligne) {
			$matches = array();
			preg_match('/^(\s*\*\s*)+/', $ligne, $matches);
			$niveau = count(explode("*", $matches[0])) - 1;
			$ligne = preg_replace('/^(\s*\*\s*)+/', "", $ligne);
			
			if ($niveau > $niveau_precedent) {
				$resultat .= str_repeat("<ul>\n<li>\n", $niveau - $niveau_precedent);
				$resultat .= $ligne . "\n";
			} else {
				$resultat .= str_repeat("</li>\n</ul>\n", $niveau_precedent - $niveau);
				if ($niveau > 0 && $ligne != "")
					$resultat .= "</li>\n<li>\n$ligne\n";
			}
			
			$niveau_precedent = $niveau;
		}
		
		$resultat .= str_repeat("</li>\n</ul>\n", $niveau_precedent);
		return $resultat;
	}
	
	function wiki_formater_lien($lien_externe, $lien) {
		preg_match("/^([^#|]*)#?([^|]*)\|?(.*)$/", $lien, $matches);
		$nom_page = $matches[1];
		$nom_ancre = $matches[2];
		$texte_lien = $matches[3];
		
		if ($nom_page == "" && $nom_ancre == "")
			return $texte_lien;
		
		if ($texte_lien == "") {
			if ($nom_page != "")
				$texte_lien = $nom_page;
			else
				$texte_lien = $nom_ancre;
		}
		
		if ($nom_page != "") {
			if ($lien_externe == "") {
				$url_lien = url("page", $nom_page);
			} else {
				$url_lien = htmlentities($nom_page, ENT_QUOTES, "UTF-8");
			}
		}
		
		if ($nom_ancre != "")
			$url_lien .= "#" . htmlentities(urlencode($nom_ancre), ENT_QUOTES, "UTF-8");
		
		return "<a href=\"" . $url_lien . "\">" . $texte_lien . "</a>";
	}
	
	function wiki_base($tags, $nom_resultat) {
		$donnees = $tags[$nom_resultat];
		$tags["niveau entetes"] = ($tags["niveau entetes"] != "") ? $tags["niveau entetes"] : 0;
		$niveau_entetes = $tags["niveau entetes"];
		
		/* === Titres === */
		$donnees = preg_replace('/^(={1,6})\s*(.*?)\s*\1$/me',
			'"\n</p>\n<h" . min(6, $niveau_entetes + strlen(\'$1\')) . \'>$2</h\' . strlen(\'$1\') . ">\n<p>"',
			$donnees);
		
		/* '''Emphases''' */
		$donnees = preg_replace("/'''''(.*)'''''/Um", '<strong><em>$1</em></strong>', $donnees); /* À éviter */
		$donnees = preg_replace("/'''(.*)'''/Um", '<strong>$1</strong>', $donnees);
		$donnees = preg_replace("/''(.*)''/Um", '<em>$1</em>', $donnees);
		
		/* [Liens internes] et [[liens externes]] */
		// TODO : capture : [xyz]], le 2e ] ne devrait être capturé que s'il y en a deux au début.
		$donnees = preg_replace('/\[(\[?)([^]]*?)\]\]?/me', "wiki_formater_lien('$1', unquote('$2'))", $donnees);
		
		/* * Listes */
		$donnees = preg_replace('/^([ \t]*\*[ \t*]+.*\n?)+/me',
			"'\n</p>\n' . wiki_formater_liste(unquote('$0')) . '<p>\n'",
			$donnees);
				
		/* Paragraphes (les lignes vides séparent les paragraphes) */
		$donnees = preg_replace('/^/', "<p>\n", $donnees);
		$donnees = preg_replace('/$/D', "\n</p>", $donnees);
		$donnees = preg_replace('/^\n/m', "\n</p>\n<p>\n", $donnees);
		$donnees = preg_replace('/<p>\s*<\/p>/m', '', $donnees);
		
		/* Nettoyage du code xhtml */
		$donnees = preg_replace('/^\n/m', '', $donnees);
		$donnees = preg_replace('/(<p>)([^\n])/m', "$1\n$2", $donnees);
		
		$tags[$nom_resultat] = $donnees;
		return $tags;
	}
	
	enregisterPlugin("wiki");
?>