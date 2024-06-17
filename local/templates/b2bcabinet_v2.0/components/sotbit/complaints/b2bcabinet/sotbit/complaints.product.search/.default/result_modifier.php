<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$arResult['HEADERS'][] = ['id' => "NAME", 'name' => Loc::getMessage('SOTBIT_COMPLAINTS_POSITIONS_PRODUCT_TITLE'), "sort" => "NAME", "default" => true, "editable" => false, "align" => "left"];
$arResult['HEADERS'][] = ['id' => "BUTTON", 'name' => "<button type=\"button\" class=\"list-icons-item btn btn-link p-0\" onclick=\"changeProd(".json_encode(array_keys($arResult["ITEMS"])).");\">".
Loc::getMessage('SOTBIT_COMPLAINTS_BUTTON_ADD')
."</button>", "html" => true, "default" => true, "editable" => false];

foreach ($arParams['SEARCH_PRODUCTS_FIELDS'] as $key => $val) {
    $arResult['HEADERS'][] = ['id' => $val, 'name' => $arResult['DISPLAY_FIELDS'][$val], "default" => true, "editable" => false, "align" => "right"];
}

foreach ($arParams['SEARCH_PRODUCTS_PROPERTIES'] as $key => $val) {
    $arResult['HEADERS'][] = ['id' => "PROPERTY_" . $val . "_VALUE", 'name' => $arResult['DISPLAY_PROPS'][$val]['NAME'], $arResult['DISPLAY_PROPS'][$val]['NAME'], "default" => true, "editable" => false, "align" => "right"];
}

foreach ($arResult['ITEMS'] as $key => $val) {
    $arResult['ITEMS_TO_JS'][$key] = $val['data'];
}