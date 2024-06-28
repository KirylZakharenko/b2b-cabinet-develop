<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => 'Блок бюджетирования',
    "DESCRIPTION" => 'Отображение блока бюджетирования',
    "ICON" => "/images/catalog.gif",
    "PATH" => array(
        "ID" => "b-profile",
        "CHILD" => array(
            "ID" => "budget_list",
            "NAME" => "Список бюджетирования",
        )
    )
);
?>