<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$arParameters = Array(
    "PARAMETERS"=> Array(
        "CACHE_TIME" => array(
            "NAME" => Loc::getMessage('PROBKI_GADGET_NAME_CACHE_TIME'),
            "TYPE" => "STRING",
            "DEFAULT" => "3600"
        ),
        "SHOW_URL" => Array(
            "NAME" => Loc::getMessage('PROBKI_GADGET_NAME_SHOW_URL'),
            "TYPE" => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT" => "N",
        ),
    ),
    "USER_PARAMETERS"=> Array(
        "CITY"=>Array(
            "NAME" => Loc::getMessage('PROBKI_GADGET_NAME_USER_PARAMETERS'),
            "TYPE" => "STRING",
        ),
    ),
);

?>