<?php

namespace Custom\Currency;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\Query\Join;

class CurrencyTable extends Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     * @return string
     */
    public static function getTableName()
    {
        return 'sotbit_currency_users';
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
            'CURRENCY' => new Entity\StringField('CURRENCY', []),
        );
    }
}
