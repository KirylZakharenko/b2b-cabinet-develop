<?php

use Bitrix\Main;
use Bitrix\Main\EventManager as EManager;

EManager::getInstance()->addEventHandler("sale", "OnSaleOrderSaved", "cashbackPayment");

/**
 * @throws Main\ArgumentNullException
 * @throws Main\ArgumentOutOfRangeException
 * @throws Main\ArgumentException
 * @throws Main\ObjectPropertyException
 * @throws Main\SystemException
 * @throws Main\NotImplementedException
 */

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/currency.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/currency.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/functions/table.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/functions/table.php';
}

initCurrencyTable();

function cashbackPayment(Main\Event $event)
{
    /** @var \Bitrix\Sale\Order $order */
    $order = $event->getParameter("ENTITY");
    $isNew = $event->getParameter('IS_NEW');


    if ($isNew) {
        $cashbackCode = 'CASHBACK_PAID';

        $propertyCollection = $order->getPropertyCollection();

        $property = null;
        if ($propertyCollection->getItemByOrderPropertyCode($cashbackCode) === null) {
            $property = $propertyCollection->createItem(
                [
                    'NAME' => "Оплачен баллами",
                    'CODE' => $cashbackCode,
                    'TYPE' => 'BOOLEAN',
                ]
            );
        } else {
            $property = $propertyCollection->getItemByOrderPropertyCode($cashbackCode);

        }


        global $USER_FIELD_MANAGER, $USER;

        $USER_FIELD_MANAGER->Update('USER', $USER->GetID(), ["UF_CASHBACK" => 1111]);

        $property->setField('VALUE', 'Y');
        $order->save();
    }
}