<?php

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Sale;

use Custom\Currency\CurrencyTable;

function initCurrencyTable()
{
    global $APPLICATION;
    $dbComm = Application::getConnection(CurrencyTable::getConnectionName());
    $tableExists = $dbComm->isTableExists(CurrencyTable::getTableName());
    if (!$tableExists) {
        Base::getInstance(CurrencyTable::class)->createDbTable();
    }
}

function deleteTable()
{
    $dbComm = Application::getConnection(CurrencyTable::getConnectionName());
    $dbComm->queryExecute('drop table if exists ' . CurrencyTable::getTableName());
}

function writeTable($userID, $currency)
{

    $status = '';

    $user = CurrencyTable::getList(['filter' => ['USER_ID' => $userID]])->fetch();
    if (!empty($user)) {
        CurrencyTable::update($user['ID'], ['CURRENCY' => $currency]);
        $status = "SUCCESS";
    } else {
        CurrencyTable::add([
            'USER_ID' => $userID,
            'CURRENCY' => $currency
        ]);
        $status = "ERROR";
    }

    return $status;
}

/**
 * @throws Main\ArgumentTypeException
 * @throws Main\ArgumentException
 * @throws Main\NotImplementedException
 */
function changeCurrencyBasketItems($userID, $currency)
{
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

//    $basketItems =
}