<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<div class="budget-section-history">
    <div class="history-title">История расходов</div>

    <? if (!empty($arResult['HISTORY_LIST'])) { ?>

        <div class="history-list">

            <? foreach ($arResult['HISTORY_LIST'] as $item) { ?>
                <div class="history-item">

                    <div class="history-left">
                        <div class="history-label">
                            <div class="history-item-title"><?= $item['MONEY'] ?></div>
                            <div class="history-item-date"><?= $item['ORDER_DATE'] ?></div>
                        </div>
                    </div>

                    <div class="history-right">
                        <div class="history-label">
                            <div class="history-item-date">Заказ № <?= $item['ORDER_ID'] ?></div>
                        </div>
                        <div class="history-item-status">
                            <span><?= $item['ORDER_STATUS'] ?></span>
                        </div>
                    </div>
                </div>
            <? } ?>
        </div>
    <? } ?>
</div>