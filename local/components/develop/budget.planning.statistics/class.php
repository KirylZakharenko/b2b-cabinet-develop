<?php

use Budget\Statistics\BudgetStatistics as Statistics;

class BudgetStatistics extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {

        $statistics = new Statistics($this->arParams['USER_DATA']);

        $this->arResult['HISTORY_LIST'] = $statistics->getHistoryList();

        $this->IncludeComponentTemplate();
    }

}