<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$this->setFrameMode(true);

global $COMPLAINTS_GOODS;
$filterOption = new Bitrix\Main\UI\Filter\Options('COMPLAINTS_GOODS' . $_REQUEST['orderId']);
$filterData = $filterOption->getFilter([]);
$COMPLAINTS_GOODS = $filterData;

$APPLICATION->IncludeComponent(
    "bitrix:ui.sidepanel.wrapper",
    ".default",
    array(
        'POPUP_COMPONENT_NAME' => "sotbit:complaints.product.search",
        'POPUP_COMPONENT_TEMPLATE_NAME' => "",
        'POPUP_COMPONENT_PARAMS' => $arParams,
        'USE_PADDING' => true,
        'POPUP_COMPONENT_PARENT' => $component,
    )
);
