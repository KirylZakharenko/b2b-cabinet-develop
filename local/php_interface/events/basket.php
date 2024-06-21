<?php

use Bitrix\Main;
use Bitrix\Main\EventManager;
use Bitrix\Sale;

$manager = EventManager::getInstance();

$manager->addEventHandler('sale', 'OnBasketAdd', ['BasketEvents', 'changeCurrencyItems']);

class BasketEvents
{
    public static function changeCurrencyItems($ID, $arFields)
    {
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Main\Context::getCurrent()->getSite());
        $basketItems = $basket->getBasketItems();
        $userCurrency = $_SESSION['USER_CURRENCY'];
        foreach ($basketItems as $basketItem) {

            if ($ID == $basketItem->getId()) {
                $basePrice = $basketItem->getPrice();

                $customPrice = CCurrencyRates::ConvertCurrency($basketItem->getPrice(), $basketItem->getCurrency(), $userCurrency);
                $basketItem->setField('CUSTOM_PRICE', 'Y'); // Изменение поля
                $basketItem->setField('PRICE', $customPrice); // Изменение поля
                $basketItem->setField('CURRENCY', $userCurrency); // Изменение поля

                $basketItem->save();

                break;
            }
        }
    }

}
