<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Loader;

/**
 * Список переменных, которых также нужно получить из $_REQUEST параметров, но их нет в url-маске
 * К примеру мы указали маску /catalog/#SECTION_CODE#/ и в $arComponentVariables указали "SECTION",
 * то при парсинге урл /catalog/section-code/?SECTION=123 в $arVariables будет SECTION_CODE и SECTION, несмотря на то, что SECTION в маске нет.
 * @var array|string[]
 */

class BudgetPlanning extends CBitrixComponent
{

    protected array $arComponentVariables = [
        "SECTION",
    ];

    public function onPrepareComponentParams($params)
    {
            return $params;
    }

    public function executeComponent()
    {

        if ($this->arParams["SEF_MODE"] === "Y") {
            $componentPage = $this->sefMode();
        } else {
            $componentPage = $this->noSefMode();
        }

        //Отдать 404 статус если не найден шаблон
        if (!$componentPage) {
            Tools::process404(
                $this->arParams["MESSAGE_404"],
                ($this->arParams["SET_STATUS_404"] === "Y"),
                ($this->arParams["SET_STATUS_404"] === "Y"),
                ($this->arParams["SHOW_404"] === "Y"),
                $this->arParams["FILE_404"]
            );
        }

        $this->IncludeComponentTemplate($componentPage);
    }


    protected function sefMode()
    {
        //Значение алиасов по умолчанию.
        $arDefaultVariableAliases404 = [];

        /**
         * Значение масок для шаблонов по умолчанию. - маски без корневого раздела,
         * который указывается в $arParams["SEF_FOLDER"]
         */
        $arDefaultUrlTemplates404 = [
            "list" => "#SECTION_CODE#/",
            "graph" => "#SECTION_CODE#/#ELEMENT_CODE#/",
        ];

        //В этот массив будут заполнены переменные, которые будут найдены по маске шаблонов url
        $arVariables = [];

        $engine = new CComponentEngine($this);
        //Нужно добавлять для парсинга SECTION_CODE_PATH и SMART_FILTER_PATH (жадные шаблоны)
        $engine->addGreedyPart("#SECTION_CODE_PATH#");
        $engine->addGreedyPart("#SMART_FILTER_PATH#");
        $engine->setResolveCallback(["CIBlockFindTools", "resolveComponentEngine"]);

        //Объединение дефолтных параметров масок шаблонов и алиасов. Параметры из настроек заменяют дефолтные.
        $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $this->arParams["SEF_URL_TEMPLATES"]);
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $this->arParams["VARIABLE_ALIASES"]);

        //Поиск шаблона
        $componentPage = $engine->guessComponentPath(
            $this->arParams["SEF_FOLDER"],
            $arUrlTemplates,
            $arVariables
        );

        //Проброс значений переменных из алиасов.
        CComponentEngine::initComponentVariables($componentPage, $this->arComponentVariables, $arVariableAliases, $arVariables);
        $this->arResult = [
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases
        ];

        return $componentPage;
    }

    protected function noSefMode()
    {
        $componentPage = "";
        $arVariables = [];
        $arDefaultVariableAliases = [];

        //Объединение дефолтных алиасов. Параметры из настроек заменяют дефолтные.
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $this->arParams["VARIABLE_ALIASES"]);
        //Получаем значения переменных в $arVariables
        CComponentEngine::initComponentVariables(false, $this->arComponentVariables, $arVariableAliases, $arVariables);
//
//        //По найденным параметрам $arVariables определяем тип страницы
//        if ((isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
//            || (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')
//        ) {
//            $componentPage = "graph";
//        } elseif ((isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
//            || (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '')
//        ) {
//            $componentPage = "list";
//        }

        $this->arResult = [
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases
        ];

        $componentPage = "list";
        return $componentPage;
    }

}