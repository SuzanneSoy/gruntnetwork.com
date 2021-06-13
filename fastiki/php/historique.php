<?php
	function historique_get_current_version($nom_page) {
		$nom_fichier_version = get_nom_fichier_version($nom_page);
		
		if (! file_exists($nom_fichier_version))
			return -1;
		
		if (! $fichier_version = @fopen($nom_fichier_version, "r"))
			die ("N'a pas pu ouvrir '$nom_fichier_version' en lecture.");
		if (! flock($fichier_version, LOCK_SH))
			die ("N'a pas pu vérouiller '$nom_fichier_version'.");
		
		$version = fread($fichier_version, filesize($nom_fichier_version));
		flock($fichier_version, LOCK_UN);
		
		$version = explode("\n", $version);
		$version = $version[0];
		if (is_numeric($version)) {
			$version = intval($version);
		} else {
			$version = -1;
		}
		
		return $version;
	}
	
	function historique_nouvelle_version($nom_page) {
		$dossier_hist = get_dossier_hist($nom_page);
		mkdir_if_not_exists($dossier_hist);
		
		/* Ouverture et verrouillage du compteur de versions */
		$nom_fichier_version = get_nom_fichier_version($nom_page);
		touch($nom_fichier_version); // Pour être sûr que le fichier existe déjà.
		if (! $fichier_version = @fopen($nom_fichier_version, "r+"))
			die ("N'a pas pu ouvrir '$nom_fichier_version' en ajout/écriture.");
		if (! flock($fichier_version, LOCK_EX))
			die ("N'a pas pu vérouiller '$nom_fichier_version'.");
		
		/* Lecture et incrémentation du compteur de versions */
		$version = "";
		if (($filesz = filesize($nom_fichier_version)) != 0)
			$version = fread($fichier_version, $filesz);
		$version = explode("\n", $version);
		$version = $version[0];
		if (is_numeric($version)) {
			$version = intval($version) + 1;
		} else {
			$version = 0;
		}
		
		/* Écriture du compteur de versions */
		if (! ftruncate($fichier_version, 0))
			die ("N'a pas pu tronquer '$nom_fichier_version'.");
		if (! rewind($fichier_version))
			die ("N'a pas pu rembobiner '$nom_fichier_version'.");
		if (! fwrite($fichier_version, $version))
			die ("N'a pas pu écrire dans '$nom_fichier_version'.");
		
		/* Déverrouillage et fermeture */
		flock($fichier_version, LOCK_UN);
		fclose($fichier_version);
		
		mkdir(get_dossier_hist_version($nom_page, $version), 0777, true);
		
		return $version;
	}
	
	function get_dossier_hist($nom_page) {
		return get_cfg("dossier", "historique") . "/page/" . securiser_chaine_slashes($nom_page);
	}
	
	function get_dossier_hist_version($nom_page, $version = NULL) {
		if (is_null($version))
			$version = historique_get_current_version($nom_page);
		return get_dossier_hist($nom_page) . "/" . $version;
	}
	
	function get_nom_fichier_version($nom_page) {
		return get_dossier_hist($nom_page) . "/version.txt";
	}
	
	function get_nom_fichier_source($nom_page, $version = NULL) {
		return get_dossier_hist_version($nom_page, $version) . "/source.txt";
	}
	
	function get_nom_fichier_log($nom_page, $version = NULL) {
		return get_dossier_hist_version($nom_page, $version) . "/log.txt";
	}
	
	function page_existe($nom_page, $version = NULL) {
		return file_exists(get_nom_fichier_source);
	}
	
	
	function ecrire_log($temps, $nom_utilisateur, $action, $nom_page, $version, $depuis_page, $depuis_version) {
		$donnees = securiser_chaine_espaces($temps)
			. " " . securiser_chaine_espaces($nom_utilisateur)
			. " " . securiser_chaine_espaces($action)
			. " " . securiser_chaine_espaces($nom_page)
			. " " . securiser_chaine_espaces($version)
			. " " . securiser_chaine_espaces($depuis_page)
			. " " . securiser_chaine_espaces($depuis_version)
			. "\n";
		
		if (! file_put_contents(get_nom_fichier_log($nom_page, $version), $donnees, FILE_APPEND | LOCK_EX))
			die("Impossible de mettre à jour le log");
		
		if (! file_put_contents(get_cfg("fichier", "log"), $donnees, FILE_APPEND | LOCK_EX))
			die("Impossible de mettre à jour le log général");
	}
	
	
	
	function historique_modifier_source($nom_utilisateur, $nom_page, $nouveau_nom_page, $source) {
		if ($nom_page != $nouveau_nom_page) {
			historique_renommer_page($nom_utilisateur, $nom_page, $nouveau_nom_page, $source);
		} else if (! page_existe($nom_page)) {
			historique_creer_page($nom_utilisateur, $nom_page, $source);
		} else {
			$version = historique_nouvelle_version($nom_page);
			ecrire_fichier(get_nom_fichier_source($nom_page, $version), $source);
			ecrire_log(time(), $nom_utilisateur, "Modifier", $nom_page, $version, "", "");
		}
	}
	
	function historique_renommer_page($nom_utilisateur, $depuis_page, $nom_page, $source = NULL) {
		$version_depuis = historique_nouvelle_version($depuis_page);
		$version = historique_nouvelle_version($nom_page);
		
		if (is_null($source))
			$source = lire_source($depuis_page, "", $depuis_version);
		
		ecrire_fichier(get_nom_fichier_source($nom_page, $version), $source);
		
		$t = time();
		ecrire_log($t, $nom_utilisateur, "RenomerDepuis", $nom_page, $version, $depuis_page, $version_depuis - 1);
		ecrire_log($t, $nom_utilisateur, "RenomerVers", $depuis_page, $version_depuis, $nom_page, $version);
		
		return $version;
	}
	
	function historique_creer_page($nom_utilisateur, $nom_page, $source = "") {
		$version = historique_nouvelle_version($nom_page);
		
		ecrire_fichier(get_nom_fichier_source($nom_page, $version), $source);
		ecrire_log(time(), $nom_utilisateur, "Creer", $nom_page, $version, "", "");
		
		return $version;
	}
	
	function historique_supprimer_page($nom_utilisateur, $nom_page) {
	}
?>
