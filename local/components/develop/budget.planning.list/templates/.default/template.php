<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<div class="budget-section">


    <div class="budget-section-stats">
        <? if ($arResult['USER_DATA_CASH']) { ?>
            <div class="stats-days">
                <div class="stats-title">Расходы за месяц:</div>
                <div class="stats-cash">
                    <span><?= $arResult['USER_DATA_CASH']['ALL_SUM_FORMATTED']['MONTH'] ?></span>
                </div>
            </div>

            <div class="stats-days">
                <div class="stats-title">Расходы за год:</div>
                <div class="stats-cash">
                    <span><?= $arResult['USER_DATA_CASH']['ALL_SUM_FORMATTED']['YEAR'] ?></span>
                </div>
            </div>
        <? } ?>
    </div>

    <div class="another-section">
        <?
        if ($arParams['SHOW_HISTORY'] === 'Y') { ?>
            <div class="another-block">
                <?
                $APPLICATION->IncludeComponent(
                    "develop:budget.planning.history",
                    ".default",
                    array(
                        'USER_DATA' => $arResult['USER_DATA']
                    )
                );
                ?>
            </div>

        <? } ?>
        <? if ($arParams['SHOW_GRAPH'] === 'Y') { ?>
            <div class="another-block">
                <? $APPLICATION->IncludeComponent(
                    "develop:budget.planning.statistics",
                    ".default",
                    array(
                        'SHOW_GRAPH' => $arParams['SHOW_GRAPH']
                    )
                ); ?>
            </div>
        <? }
        ?>
    </div>

</div>