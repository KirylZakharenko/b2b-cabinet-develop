<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Budget\History\BudgetHistory;

class BudgetPlanningHistory extends CBitrixComponent
{

//    public function onIncludeComponentLang()
//    {
//        Loc::loadMessages(dirname(__FILE__) . "/class.php");
//    }

    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {

        $this->arResult['HISTORY_LIST'] = $this->getHistory();
        $this->IncludeComponentTemplate();
    }

    public function getHistory(): array
    {
        $history = new BudgetHistory($this->arParams['USER_DATA']);
        return $history->getHistoryList();
    }
}