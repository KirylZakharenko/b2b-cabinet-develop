<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main;
use Bitrix\Sale;
if ($_POST['CURRENCY']) {

    global $USER;
    $status = writeTable($USER->GetID(), $_POST['CURRENCY']);

    if ($status == "SUCCESS") {

        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Main\Context::getCurrent()->getSite());
        $basketItems = $basket->getBasketItems();

        foreach ($basketItems as $basketItem) {
                $basePrice = $basketItem->getBasePrice();
                $customPrice = CCurrencyRates::ConvertCurrency($basePrice, $basketItem->getCurrency(), $_POST['CURRENCY']);
                $basketItem->setField('CUSTOM_PRICE', 'Y');
                $basketItem->setField('PRICE', $customPrice);
                $basketItem->setField('CURRENCY', $_POST['CURRENCY']);

                $basketItem->save();
        }
    }
    echo json_encode($status);
}