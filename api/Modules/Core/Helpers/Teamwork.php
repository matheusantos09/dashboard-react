<?php

namespace App\Helpers;

/**
 * Class ApiTeamwork
 *
 * @package App\Helpers
 */
class Teamwork
{

    //@TODO fazer o get das funçoes do filtro que serão salvas

    /**
     * @param $hours
     *
     * @return array|float
     */
    public static function convertHours($hours)
    {

        $hours = explode('.', $hours);

        if (!isset($hours[1])) {
            return $hours;
        }

        return (float)($hours[0] . '.' . (int)round(($hours[1] * 60) / 100, 0));

    }
}