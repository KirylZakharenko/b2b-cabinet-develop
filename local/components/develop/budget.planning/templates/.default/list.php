<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->IncludeComponent(
    "develop:budget.planning.list",
    ".default",
    Array(
        'SHOW_TIME_STAMP' => ['MONTH'],
        'SHOW_HISTORY' => $arParams['SHOW_HISTORY']
    ),
);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");