<?php

namespace Budget\Helper\Config\Budget;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Budget\Helper\Internals\Budget\BudgetTable;

class BudgetConfig
{
    public static function initBudgetTable(): void
    {
        global $APPLICATION;
        $dbComm = Application::getConnection(BudgetTable::getConnectionName());
        $tableExists = $dbComm->isTableExists(BudgetTable::getTableName());
        if (!$tableExists) {
            Base::getInstance(BudgetTable::class)->createDbTable();
        }
    }

    public static function deleteTable(): void
    {
        $dbComm = Application::getConnection(BudgetTable::getConnectionName());
        $dbComm->queryExecute('drop table if exists ' . BudgetTable::getTableName());
    }

    public static function writeTable($userID, $currency): string
    {

        $status = '';

        $user = BudgetTable::getList(['filter' => ['USER_ID' => $userID]])->fetch();
        if (!empty($user)) {
            BudgetTable::update($user['ID'], ['CURRENCY' => $currency]);
            $status = "SUCCESS";
        } else {
            BudgetTable::add([
                'USER_ID' => $userID,
                'CURRENCY' => $currency
            ]);
            $status = "ERROR";
        }

        return $status;
    }
}