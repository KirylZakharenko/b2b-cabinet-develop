<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Budget\Helper\Config\Budget\BudgetConfig;
use Budget\Helper\Internals\Budget\BudgetTable;
use Budget\Budget;

class BudgetPlanningList extends CBitrixComponent
{
    protected $historyList = [];
    protected $userNameList = [];
    private $reverseKeysList = [
        "USER" => "USER_ID",
        "NAME" => "TYPE",
        "INFO" => "DATA"
    ];

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
        if (!empty($this->getFieldsTable())) {
            $dataList = $this->getFieldsTable();

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

        BudgetConfig::initBudgetTable();

        $userBudget = BudgetTable::getList([
            'filter' => [
                'USER_ID' => $USER->GetID(),
            ],
            'select' => ['*']
        ])->fetchAll();

        if ($userBudget) {
            $data = $userBudget;
        } else {
            $data = [];
        }

        return $data;
    }
}