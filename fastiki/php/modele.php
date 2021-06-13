<?php
	require_once("require.php");
	
	function compiler_page_modele($nom_modele, $tags, $nom_resultat) {
		$donnees = lire_fichier(get_cfg("dossier", "modeles") . "/" . securiser_chaine($nom_modele));
		$tags_modele = $tags;
		$tags_modele[$nom_resultat] = $donnees;
		
		//echo $donnees;
		$tags_modele = appliquer_plugins("pre_modele", $tags_modele, $nom_resultat);
		
		//echo $tags_modele[$nom_resultat];
		$donnees = replacevars($tags_modele[$nom_resultat], $tags);
		$tags_modele[$nom_resultat] = $donnees;
		
		appliquer_plugins("post_modele", $tags_modele, $nom_resultat);
		
		return $tags_modele;
	}
?>