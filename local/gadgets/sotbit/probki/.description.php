<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arDescription = array(
    "NAME" => Loc::getMessage('PROBKI_GADGET_NAME'),
    "DESCRIPTION" => Loc::getMessage('PROBKI_GADGET_DESCRIPTION'),
    "ICON" => "",
    "LANG_ONLY" => "ru",
    "GROUP" => array("ID" => "services"),
    "SU" => true,
    "SG" => true,
    "AI" => true,
);
?>