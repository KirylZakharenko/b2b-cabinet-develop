<?php

class BudgetStatistics extends CBitrixComponent
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