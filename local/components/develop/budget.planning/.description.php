<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => 'Планирование бюджета',
    "DESCRIPTION" => 'Позволяет вести статистику своих финансов',
    "ICON" => "/images/catalog.gif",
    "COMPLEX" => "Y",
    "PATH" => array(
        "ID" => "b-profile",
        "CHILD" => array(
            "ID" => "budget",
            "NAME" => "Бюджетирование",
        )
    )
);
?>