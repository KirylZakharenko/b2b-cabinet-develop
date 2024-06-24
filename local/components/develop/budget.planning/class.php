<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Internals\OrderChangeTable,
    Bitrix\Main\UserTable;
use Bitrix\Main\Grid\Options as GridOptions;

class BudgetPlanningDetail extends CBitrixComponent
{
    protected $historyList = [];
    protected $userNameList = [];
    private $reverseKeysList = [
        "USER" => "USER_ID",
        "NAME" => "TYPE",
        "INFO" => "DATA"
    ];

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
//        if ($this->startResultCache()) {
//            $this->getUserSaleHistory($this->arParams["ORDER_ID"]);
//
//            if (!empty($this->historyList)) {
//                $this->arResult["HISTORY_LIST"] = $this->historyList;
//            } else {
//                $this->arResult["HISTORY_LIST"] = [];
//            }
//
//            $this->setResultCacheKeys(["HISTORY_LIST"]);
//
//        } else {
//            $this->abortResultCache();
//        }

        $this->IncludeComponentTemplate('index');

    }

    public function getUserSaleHistory($orderID): void
    {

        $filter = [
            "ORDER_ID" => $orderID,
            "!ENTITY" => ["PROPERTY", "TAX"],
            "!ENTITY_ID" => "",
        ];
        $select = [
            "DATE_MODIFY", "TYPE", "DATA", "USER_ID", "ID"
        ];

        $grid_options = new GridOptions("HISTORY_LIST");
        $sort = $grid_options->GetSorting();

        $sortKey = array_key_first($sort['sort']);

        if (array_key_exists($sortKey, $this->reverseKeysList)) {
            $sort['sort'][$this->reverseKeysList[$sortKey]] = $sort['sort'][$sortKey];
            unset($sort['sort'][$sortKey]);
        }

        $result = OrderChangeTable::getList([
            "filter" => $filter,
            "select" => $select,
            "order" => $sort['sort'],
            "cache" => ['ttl' => 3600]
        ]);

        while ($entry = $result->fetch()) {

            $print["COLUMNS"] = \CSaleOrderChange::GetRecordDescription($entry["TYPE"], $entry["DATA"]);

            if ($print["COLUMNS"]["INFO"] == "") {
                $print["COLUMNS"]["INFO"] = '-';
            }

            $print["COLUMNS"]["DATE_MODIFY"] = $entry["DATE_MODIFY"]->toString();

            if (!isset($this->userNameList[$entry["USER_ID"]])) {
                $print["COLUMNS"]["USER"] = $this->getUserName($entry["USER_ID"]);
            } else {
                $print["COLUMNS"]["USER"] = $this->userNameList[$entry["USER_ID"]];
            }

            $print['data'] = $print["COLUMNS"];
            $print['actions'] = [];
            $print['editable'] = true;
            $print['id'] = $entry['ID'];

            $this->historyList[] = $print;
        }
    }

    public function getUserName($userID): string
    {
        $result = UserTable::getList([
            "filter" => ["ID" => $userID],
            "select" => ["NAME", "LAST_NAME"],
            "cache" => ['ttl' => 3600]
        ])->fetch();

        return $result['NAME'] . " " . $result['LAST_NAME'];
    }
}