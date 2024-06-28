<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Планирование");


$APPLICATION->IncludeComponent(
	"develop:budget.planning", 
	".default", 
	array(
		"MESSAGE_404" => "",
		"SEF_MODE" => "N",
		"SET_STATUS_404" => "N",
		"SHOW_404" => "N",
		"SHOW_HISTORY" => "Y",
		"SHOW_TIME_STAMP" => array(
			0 => "MONTH",
		),
		"COMPONENT_TEMPLATE" => ".default",
		"VARIABLE_ALIASES" => array(
			"ELEMENT_CODE" => "ELEMENT_CODE",
			"SECTION_CODE" => "SECTION_CODE",
		)
	),
	false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");