<?php
	function isset_in($tab, $var, $alt = NULL, $callback = NULL, $callback_arg = NULL) {
		if (isset($tab[$var])) {
			if (function_exists($callback)) {
				return $callback($tab[$var], $callback_arg);
			} else {
				return $tab[$var];
			}
		} else {
			if (is_null($alt)) {
				return "&lt;NON TROUVÉ : $var&gt;";
			} else {
				return $alt;
			}
		}
	}
	
	function post_has($champ) {
		return array_key_exists($champ, $_POST);
	}
	
	function dos2unix($donnees) {
		$donnees = preg_replace("/\\015\\012/", "\012", $donnees);
		$donnees = preg_replace("/\\015/", "\012", $donnees);
		return $donnees;
	}
	
	function unquote($donnees) {
		return preg_replace('/\\\\"/', '"', $donnees);
	}
	
	function __replacevars($val) {
		return htmlentities($val, ENT_QUOTES, "UTF-8");
	}
	
	function _replacevars($var, $vars) {
		if (preg_match("|^he:|", $var))
			return isset_in($vars, preg_replace("|^he:|", "", $var), NULL, "__replacevars");
		else
			return isset_in($vars, $var);
	}
	
	function replacevars($donnees, $vars) {
		return preg_replace("/\\\${([^{}]+)}/e",
			"_replacevars(unquote('\\1'), \$vars)",
			$donnees);
	}
	
	function subarrays($a, $index) {
		foreach ($a as $k => $v)
			$a[$k] = $a[$k][$index];
		return $a;
	}
	
	function preg_find_delete($regexp, &$donnees, $match = NULL, $submatch = NULL, $limit = -1) {
		preg_match_all($regexp, $donnees, $matches);
		if (!is_null($match))
			$matches = subarrays($matches, $match);
		if (!is_null($submatch))
			$matches = $matches[$submatch];
		
		$donnees = preg_replace($regexp, "", $donnees, $limit);
		return $matches;
	}
?>