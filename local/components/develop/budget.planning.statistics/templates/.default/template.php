<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);

$messages = Loc::loadLanguageFile(__FILE__);
?>

<div class="budget-section-history">
    <div class="history-title">История расходов</div>
    <select name="chart" id="view-chart">
        <option value="line"><?=Loc::getMessage('CHART_TYPE_LINE')?></option>
        <option value="bar"><?=Loc::getMessage('CHART_TYPE_BAR')?></option>
        <option value="pie"><?=Loc::getMessage('CHART_TYPE_PIE')?></option>
    </select>
    <canvas id="myChart" ></canvas>
</div>


<script>


    BX.BudgetPlanningComponent.init({
       test: 'testovyu',
        month: <?=CUtil::PhpToJSObject($arResult['MONTH_NAME'])?>,
        message: <?=CUtil::PhpToJSObject($messages)?>,
        dataChart: <?=CUtil::PhpToJSObject($arResult['SUM_GRAPH_STATS'])?>
    });

</script>