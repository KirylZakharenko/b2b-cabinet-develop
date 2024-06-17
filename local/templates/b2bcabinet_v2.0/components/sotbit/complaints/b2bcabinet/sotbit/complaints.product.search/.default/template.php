<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="header-content">
    <h1><?= Loc::getMessage('SOTBIT_COMPLAINTS_SEARCH_TITLE') ?></h1>
    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:main.ui.filter",
        "b2bcabinet",
        array(
            "FILTER_ID" => "COMPLAINTS_GOODS" . $_REQUEST['orderId'],
            "GRID_ID" => "COMPLAINTS_GOODS" . $_REQUEST['orderId'],
            'FILTER' => [
                ['id' => 'NAME', 'name' => GetMessage("SOTBIT_COMPLAINTS_FILTER_NAME"), 'type' => 'string'],
                ['id' => 'ARTICLE', 'name' => GetMessage("SOTBIT_COMPLAINTS_FILTER_ARTICLE"), 'type' => 'string'],
            ],
            "ENABLE_LIVE_SEARCH" => true,
            "ENABLE_LABEL" => true,
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );?>

</div>
<div class="rounded-3 border bg-white overflow-auto main-wrap">
    <?
    $APPLICATION->IncludeComponent(
        'bitrix:main.ui.grid',
        'simple',
        array(
            'GRID_ID' => 'COMPLAINTS_GOODS' . $_REQUEST['orderId'],
            'HEADERS' => $arResult["HEADERS"],
            'ROWS' => $arResult["ITEMS"],
            'AJAX_MODE' => 'Y',

            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",

            "ALLOW_COLUMNS_SORT" => true,
            "ALLOW_ROWS_SORT" => [],
            "ALLOW_COLUMNS_RESIZE" => false,
            "ALLOW_HORIZONTAL_SCROLL" => false,
            "ALLOW_SORT" => true,
            "ALLOW_PIN_HEADER" => true,
            "ACTION_PANEL" => [],

            "SHOW_CHECK_ALL_CHECKBOXES" => false,
            "SHOW_ROW_CHECKBOXES" => false,
            "SHOW_ROW_ACTIONS_MENU" => false,
            "SHOW_GRID_SETTINGS_MENU" => false,
            "SHOW_NAVIGATION_PANEL" => true,
            "SHOW_PAGINATION" => true,
            "SHOW_SELECTED_COUNTER" => false,
            "SHOW_TOTAL_COUNTER" => true,
            "SHOW_PAGESIZE" => true,
            "SHOW_ACTION_PANEL" => true,

            "ENABLE_COLLAPSIBLE_ROWS" => true,
            'ALLOW_SAVE_ROWS_STATE' => true,

            "SHOW_MORE_BUTTON" => false,
            '~NAV_PARAMS' => $arResult['GET_LIST_PARAMS']['NAV_PARAMS'],
            'NAV_OBJECT' => $arResult['NAV_OBJECT'],
            'NAV_STRING' => $arResult['NAV_STRING_STAFF_A'],
            "TOTAL_ROWS_COUNT" => count(is_array($arResult["ITEMS"]) ? $arResult["ITEMS"] : []),
            "CURRENT_PAGE" => $arResult['CURRENT_PAGE'],
            "PAGE_SIZES" => $arParams['ORDERS_PER_PAGE'],
            "DEFAULT_PAGE_SIZE" => 50,
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    ); ?>
</div>

<script>
    BX.message(<?=\Bitrix\Main\Web\Json::encode(\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__))?>);
    var productsObject = <?=CUtil::phpToJSObject($arResult["ITEMS_TO_JS"])?>;
</script>
