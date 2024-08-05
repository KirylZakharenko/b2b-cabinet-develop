<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("инфраструктура");

$APPLICATION->IncludeComponent(
    "signature:founder",
    ".default",
    array()
);






require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>