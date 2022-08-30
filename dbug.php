<?php

if (! function_exists('dbug')) {
	// A simple debugging function, for logging variables regardless of type.
	function dbug() {
		$args = func_get_args();
		$out = array();
		foreach ($args as $arg) {
			if (! is_scalar($arg) ) {
				$arg = print_r($arg, true);
			}
			$out[] = $arg;
		}
		$out = implode("\n", $out);
		error_log("\n$out");
	}
}
