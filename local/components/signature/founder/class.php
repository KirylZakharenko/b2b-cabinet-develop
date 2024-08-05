<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\OrderChangeTable,
    Bitrix\Main\UserTable;
use Bitrix\Main\Grid\Options as GridOptions;

class SaleHistory extends CBitrixComponent
{
    protected $historyList = [];
    protected $userNameList = [];

    private $reverseKeysList = [
        "USER" => "USER_ID",
        "NAME" => "TYPE",
        "INFO" => "DATA"
    ];

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(dirname(__FILE__) . "/class.php");
    }

    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {
        $this->IncludeComponentTemplate();
    }

}