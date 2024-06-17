<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Custom\Currency\CurrencyTable;
if (isset($_POST['CURRENCY'])) {

    global $USER;
    $status = writeTable($USER->GetID(), $_POST['CURRENCY']);
    echo json_encode($status);
}