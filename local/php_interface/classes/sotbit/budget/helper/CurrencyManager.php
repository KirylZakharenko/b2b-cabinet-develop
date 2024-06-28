<?php

namespace Develop\Helper;

use CCurrencyLang;

class CurrencyManager
{
    public static function getBaseCurrency(): ?string
    {
        return \Bitrix\Currency\CurrencyManager::getBaseCurrency();
    }

    public static function currencyFormat($arPrices, $currency, $arCodes = [], $flag = true)
    {
        if (gettype($arPrices) === 'array') {
            $result = [];
            if (!empty($arCodes)) {
                foreach ($arCodes as $key => $arCode) {
                    $result[$arCode] = CCurrencyLang::CurrencyFormat($arPrices[$key], $currency, $flag);
                }
                return $result;
            } else {
                foreach ($arPrices as $price) {
                    $result[] = CCurrencyLang::CurrencyFormat($price, $currency, $flag);
                }
                return $result;
            }
        } else {
            return CCurrencyLang::CurrencyFormat($arPrices, $currency, $flag);
        }
    }
}