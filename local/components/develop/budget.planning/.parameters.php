<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

$arComponentParameters = [
    "PARAMETERS" => [

        "VARIABLE_ALIASES" => [
            "ELEMENT_CODE" => [
                "NAME" => 'Символьный код элемента',
            ],
            "SECTION_CODE" => [
                "NAME" => 'Символьный код раздела',
            ],
        ],

        "SEF_MODE" => [//Вкл/выкл режим ЧПУ. Каждый дочерний элемент - это шаблон, на котором подключаются простые компоненты.
            "list" => [
                "NAME" => 'Страница раздела',
                "DEFAULT" => "#SECTION_CODE#/",
                "VARIABLES" => [
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ],
            ],
            "graph" => [
                "NAME" => 'Детальная страница',
                "DEFAULT" => "#SECTION_CODE#/#ELEMENT_CODE#/",
                "VARIABLES" => [
                    "ELEMENT_ID",
                    "ELEMENT_CODE",
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ]
            ]
        ],

        'SHOW_TIME_STAMP' => array(
            'NAME' => 'Периоды отображения расходов',
            'TYPE' => 'LIST',
            'VALUES' => [
                'MONTH' => 'Месяц',
                'YEAR' => 'Год',
            ],
            'REFRESH' => 'N',
            'DEFAULT' => 'MONTH',
            'MULTIPLE' => 'Y',
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

CIBlockParameters::Add404Settings($arComponentParameters, []);