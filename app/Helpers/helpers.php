<?php

if (!function_exists('csgo_getchar_by_name')) {
	function csgo_getchar_by_name($name)
	{
		$char = config("csgo-font.mapping.$name");
		if (!$char)
			info("CS:GO icon missing for name $name");

		return $char;
	}
}