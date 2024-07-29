<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;

$arComponentParameters = [
    "PARAMETERS" => [
        'SHOW_GRAPH' => array(
            'NAME' => 'Отображать статистику в графах',
            'TYPE' => 'CHECKBOX',
            'MULTIPLE' => 'N',
            'DEFAULT' => 'N',
            "PARENT" => "ADDITIONAL_SETTINGS"
        ),
        'USER_DATA' => array(
            'NAME' => 'Пользовательские данные',
            'TYPE' => 'LIST',
            'REFRESH' => 'N',

        ),
    ]
];