<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>
<div class="budget-section-history">
    <div class="history-title"><?= Loc::getMessage('BUDGET_HISTORY_TITLE') ?></div>

    <? if (!empty($arResult['HISTORY_LIST'])) { ?>

        <div class="history-list">

            <? foreach ($arResult['HISTORY_LIST'] as $item) { ?>
                <div class="history-item">

                    <div class="history-right">
                        <div class="history-label">
                            <div class="history-item-date"><?= Loc::getMessage('BUDGET_HISTORY_ORDER') ?><?= $item['ORDER_ID'] ?></div>
                        </div>
                        <div class="history-item-status">
                            <span><?= Loc::getMessage('BUDGET_HISTORY_STATUS') ?></span>

                            <? if ($item['ORDER_CANCELED'] == 'Y'): ?>
                                <span class="canceled-order">Отменен</span>
                            <? else: ?>
                            <span class="<?if ($item['ORDER_STATUS_ID'] == 'F'): ?>success-order<? endif; ?>"><?= $item['STATUS_NAME'] ?></span>
                            <? endif; ?>
                        </div>
                    </div>

                    <div class="history-left">
                        <div class="history-label">
                            <div class="history-item-sum-block">
                                <div class="history-item-title"><?= Loc::getMessage('BUDGET_HISTORY_SUM') ?></div>
                                <div class="history-item-subtitle"><?= $item['SUM_FORMATTED'] ?></div>
                            </div>
                            <div class="history-item-date"><?= $item['ORDER_DATE'] ?></div>
                        </div>
                    </div>
                </div>
            <? } ?>
        </div>
    <? } ?>
</div>