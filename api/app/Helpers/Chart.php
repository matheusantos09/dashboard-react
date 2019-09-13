<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

/**
 * Class Chart
 *
 * @package App\Helpers
 */
class Chart
{

    const MIN                    = 0;
    const TIME_EXCEEDED_MULTIPLE = 2;
    const PERCENT_AREA_WARNING   = 10;
    const LABEL_RANGE            = 10;

    public static function mountStaticZones($project, $supportHours = false)
    {

        if (!$supportHours) {
            $max              = 0;
            $phaseTimeProject = array();
            $arrayColor       = array(
                '#30B32D',
                '#FFDD00',
                '#F03E3E'
            );


            if (isset($project['totalHoursEstimated'])) {
                $max = (int)round($project['totalHoursEstimated']);
            }

            $phaseTimeProject[] = $max - (int)round((($max * self::TIME_EXCEEDED_MULTIPLE) * self::PERCENT_AREA_WARNING) / 100);
            $phaseTimeProject[] = (int)round($max + (($max * self::TIME_EXCEEDED_MULTIPLE) * self::PERCENT_AREA_WARNING) / 100);
            $phaseTimeProject[] = (int)round($max * self::TIME_EXCEEDED_MULTIPLE);

            if ($phaseTimeProject[2] < $project['totalHours']) {
                $phaseTimeProject[2] = $project['totalHours'];
            }

            $arrayData = array(
                array(
                    'strokeStyle' => (string)$arrayColor[0],
                    'min'         => (string)self::MIN,
                    'max'         => (string)$phaseTimeProject[0],
                    'height'      => (string)0.8,
                ),
                array(
                    'strokeStyle' => (string)$arrayColor[1],
                    'min'         => (string)$phaseTimeProject[0],
                    'max'         => (string)$phaseTimeProject[1],
                    'height'      => (string)1.2,
                ),
                array(
                    'strokeStyle' => (string)$arrayColor[2],
                    'min'         => (string)$phaseTimeProject[1],
                    'max'         => (string)$phaseTimeProject[2],
                    'height'      => (string)1.6,
                ),
            );

            return json_encode($arrayData);
        }

        $max              = 0;
        $phaseTimeProject = array();
        $arrayColor       = array(
            '#30B32D',
            '#FFDD00',
            '#F03E3E'
        );


        if (isset($project['support_hours'])) {
            $max = (int)round($project['support_hours']);
        }

        $phaseTimeProject[] = $max - (int)round((($max * self::TIME_EXCEEDED_MULTIPLE) * self::PERCENT_AREA_WARNING) / 100);
        $phaseTimeProject[] = (int)round($max + (($max * self::TIME_EXCEEDED_MULTIPLE) * self::PERCENT_AREA_WARNING) / 100);
        $phaseTimeProject[] = (int)round($max * self::TIME_EXCEEDED_MULTIPLE);

        if ($phaseTimeProject[2] < $project['total']) {
            $phaseTimeProject[2] = $project['total'];
        }

        $arrayData = array(
            array(
                'strokeStyle' => (string)$arrayColor[0],
                'min'         => (string)self::MIN,
                'max'         => (string)$phaseTimeProject[0],
                'height'      => (string)0.8,
            ),
            array(
                'strokeStyle' => (string)$arrayColor[1],
                'min'         => (string)$phaseTimeProject[0],
                'max'         => (string)$phaseTimeProject[1],
                'height'      => (string)1.2,
            ),
            array(
                'strokeStyle' => (string)$arrayColor[2],
                'min'         => (string)$phaseTimeProject[1],
                'max'         => (string)$phaseTimeProject[2],
                'height'      => (string)1.6,
            ),
        );

        return json_encode($arrayData);

    }

    public static function mountStaticLabel($project, $supportHours = false)
    {

        if (!$supportHours) {
            $max         = (int)round(self::calcMaxValue($project));
            $range       = $max === 1 ? 1 : self::LABEL_RANGE;
            $arrayReturn = array();

            if ($max < $project['totalHours']) {
                $max = (int)round($project['totalHours']);
            }

            $arrayReturn[] = 0;

            $stepValues  = $max / $range;
            $stepsValues = 0;

            for ($i = 0; $i < $range; $i++) {
                $stepsValues   += $stepValues;
                $arrayReturn[] = $stepsValues;
            }

            if ($arrayReturn[count($arrayReturn) - 1] !== $max) {
                $arrayReturn[] = round($max);
            }

            return json_encode($arrayReturn);
        }

        $max         = (int)round(self::calcMaxValue($project, true));
        $range       = $max === 1 ? 1 : self::LABEL_RANGE;
        $arrayReturn = array();

        if ($max < $project['total']) {
            $max = (int)round($project['total']);
        }

        $arrayReturn[] = 0;

        $stepValues  = $max / $range;
        $stepsValues = 0;

        for ($i = 0; $i < $range; $i++) {
            $stepsValues   += $stepValues;
            $arrayReturn[] = $stepsValues;
        }

        if ($arrayReturn[count($arrayReturn) - 1] !== $max) {
            $arrayReturn[] = round($max);
        }

        return json_encode($arrayReturn);

    }

    public static function calcMaxValue($project, $supportHours = false)
    {

        $max = 0;

        if (!$supportHours) {

            if (isset($project['totalHoursEstimated'])) {
                $max = $project['totalHoursEstimated'];
            }

            return (float)round($max, 2) * self::TIME_EXCEEDED_MULTIPLE;
        }

        if (isset($project['support_hours'])) {
            $max = $project['support_hours'];
        }

        return (float)round($max, 2) * self::TIME_EXCEEDED_MULTIPLE;

    }

    public static function getValueTime($project, $supportHours = false)
    {

        $value = 0;

        if (!$supportHours) {

            if (isset($project['totalHours'])) {
                $value = (float)round($project['totalHours'], 2);
            }

            return $value;
        }

        if (isset($project['total'])) {
            $value = (float)round($project['total'], 2);
        }

        return $value;
    }

    public static function getEstimateHours($project)
    {

        $max = 1;

        if (isset($project['totalHoursEstimated'])) {
            $max = $project['totalHoursEstimated'];
        }

        if (strpos($max, '.')) {

            $time = explode('.', $max);

            $mins = (60 * ($time[1] > 0 ? $time[1] : 1)) / 100;

            $time = $time[0] . '.' . $mins;

            return round($time, 2);

        }

        return $max;

    }

    public static function getEstimateHoursForm($project)
    {

        $max = 1;

        if (isset($project['totalHoursEstimated'])) {
            $max = $project['totalHoursEstimated'];
        }

        if (strpos($max, '.')) {

            $time = explode('.', $max);

            $mins = (60 * ($time[1] > 0 ? $time[1] : 1)) / 100;

            $time = $time[0] . '.' . $mins;

            return round($time, 2);

        }

        return $max . '.00';

    }

    public static function verifyProjectFilter($key)
    {

        if (!Cache::has('filterExcludedProjects')) {
            return false;
        }

        $filter = unserialize(Cache::get('filterExcludedProjects'));

        return in_array($key, $filter);

    }

    public static function verifyProjectChangeHoursEstimated($projectId)
    {

        $projects = unserialize(Cache::get('projectsAllTime'));

        if (isset($projects[$projectId]['changedHours']) && !$projects[$projectId]['changedHours']) {
            return true;
        }

    }

    public static function generateBlockSupportHours($project)
    {

        $supportHours = $project['support_hours'];
        $total        = $project['total'];

        $returnHtml = '';

        $returnHtml .= '
                        <p>Horas de Suporte Contratadas: <b class="time">' . self::mountShowTime($supportHours) . '</b></p>
                        <p>Horas de Suporte Utilizada: <b class="time">' . self::mountShowTime($total) . '</b></p>
                        ';
        if ($supportHours < $total) {

            $ultrapassado = $total - $supportHours;

            $returnHtml .= '
                         <p>Horas ultrapassadas: <b class="time red">' . self::mountShowTime($ultrapassado) . '</b></p>
                        ';
        } else {
            $disponivel = $supportHours - $total;

            $returnHtml .= '
                        <p>Horas de Suporte Disponível: <b class="time">' . self::mountShowTime($disponivel) . '</b></p>
                        ';
        }

        return $returnHtml;

    }

    private static function mountShowTime($time, $formatted = true)
    {

        $time = self::convertToHoursDecimal($time);

        if ($formatted) {
            if (strpos($time, ':')) {

                $time = explode(':', $time);

                $returnMount = '';

                if ($time[0] > 1) {
                    $returnMount .= $time[0] . ' horas';

                    if ($time[1] > 0) {
                        if ($time[1] > 1) {
                            $returnMount .= ' e ' . $time[1] . ' minutos';
                        } else {
                            $returnMount .= ' e ' . $time[1] . ' minuto';
                        }
                    }

                } else {
                    $returnMount .= $time[0] . ' hora';

                    if ($time[1] > 0) {
                        if ($time[1] > 1) {
                            $returnMount .= ' e ' . $time[1] . ' minutos';
                        } else {
                            $returnMount .= ' e ' . $time[1] . ' minuto';
                        }
                    }

                }

                return $returnMount;

            }
        }

        return $time;
    }

    public static function convertToHoursDecimal($time)
    {
        if (strpos($time, '.')) {

            $time = explode('.', $time);

            $mins = $time[1] > 0 ? (60 * $time[1]) / 100 : 0;

            $time = round($time[0]) . ':' . round($mins);

            return $time;

        }

        return $time;
    }

    public static function supportUsedHours($project, $formatted = true)
    {

        $total = $project['total'];

        return self::mountShowTime($total, $formatted);

    }

    public static function gaugeMaxValue($project, $supportHours = false)
    {

        $max = 0;

        if (!$supportHours) {

            if (isset($project['totalHoursEstimated'])) {
                $max = $project['totalHoursEstimated'];
            }

            $max       = (float)round($max, 2) * self::TIME_EXCEEDED_MULTIPLE;
            $valueTime = self::getValueTime($project);

            if ($valueTime > $max) {
                return $valueTime;
            }

            return (float)round($max, 2) * self::TIME_EXCEEDED_MULTIPLE;
        }

        if (isset($project['support_hours'])) {
            $max = $project['support_hours'];
        }

        return (float)round($max, 2) * self::TIME_EXCEEDED_MULTIPLE;

    }

}