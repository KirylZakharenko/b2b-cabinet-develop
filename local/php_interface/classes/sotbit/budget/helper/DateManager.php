<?php

namespace Develop\Helper;

class DateManager
{

    public static function getDate($format, $timeStamp): string
    {

        if (gettype($timeStamp) === 'object') {
            $date = date($format,$timeStamp->getTimestamp());
        } else {
            $date = date($format,$timeStamp);
        }

        return $date;
    }

    public static function getSimpleDate($format): string
    {
        return date($format);
    }

}