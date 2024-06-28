<?php

namespace Budget\History;

use Budget\Budget;
use Bitrix\Sale\StatusLangTable;
class BudgetHistory extends Budget
{
    protected array $orderStatus = [];
    protected array $orderStatusList = [];

    public function __construct($userData)
    {
        parent::__construct($userData);
    }

    private function getOrderStatus()
    {
           if (!empty($this->userData)) {

               foreach ($this->userData as $data) {
                   $this->orderStatus[] = $data['ORDER_STATUS'];
               }

               $this->orderStatus = array_unique($this->orderStatus);

               $this->getOrderStatusList();

           }
    }

    private function getOrderStatusList()
    {
        $statusList = StatusLangTable::getList([
            'filter' => [
                'STATUS_ID' => $this->orderStatus,
            ],
            'select' => ['STATUS_ID', 'NAME']
        ]);

        while ($status = $statusList->fetch()) {

            $this->orderStatusList[$status['STATUS_ID']] = $status['NAME'];
        }
    }

    public function getHistoryList()
    {
        //parent::calculateCashUser($this->userData);
        $this->getOrderStatus();

        $result['HISTORY_LIST'] = [];



        foreach ($this->userData as $data) {
            $orderDate = static::getDate('d M Y H:i:s', $data['TIME_STAMP']);

            $result['HISTORY_LIST'][] = [
                'MONEY' => $data['MONEY'],
                'ORDER_ID' => $data['ORDER_ID'],
                'ORDER_STATUS' => $this->orderStatusList[$data['ORDER_STATUS']],
                'ORDER_DATE' => $orderDate,
            ];
        }

        return $result['HISTORY_LIST'];
    }


}