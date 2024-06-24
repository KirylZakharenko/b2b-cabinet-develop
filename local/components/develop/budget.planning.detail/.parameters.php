<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
if (!CModule::IncludeModule('iblock')) {
    return;
}
Loader::includeModule('iblock');
// получаем массив всех типов инфоблоков для возможности выбора
$arIBlockType = CIBlockParameters::GetIBlockTypes();
// пустой массив для вывода
$arInfoBlocks = array();
// выбираем активные инфоблоки
$arFilterInfoBlocks = array('ACTIVE' => 'Y');
// сортируем по озрастанию поля сортировка
$arOrderInfoBlocks = array('SORT' => 'ASC');
// если уже выбран тип инфоблока, выбираем инфоблоки только этого типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
// метод выборки информационных блоков
$rsIBlock = CIBlock::GetList($arOrderInfoBlocks, $arFilterInfoBlocks);
// перебираем и выводим в адмику доступные информационные блоки
while ($obIBlock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}
// настройки компонента, формируем массив $arParams
$arComponentParameters = [
    // основной массив с параметрами
    "PARAMETERS" => [
        // псевдоимена для комплексного компонента
        "VARIABLE_ALIASES" => [
            // элемент
            "ELEMENT_CODE" => [
                "NAME" => 'Символьный код элемента',
            ],
            // секция
            "SECTION_CODE" => [
                "NAME" => 'Символьный код раздела',
            ],
            "CATALOG_URL" => [
                "NAME" => 'Каталог (относительно корня сайта)',
            ]
        ],
        // настройки режима ЧПУ
        "SEF_MODE" => [
            // настройки для секции
            "section" => [
                "NAME" => 'Страница раздела',
                "DEFAULT" => "#SECTION_CODE#/",
                "VARIABLES" => [
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ],
            ],
            // настройки для элемента
            "element" => [
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
        // выбор типа инфоблока
        'IBLOCK_TYPE' => array(                  // ключ массива $arParams в component.php
            'PARENT' => 'TEST',                  // название группы
            'NAME' => 'Выберите тип инфоблока',  // название параметра
            'TYPE' => 'LIST',                    // тип элемента управления, в котором будет устанавливаться параметр
            'VALUES' => $arIBlockType,           // входные значения
            'REFRESH' => 'Y',                    // перегружать настройки или нет после выбора (N/Y)
            'DEFAULT' => 'news',                 // значение по умолчанию
            'MULTIPLE' => 'N',                   // одиночное/множественное значение (N/Y)
        ),
        // выбор самого инфоблока
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите родительский инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
        ),
    ]
];