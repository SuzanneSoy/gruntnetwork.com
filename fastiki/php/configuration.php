<?php
	$CONFIG = array();
	
	function cfg($cat, $var, $val) {
		global $CONFIG;
		
		if (! has_cfg_cat($cat))
			$CONFIG[$cat] = array();
		
		if (! has_cfg($cat, $var))
			$CONFIG[$cat][$var] = $val;
	}
	
	function get_cfg($cat, $var, $alt = NULL) {
		global $CONFIG;
		
		if (has_cfg($cat, $var))
			return $CONFIG[$cat][$var];
		else if (!is_null($alt))
			return $alt;
		else
			die("La variable de configuration n'est pas définie : $cat : $var");
	}
	
	function has_cfg($cat, $var) {
		global $CONFIG;
		
		return has_cfg_cat($cat) && isset($CONFIG[$cat][$var]);
	}
	
	function has_cfg_cat($cat) {
		global $CONFIG;
		
		return isset($CONFIG[$cat]);
	}
	
	function charger_config() {
		include_once("../../config.php");
		require_once("config_defaut.php");
	}
?>