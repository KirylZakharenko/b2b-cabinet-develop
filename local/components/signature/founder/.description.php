<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentDescription = array(
    "NAME" => "Инфраструктура",
    "DESCRIPTION" => '',
    "PATH" => array(
        "ID" => "dv_components",
        "CHILD" => array(
            "ID" => "signature",
            "NAME" => "Общая страница инфраструктур"
        )
    ),
);
