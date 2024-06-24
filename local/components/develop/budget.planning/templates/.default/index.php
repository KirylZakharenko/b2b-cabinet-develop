<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    "develop:budget.planning.detail",
    "",
    Array(
        "IBLOCK_ID" => "",
        "IBLOCK_TYPE" => "sotbit_b2bcabinet_type_catalog",
    )
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");