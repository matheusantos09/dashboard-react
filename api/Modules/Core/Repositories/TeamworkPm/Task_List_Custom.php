<?php


namespace App\Repository\TeamworkPm;


use TeamWorkPm\Exception;
use TeamWorkPm\Model;

/**
 * Class Task_List_Custom
 *
 * @package App\Repository\TeamworkPm
 */
class Task_List_Custom extends Model
{

    protected function init()
    {
        $this->fields = [
            'id' => true,
        ];
        $this->parent = 'tasks';
        $this->action = 'tasks';
    }

    /**
     * @param      $task_list
     * @param null $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getAllTaskOnTaskList($task_list, $params = null)
    {
        $task_list = (int)$task_list;
        if ($task_list <= 0) {
            throw new Exception('Invalid param task_list');
        }
        return $this->rest->get("tasklists/$task_list/$this->action", $params);
    }

}