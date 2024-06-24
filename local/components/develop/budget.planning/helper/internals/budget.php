<?php

namespace Develop\Budget\Helper\Internals;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;

class BudgetTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'develop_budget_users';
    }

    public static function getMap()
    {
        return array(
            'ID' => new Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
            )),
            'USER_ID' => new Entity\IntegerField('USER_ID', [
                'required' => true,
            ]),
            'MONEY' => new Entity\FloatField('MONEY', [
                'required' => true,
            ]),
            'TIME_STAMP' => new Entity\DatetimeField('TIME_STAMP', [
                'required' => true,
            ])
        );
    }
}
