<?php
	require_once("require.php");
	
	function generer_formulaire_edition($nom_page, $nouveau_nom_page, $source, $nom_resultat) {
		$tags_source = array(
			"utilisateur" => auth_get_user(),
			"date" => date("r"),
			"datenbsp" => preg_replace("| |", " ", date("r")), // space -> no-break space
			"logo" => "editer.png",
			"source" => $source,
			"nom page" => $nom_page,
			"nouveau nom page" => $nouveau_nom_page
		);
		
		return compiler_page_modele("formulaire_edition.htmlf", $tags_source, $nom_resultat);
	}
	
	function editer($nom_page, $nouveau_nom_page) {
		$nom_page = desecuriser_chaine($nom_page);
		$nouveau_nom_page = desecuriser_chaine($nouveau_nom_page);
		
		if (post_has("appliquer")) {
			$source = dos2unix($_POST["source"]);
			
			$tags_contenu            = compiler_source($nouveau_nom_page, $source, "contenu");
			$tags_site               = compiler_page_modele("site.html", $tags_contenu, "contenu");
			
			// TODO : Homogénéiser les noms.
			// mettre_a_jour_cache("source compilee", $nom_page, $nouveau_nom_page, $tags_contenu);
			mettre_a_jour_cache("page", $nom_page, $nouveau_nom_page, $tags_site);
			historique_modifier_source(auth_get_user(), $nom_page, $nouveau_nom_page, $source);
			
			header("HTTP/1.0 302 Redirect");
			header("Location: " . url("page", $nouveau_nom_page));
			
			return "";
		} else if (post_has("appercu")) {
			$source       = dos2unix($_POST["source"]);
			
			$tags_formulaire_edition = generer_formulaire_edition($nom_page, $nouveau_nom_page, $source, "formulaire_edition");
			$tags_contenu            = compiler_source($nouveau_nom_page, $source, "contenu");
			$tags_appercu            = compiler_page_modele("appercu.htmlf", array_merge($tags_contenu, $tags_formulaire_edition), "contenu");
			$tags_site               = compiler_page_modele("site.html", $tags_appercu, "contenu");
			
			return $tags_site["contenu"];
		} else {
			$source                  = lire_source($nom_page, "page\n\n= $nom_page =");
			
			$tags_formulaire_edition = generer_formulaire_edition($nom_page, $nom_page, $source, "formulaire_edition");
			$tags_editer             = compiler_page_modele("editer.htmlf", $tags_formulaire_edition, "contenu");
			$tags_site               = compiler_page_modele("site.html", $tags_editer, "contenu");
			
			return $tags_site["contenu"];
		}
	}
?>
