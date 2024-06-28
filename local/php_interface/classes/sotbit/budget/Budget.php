<?php

namespace Budget;

use Develop\Helper\DateManager;
use Develop\Helper\CurrencyManager;
class Budget extends DateManager
{
    protected array $userData;
    protected array $cashMonth = [];
    protected array $cashYear = [];

    protected string $currentMonth = '';

    protected string $currentYear = '';
    protected array $budgets = [];

    const ORDER_DATE = ['MONTH', 'YEAR'];

    public function __construct($userData)
    {
        $this->userData = $userData;
        $this->calculateCashUser($userData);
        $this->calculateSendCash();
    }

    protected function calculateCashUser($userData): void
    {
        $month = static::getSimpleDate('m');
        $year = static::getSimpleDate('Y');

        foreach ($userData as $data) {

            if ($data['ORDER_STATUS'] != 'F') continue;

            $this->currentMonth = static::getDate('m', $data['TIME_STAMP']);
            $this->currentYear = static::getDate('Y', $data['TIME_STAMP']);

            if ($month == $this->currentMonth && $year == $this->currentYear) {
                $this->cashMonth[] = $data['MONEY'];
            }

            if ($year == $this->currentYear) {
                $this->cashYear[] = $data['MONEY'];
            }
        }
    }

    public function calculateSendCash(): array
    {
        $baseCurrency = CurrencyManager::getBaseCurrency();

        $sumMonth = array_sum($this->cashMonth);
        $sumYear = array_sum($this->cashYear);

        $this->budgets['ALL_SUM'] = [
            'MONTH' => $sumMonth,
            'YEAR' => $sumYear,
        ];
        $this->budgets['ALL_SUM_FORMATTED'] = CurrencyManager::currencyFormat(
            [$sumMonth, $sumYear],
            $baseCurrency,
            self::ORDER_DATE,
            true
        );

        return $this->budgets;
    }

}