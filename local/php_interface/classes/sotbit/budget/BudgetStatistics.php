<?php

namespace Budget\Statistics;

use Budget\History\BudgetHistory;


class BudgetStatistics extends BudgetHistory
{
    public function __construct($userData)
    {
        parent::__construct($userData);
    }
}