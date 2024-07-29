<?php

use Develop\Helper\DateManager as Date;

$userPriceList = [];
$mounth = [
    'Январь',
    'Февраль',
    'Март',
    'Апрель',
    'Май',
    'Июнь',
    'Июль',
    'Август',
    'Сентябрь',
    'Октябрь',
    'Ноябрь',
    'Декабрь'
];

$arResult['MONTH_NAME'] = $mounth;
$arResult['SUM_GRAPH_STATS'] = [];

if ($arParams['USER_DATA']) {

    $userData = &$arParams['USER_DATA'];

    foreach ($userData as $item) {
        if ($item['ORDER_CANCELED'] == 'Y') continue;

        $date = (int)Date::getDate('m', $item['TIME_STAMP']);
        $userPriceList[$date][] = $item['MONEY'];
    }

    $allSumStats = [];
    $rangeMonth = array_fill(0, 12, 0);

    foreach ($userPriceList as $key => $item) {

        $rangeMonth[$key] = array_sum($item);
    }

    $arResult['SUM_GRAPH_STATS'] = $rangeMonth;
}


