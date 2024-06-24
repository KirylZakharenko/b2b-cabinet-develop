<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Планирование");


$APPLICATION->IncludeComponent(
	"develop:budget.planning",
	"",
	Array(
		"IBLOCK_ID" => "",
		"IBLOCK_TYPE" => "sotbit_b2bcabinet_type_catalog",
		"SEF_MODE" => "N",
		"VARIABLE_ALIASES" => Array(
			"CATALOG_URL" => "CATALOG_URL",
			"ELEMENT_CODE" => "ELEMENT_CODE",
			"SECTION_CODE" => "SECTION_CODE"
		)
	)
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");