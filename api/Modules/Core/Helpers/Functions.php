<?php

namespace App\Helpers;

/**
 * Class Functions
 *
 * @package App\Helpers
 */
class Functions
{

    /**
     * @param        $min
     * @param string $format
     * @param bool   $convertDecimalHours
     *
     * @return float|string|void
     */
    public static function convertMinsToHours($min, $format = '%02d:%02d', $convertDecimalHours = false)
    {
        if ($min < 1) {
            return;
        }
        $hours   = floor($min / 60);
        $minutes = ($min % 60);

        if ($convertDecimalHours) {
            return round($hours + ($minutes / 60), 2);
        }

        return sprintf($format, $hours, $minutes);

    }

    /**
     * @param $time
     *
     * @return float
     */
    public static function timeToDecimal($time)
    {

        if (strpos($time, ':')) {

            $time = explode(':', $time);

            $mins = $time[1] > 0 ? 100 / (60 / $time[1]) : 0;

            $time = $time[0] . '.' . $mins;

            $decTime = $time;

        } else {

            $decTime = $time;

        }

        return (float)$decTime;
    }
}