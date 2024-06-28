<?php

use Bitrix\Main;
use Bitrix\Main\EventManager as EManager;



/**
 * @throws Main\ArgumentNullException
 * @throws Main\ArgumentOutOfRangeException
 * @throws Main\ArgumentException
 * @throws Main\ObjectPropertyException
 * @throws Main\SystemException
 * @throws Main\NotImplementedException
 */

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/currency.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/currency.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/budget/helper/config/budget.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/budget/helper/config/budget.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/budget/helper/internals/budget.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/classes/sotbit/budget/helper/internals/budget.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/functions/table.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/functions/table.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/events/basket.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/events/basket.php';
}

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/events/custom/basket.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/events/custom/basket.php';
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/required_classes.php';
