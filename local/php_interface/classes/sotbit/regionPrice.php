<?php

namespace Sotbit\Custom\Price;

use Bitrix\Catalog\GroupAccessTable;
use Bitrix\Catalog\GroupTable;

class RegionPrice
{
    protected static $priceTypeList = ['BASE', 'OPT', 'SMALL_OPT'];


    public static function getAvailablePriceTypes($type = [1, 2]): array
    {
        $availableType = [];
        $resultPriceTypeList = [];


        $pricesId = self::getTypePriceRegion();
        if (!empty($pricesId)) {
            $availablePriceList = GroupAccessTable::getList([
                'filter' => [
                    'GROUP_ID' => $type,
                    'CATALOG_GROUP_ID' => $pricesId['ID'],
                    'ACCESS' => 'Y'
                ],
                'select' => ['CATALOG_GROUP_ID']
            ]);

            while ($price = $availablePriceList->fetch()) {
                $availableType[] =  $price['CATALOG_GROUP_ID'];
            }
            if (!empty($availablePriceList)) {

                foreach ($pricesId['ID'] as $key => $item) {
                    if (in_array($item, $availableType)) {
                        $resultPriceTypeList[] = [
                           'ID' => $item,
                            'NAME' => $pricesId['NAME'][$key],
                            'CAN_BUY' => 'Y'
                        ];
                    } else {
                        $resultPriceTypeList[] = [
                            'ID' => $item,
                            'NAME' => $pricesId['NAME'][$key],
                            'CAN_BUY' => 'N'
                        ];
                    }
                }
            }
        }

        return $resultPriceTypeList;
    }

    private static function getTypePriceRegion(): array
    {
        $priceGroup = [];

        $groups = GroupTable::getList([
            'filter' => [
                'NAME' => self::$priceTypeList,
            ],
            'select' => ['ID', 'NAME'],
        ]);

        while ($group = $groups->fetch()) {
            $priceGroup['ID'][] = $group['ID'];
            $priceGroup['NAME'][] = $group['NAME'];
        }

        return $priceGroup;
    }
}