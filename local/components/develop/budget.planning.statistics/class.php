<?php

class BudgetStatistics
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {
        $this->IncludeComponentTemplate();
    }

}