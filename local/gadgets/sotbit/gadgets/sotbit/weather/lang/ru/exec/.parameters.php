<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$arParameters = array(
	"PARAMETERS" => array(
		"CACHE_TIME" => array(
			"NAME" => "Время кеширования, сек (0-не кешировать)",
			"TYPE" => "STRING",
			"DEFAULT" => "3600"
		),
		"SHOW_URL" => array(
			"NAME" => "Показывать ссылку на подробную информацию",
			"TYPE" => "CHECKBOX",
			"MULTIPLE" => "N",
			"DEFAULT" => "N",
		),
		"API_KEY_LOCATOR" => array(
			"NAME" => "Ключ API Яндекс Локатор",
			"TYPE" => "STRING",
		),
		"API_KEY_WEATHER" => array(
			"NAME" => "Ключ API Яндекс.Погоды",
			"TYPE" => "STRING",
		),
	),
	"USER_PARAMETERS" => array(
	),
);