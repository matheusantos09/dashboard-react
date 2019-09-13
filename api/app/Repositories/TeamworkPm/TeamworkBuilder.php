<?php

namespace App\Repositories\TeamworkPm;

use TeamWorkPm\Auth as TeamWorkPmAuth;

/**
 * Class TeamworkBuilder
 *
 * @package App\Repositories\TeamworkPm
 */
class TeamworkBuilder
{
    /**
     * @param $class_name
     *
     * @return mixed
     */
    public static function build($class_name)
    {
        $class_name = str_replace(['/', '.'], '\\', $class_name);
        $class_name = preg_replace_callback('/(\\\.)/', function ($matches) {
            return strtoupper($matches[1]);
        }, $class_name);
        $class_name = ucfirst($class_name);
        if (strcasecmp($class_name, 'task\\list') === 0) {
            $class_name = 'Task_List';
        }
        $class_name = '\\' . __NAMESPACE__ . '\\' . $class_name;

        return forward_static_call_array([$class_name, 'getInstance'], TeamWorkPmAuth::get());
    }
}