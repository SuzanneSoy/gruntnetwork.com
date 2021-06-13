<?php
	/* SECURITE */
	function securiser_chaine($chaine) {
		/* Ce serait plus simple d'utiliser urlencode, mais les / seraient échappés (pas pratique pour taches/...). */
		$chaine = preg_replace("|-|", "-2d", $chaine);
		$chaine = preg_replace("|^/*|e", "str_repeat('-2f', strlen('\\0'))", $chaine);
		$chaine = preg_replace("|\.\./|", "-2e-2e/", $chaine);
		$chaine = preg_replace("|~|", "-7e", $chaine);
		$chaine = preg_replace("|\\000|", "-00", $chaine);
		$chaine = preg_replace("|\n|", "-0a", $chaine); // TODO : Verifier !!!
		$chaine = preg_replace("|^$|", "-vide", $chaine);
		
		return $chaine;
	}
	
	function securiser_chaine_espaces($chaine) {
		$chaine = securiser_chaine($chaine);
		$chaine = preg_replace("| |", "-20", $chaine);
		return $chaine;
	}
	
	function desecuriser_chaine_espaces($chaine) {
		$chaine = preg_replace("|-20|", " ", $chaine);
		$chaine = securiser_chaine($chaine);
		return $chaine;
	}
	
	function securiser_chaine_slashes($chaine) {
		$chaine = securiser_chaine($chaine);
		$chaine = preg_replace("|/|", "-2f", $chaine);
		return $chaine;
	}
	
	function desecuriser_chaine_slashes($chaine) {
		$chaine = preg_replace("|-2f|", "/", $chaine);
		$chaine = securiser_chaine($chaine);
		return $chaine;
	}
	
	/* SECURITE */
	function desecuriser_chaine($chaine) {
		$chaine = preg_replace("|^-vide$|", "", $chaine);
		$chaine = preg_replace("|-0a|", "\n", $chaine); // TODO : Verifier !!!
		$chaine = preg_replace("|-00|", "\\000", $chaine);
		$chaine = preg_replace("|-7e|", "~", $chaine);
		$chaine = preg_replace("|-2e-2e/|", "../", $chaine);
		$chaine = preg_replace("|-2f|", "/", $chaine);
		$chaine = preg_replace("|-2d|", "-", $chaine);
		return $chaine;
	}
?>