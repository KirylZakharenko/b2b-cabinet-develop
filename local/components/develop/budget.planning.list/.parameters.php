<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;



$arComponentParameters = [
    // основной массив с параметрами
    "PARAMETERS" => [
        // выбор типа инфоблока
        'SHOW_TIME_STAMP' => array(                  // ключ массива $arParams в component.php
            'NAME' => 'Периоды отображения расходов',  // название параметра
            'TYPE' => 'LIST',                    // тип элемента управления, в котором будет устанавливаться параметр
            'VALUES' => [
                'MONTH' => 'Месяц',
                'YEAR' => 'Год',
            ],
            'REFRESH' => 'N',                    // перегружать настройки или нет после выбора (N/Y)
            'DEFAULT' => 'MONTH',                 // значение по умолчанию
            'MULTIPLE' => 'Y',                   // одиночное/множественное значение (N/Y)
        ),
        'SHOW_HISTORY' => array(
            'NAME' => 'Показывать историю',
            'TYPE' => 'CHECKBOX',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'N',
            "PARENT" => "ADDITIONAL_SETTINGS"
        ),
    ]
];