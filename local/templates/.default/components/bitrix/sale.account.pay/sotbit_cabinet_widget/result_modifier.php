<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 13-Feb-18
 * Time: 12:21 PM
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['CURRENCY'] = trim(str_replace('# ', '', $arResult['FORMATED_CURRENCY']));

$arResult['USER_ACCOUNT'] = $dbAccountCurrency = CSaleUserAccount::GetList(
    [],
    ['USER_ID' => $USER->GetID()],
    false,
    false,
    []
)->Fetch();

if(!empty($arResult['USER_ACCOUNT']) && !empty($arResult['USER_ACCOUNT']['CURRENT_BUDGET'])) {
    $arResult['USER_ACCOUNT']['FORMAT_CURRENT_BUDGET'] = CCurrencyLang::CurrencyFormat($arResult['USER_ACCOUNT']['CURRENT_BUDGET'], $arResult['USER_ACCOUNT']['CURRENCY'], true);
}
?>