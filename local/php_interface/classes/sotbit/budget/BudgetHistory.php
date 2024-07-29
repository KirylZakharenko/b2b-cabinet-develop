<?php

namespace Budget\History;

use Budget\Budget;
use Bitrix\Sale\StatusLangTable;
use Develop\Helper\CurrencyManager;

class BudgetHistory extends Budget
{
    protected array $orderStatus = [];
    protected array $orderStatusList = [];

    public function __construct($userData)
    {
        parent::__construct($userData);
    }

    public function getHistoryList(): array
    {
        $result['HISTORY_LIST'] = [];
        foreach ($this->userData as $data) {
            $orderDate = static::getDate('d.m.Y', $data['TIME_STAMP']);
            $sumFormatted = CurrencyManager::currencyFormat($data['MONEY']);

            $result['HISTORY_LIST'][] = [
                'SUM' => $data['MONEY'],
                'SUM_FORMATTED' => $sumFormatted,
                'ORDER_ID' => $data['ORDER_ID'],
                'ORDER_STATUS_ID' => $data['ORDER_STATUS'],
                'STATUS_NAME' => $data['STATUS_NAME'],
                'ORDER_DATE' => $orderDate,
                'ORDER_CANCELED' => $data['ORDER_CANCELED'],
            ];
        }

        usort($result['HISTORY_LIST'], [$this, 'sortItemsAsc']);

        return $result['HISTORY_LIST'];
    }

    public function sortItemsAsc($a, $b)
    {
        return strcmp($a['ORDER_DATE'], $b['ORDER_DATE']);
    }


}