<?php
	function get_nom_fichier_cache($type_cache, $nom_page) {
		return get_cfg("dossier cache", $type_cache) . "/" . securiser_chaine($nom_page) . ".html";
	}
	
	// TODO : Il faudrait mettre en cache tous les tags / seulement certains, selon le type de cache.
	// TODO : Pour l'instant, le cache des sources compilées n'est pas utilisé (ni utilisable).
	function mettre_a_jour_cache($type_cache, $nom_page, $nouveau_nom_page, $tags) {
		if ($nom_page != $nouveau_nom_page)
			unlink(get_nom_fichier_cache($type_cache, $nom_page)); // TODO : mettre en place une redirection
		
		if (! file_put_contents(get_nom_fichier_cache($type_cache, $nouveau_nom_page), $tags["contenu"], LOCK_EX))
			die("N'a pas pu mettre à jour le cache $type_cache pour $page");
	}
?>