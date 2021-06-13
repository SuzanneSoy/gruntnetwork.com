<?php
	function tache_formater_tache_inline($donnees) {
		return '</p><div class="tache">'
			. '<img alt="tâche" src="{{{url:image:tache.png:}}}" class="logo-tache" />'
			. '<p>'
			. $donnees
			. '</p></div><p>';
	}
	
	function tache_base($tags, $nom_resultat) {
		$donnees = $tags[$nom_resultat];
		
		$donnees = preg_replace('/{{{TACHE:(.*)}}}/Ume',
			"'<a href=\"' . url_page(unquote('taches/\\1')) . unquote('\">$1</a>')",
			$donnees);
		
		$donnees = preg_replace('/<tache>(.*)<\/tache>/Use',
			"taches_formater_tache_inline(unquote('$1'))",
			$donnees);
		
		$donnees = preg_replace('/<p>\s*<\/p>/m', '', $donnees);
		
		$tags[$nom_resultat] = $donnees;
		return $tags;
	}
	
	function tache_typedoc_tache($tags, $nom_resultat) {
		$donnees = $tags[$nom_resultat];
		
		$tags["logo"] = "tache.png";
		$tags["niveau entetes"] = 1;
		
		$tags["titre"] = preg_find_delete("|^=\s*(.*?)\s*=$|m", $donnees, 0, 1, 1);
		
		$dependances = preg_find_delete("|^<depends>(.*?)</depends>$|m", $donnees, NULL, 1);
		$dependances = preg_replace("|^.*$|e", "'<a href=\"' . url('page', unquote('$0')) . unquote('\">$0</a>')", $dependances);
		$tags["dependances"] = implode(", ", $dependances);
		
		$tags[$nom_resultat] = $donnees;
		return $tags;
	}
	
	enregisterPlugin("tache");
?>