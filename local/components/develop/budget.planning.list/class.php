<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Budget\Helper\Config\Budget\BudgetConfig;
use Budget\Helper\Internals\Budget\BudgetTable;
use Budget\Budget;

class BudgetPlanningList extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {
            $this->arResult['USER_DATA_CASH'] = $this->getUserData();
            $this->IncludeComponentTemplate();
    }

    public function getUserData()
    {
        $orderFields = $this->getFieldsTable();

        if (!empty($orderFields)) {
            $dataList = $orderFields;

            $this->arResult['USER_DATA'] = $dataList;
            $userBudget = new Budget($dataList);
            return $userBudget->calculateSendCash();
        } else {
            return [];
        }
    }

    public function getFieldsTable()
    {
        global $USER;


        $userBudget = \Bitrix\Sale\Internals\OrderTable::getList([
            'filter' => [
                'USER_ID' => $USER->GetID(),
            ],
            'select' => [
                'ORDER_ID' => 'ID',
                'USER_ID',
                'MONEY' => 'PRICE',
                'ORDER_STATUS' => 'STATUS_ID',
                'STATUS_NAME' => 'STATUS.NAME',
                'ORDER_CANCELED' => 'CANCELED',
                'DATE_CANCELED',
                'TIME_STAMP' => 'DATE_STATUS',
            ]
        ])->fetchAll();
        if ($userBudget) {
            $data = $userBudget;
        } else {
            $data = [];
        }
        return $data;
    }
}