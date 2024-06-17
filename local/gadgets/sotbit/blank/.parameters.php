<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arParameters = Array(
	"PARAMETERS" => Array(
		"PATH_TO_BLANK" => array(
			"NAME" => \Bitrix\Main\Localization\Loc::getMessage("GD_SOTBIT_CABINET_PATH_TO_BLANK"),
			"TYPE" => "STRING",
			"DEFAULT" => "/personal/blank_zakaza/"
		),
		'INIT_JQUERY' => array(
			"NAME" => \Bitrix\Main\Localization\Loc::getMessage("GD_SOTBIT_CABINET_INIT_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		),
	)
);
?>
