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

if (!function_exists('steamid')) {
	function steamid($steamid)
	{
		return new \SteamID($steamid);
	}
}

if (!function_exists('steamid2')) {
	function steamid2($id)
	{
		return steamid($id)->RenderSteam2();
	}
}

if (!function_exists('steamid64')) {
	function steamid64($id)
	{
		return steamid($id)->ConvertToUInt64();
	}
}

if (!function_exists('steamid3')) {
	function steamid3($id)
	{
		return steamid($id)->RenderSteam3();
	}
}