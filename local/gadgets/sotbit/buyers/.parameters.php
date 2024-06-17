<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arParameters = [
	"PARAMETERS" => [
		"SEF_FOLDER" => [
			"NAME" => \Bitrix\Main\Localization\Loc::getMessage("GD_SOTBIT_CABINET_BUYERS_PATH_TO_BUYER"),
			"TYPE" => "STRING",
			"DEFAULT" => "/b2bcabinet/personal/",
		],
		"PATH_TO_DETAIL" => [
			"NAME" => \Bitrix\Main\Localization\Loc::getMessage("GD_SOTBIT_CABINET_BUYERS_SPPL_PATH_TO_DETAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => "profile_detail.php?ID=#ID#",
		],
	],

	'USER_PARAMETERS' => [
		"PER_PAGE" => [
			"NAME" => \Bitrix\Main\Localization\Loc::getMessage("GD_SOTBIT_CABINET_BUYERS_SPPL_PER_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => 5,
		],
	]
];
?>
