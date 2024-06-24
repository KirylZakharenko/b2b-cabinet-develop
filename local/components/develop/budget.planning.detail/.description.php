<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => 'Планирование бюджета',
    "DESCRIPTION" => 'Позволяет вести статистику своих финансов',
    "ICON" => "/images/catalog.gif",
    "COMPLEX" => "Y",
    "SORT" => 10,
    "PATH" => array(
        "ID" => "content",
        "CHILD" => array(
            "ID" => "budget",
            "NAME" => "Бюджетирование",
            "SORT" => 30,
        )
    )
);
?>