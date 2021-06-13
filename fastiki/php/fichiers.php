<?php
	function lire_source($nom_page, $si_absent = NULL, $version = NULL) {
		return dos2unix(lire_fichier(get_nom_fichier_source($nom_page, $version), $si_absent));
	}
	
	function lire_fichier($nom_fichier, $si_absent = NULL) {
		if (! file_exists($nom_fichier) && !is_null($si_absent))
			return $si_absent;
		
		if (! $fichier = @fopen($nom_fichier, "r"))
			die ("N'a pas pu ouvrir '$nom_fichier' en lecture.");
		
		$donnees = fread ($fichier, filesize($nom_fichier));
		fclose($fichier);
		
		return $donnees;
	}
	
	function ecrire_fichier($nom_fichier, $donnees) {
		if (! $fichier = @fopen($nom_fichier, "w"))
			die ("N'a pas pu ouvrir '$nom_fichier' en écriture.");
		
		if ($donnees != "")
			if (fwrite($fichier, $donnees) == FALSE)
				die ("N'a pas pu écrire dans '$nom_fichier'.");
		
		fclose($fichier);
	}
	
	function mkdir_if_not_exists($nom_dossier) {
		if (! file_exists($nom_dossier))
			if (! mkdir($nom_dossier, 0777, true))
				die ("N'a pas pu créer le dossier '$nom_dossier'.");
	}
?>