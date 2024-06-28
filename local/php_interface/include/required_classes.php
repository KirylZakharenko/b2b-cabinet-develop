<?php
use Bitrix\Main\Loader;

Loader::registerAutoloadClasses(
    null,
    array(
        'Budget\Budget' => '/local/php_interface/classes/sotbit/budget/Budget.php',
        'Budget\History\BudgetHistory' => '/local/php_interface/classes/sotbit/budget/BudgetHistory.php',
        'Develop\Helper\DateManager' => '/local/php_interface/classes/sotbit/budget/helper/DateManager.php',
        'Develop\Helper\CurrencyManager' => '/local/php_interface/classes/sotbit/budget/helper/CurrencyManager.php',
    )
);