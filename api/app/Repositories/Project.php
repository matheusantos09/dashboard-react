<?php

namespace App\Repositories;

use App\Helpers\Functions;
use App\Helpers\Teamwork as TeamworkHelper;
use App\Repositories\TeamworkPm\TeamworkBuilder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Rossedman\Teamwork\Facades\Teamwork;
use TeamWorkPm\Auth as TeamWorkPmAuth;
use TeamWorkPm\Factory as TeamWorkPmFactory;

class Project
{
    /**
     * @return array|mixed
     */
    public static function getAllProjectsId()
    {

        if (Cache::has('projectsId')) {
            return unserialize(Cache::get('projectsId'));
        }

        $projects = Teamwork::project()->all(['status' => 'ALL']);
        $ids      = array();

        foreach ($projects['projects'] as $project) {
            $ids[$project['id']] = $project['name'];
        }

        Cache::put('projectsId', serialize($ids), config('constants.cache_data.projects_id'));

        return $ids;
    }

    /**
     * @return array|bool|mixed
     */
    public static function getAllProjectsTime()
    {

        if (Cache::has('projectsAllTime')) {
            return unserialize(Cache::get('projectsAllTime'));
        }

        $projectsAll = Teamwork::project()->all();
        $projects    = array();

        if ($projectsAll['STATUS'] !== 'OK') {
            return false;
        }

        foreach ($projectsAll['projects'] as $project) {
            $timeAll       = Teamwork::project((int)$project['id'])->timeTotal();
            $projectEntity = \App\Entities\Project::find($project['id']);
            $timeProject   = $timeAll['projects'][0]['time-estimates']['total-hours-estimated'];

            if ($projectEntity) {
                $timeProject = $projectEntity->estimate_time;
            }

            if ($timeAll['STATUS'] === 'OK') {

                $timeProject = TeamworkHelper::convertHours($timeProject);

                $projects[$project['id']] = array(
                    'totalHoursEstimated' => is_array($timeProject) ? $timeProject[0] : $timeProject,
                    'totalHours'          => TeamworkHelper::convertHours($timeAll['projects'][0]['time-totals']['total-hours-sum']),
                    'changedHours'        => $projectEntity ? true : false
                );
            }

        }

        Cache::put('projectsAllTime', serialize($projects), config('constants.cache_data.projects_times'));

        return $projects;

    }

    /**
     * @param $id
     *
     * @return array
     */
    public static function getSpecificProjectsTime($id)
    {

        $projects = unserialize(Cache::get('projectsAllTime'));

        if (isset($projects[$id])) {

            return array(
                'totalHoursEstimated' => $projects[$id]['totalHoursEstimated'],
                'totalHours'          => $projects[$id]['totalHours'],
                'changedHours'        => $projects[$id]['changedHours'],
            );

        }

        $timeAll = Teamwork::project($id)->timeTotal();

        if ($timeAll['STATUS'] === 'OK') {
            return array(
                'totalHoursEstimated' => $timeAll['projects'][0]['time-estimates']['total-hours-estimated'],
                'totalHours'          => TeamworkHelper::convertHours($timeAll['projects'][0]['time-totals']['total-hours-sum']),
            );
        }

        return [];

    }

    /**
     * @param $id
     *
     * @return array
     */
    public static function getSpecificProjectsTimeArchived($id)
    {

        $projects = unserialize(Cache::get('projectsAllTimeArchived'));

        if (isset($projects[$id])) {

            return array(
                'totalHoursEstimated' => $projects[$id]['totalHoursEstimated'],
                'totalHours'          => $projects[$id]['totalHours'],
                'changedHours'        => $projects[$id]['changedHours'],
            );

        }

        $timeAll = Teamwork::project($id)->timeTotal();

        if ($timeAll['STATUS'] === 'OK') {
            return array(
                'totalHoursEstimated' => $timeAll['projects'][0]['time-estimates']['total-hours-estimated'],
                'totalHours'          => TeamworkHelper::convertHours($timeAll['projects'][0]['time-totals']['total-hours-sum']),
            );
        }

        return [];

    }

    /**
     * @param $projectId
     * @param $request
     *
     * @throws Exception
     */
    public static function setNewEstimateTime($projectId, $request)
    {
        try {

            DB::beginTransaction();

            $projects = unserialize(Cache::get('projectsAllTime'));

            if (!isset($projects[$projectId])) {
                throw new Exception('Projeto não encontrado');
            }

            if (strpos($request->get('time'), ':')) {

                $time = explode(':', $request->get('time'));

                $mins = 100 / (60 / ($time[1] > 0 ? $time[1] : 1));

                $time = $time[0] . '.' . $mins;

                \App\Entities\Project::updateOrCreate([
                    'id' => $projectId
                ], [
                    'estimate_time' => round($time, 2)
                ]);

                $projects[$projectId]['totalHoursEstimated'] = $time;

            } else {

                \App\Entities\Project::updateOrCreate([
                    'id' => $projectId
                ], [
                    'estimate_time' => $request->get('time')
                ]);

                $projects[$projectId]['totalHoursEstimated'] = $request->get('time');

            }

            $projects[$projectId]['changedHours'] = true;

            Cache::put('projectsAllTime', serialize($projects), config('constants.cache_data.projects_times'));

            DB::commit();

        } catch (Exception $e) {

            DB::rollback();

            throw new Exception($e->getMessage());

        }

    }

    /**
     * @param $projectId
     * @param $request
     *
     * @throws Exception
     */
    public static function setNewEstimateTimeInative($projectId, $request)
    {
        try {

            DB::beginTransaction();

            $projects = unserialize(Cache::get('projectsAllTimeArchived'));

            if (!isset($projects[$projectId])) {
                throw new Exception('Projeto não encontrado');
            }

            if (strpos($request->get('time'), ':')) {

                $time = explode(':', $request->get('time'));

                $mins = 100 / (60 / ($time[1] > 0 ? $time[1] : 1));

                $time = $time[0] . '.' . $mins;

                \App\Entities\Project::updateOrCreate([
                    'id' => $projectId
                ], [
                    'estimate_time' => round($time, 2)
                ]);

                $projects[$projectId]['totalHoursEstimated'] = $time;

            } else {

                \App\Entities\Project::updateOrCreate([
                    'id' => $projectId
                ], [
                    'estimate_time' => $request->get('time')
                ]);

                $projects[$projectId]['totalHoursEstimated'] = $request->get('time');

            }

            $projects[$projectId]['changedHours'] = true;

            Cache::put('projectsAllTimeArchived', serialize($projects), config('constants.cache_data.projects_times'));

            DB::commit();

        } catch (Exception $e) {

            DB::rollback();

            throw new Exception($e->getMessage());

        }

    }

    /**
     * @return array|mixed
     */
    public static function getAllProjectsIdArchived()
    {

        if (Cache::has('projectsIdArchived')) {
            return unserialize(Cache::get('projectsIdArchived'));
        }

        TeamWorkPmAuth::set(config('constants.teamwork.API_KEY'));
        $projects = TeamWorkPmFactory::build('project');
        $projects = $projects->getArchived();

        $ids = array();

        foreach ($projects as $project) {
            $ids[$project['id']] = $project['name'];
        }

        Cache::put('projectsIdArchived', serialize($ids), config('constants.cache_data.projects_id_archived'));

        return $ids;
    }

    /**
     * @return array|bool|mixed
     */
    public static function getAllProjectsTimeArchived()
    {

        if (Cache::has('projectsAllTimeArchived')) {
            return unserialize(Cache::get('projectsAllTimeArchived'));
        }

        TeamWorkPmAuth::set(config('constants.teamwork.API_KEY'));
        $projectsAll = TeamWorkPmFactory::build('project');
        $projectsAll = $projectsAll->getArchived();

        $projects = array();

        foreach ($projectsAll as $project) {

            $timeAll       = self::getTimeTotal($project['id']);
            $projectEntity = self::projectFind($project['id']);
            $timeProject   = $timeAll['projects'][0]['time-estimates']['total-hours-estimated'];

            if ($projectEntity) {
                $timeProject = $projectEntity->estimate_time;
            }

            if ($timeAll['STATUS'] === 'OK') {

                $timeProject = TeamworkHelper::convertHours($timeProject);

                $projects[$project['id']] = array(
                    'totalHoursEstimated' => is_array($timeProject) ? $timeProject[0] : $timeProject,
                    'totalHours'          => TeamworkHelper::convertHours($timeAll['projects'][0]['time-totals']['total-hours-sum']),
                    'changedHours'        => $projectEntity ? true : false
                );
            }


        }

        Cache::put('projectsAllTimeArchived', serialize($projects), config('constants.cache_data.projects_times_archived'));

        return $projects;

    }

    /**
     * @param $projectId
     *
     * @return mixed
     */
    private static function getTimeTotal($projectId)
    {
        return Teamwork::project((int)$projectId)->timeTotal();
    }

    /**
     * @param $projectId
     *
     * @return mixed
     */
    private static function projectFind($projectId)
    {
        return \App\Entities\Project::find((int)$projectId);
    }

    /**
     * @param $request
     *
     * @return array|mixed
     */
    public static function getAllProjectsSupportHours($request)
    {
        if (Cache::has('projectSupportHours')) {
            return unserialize(Cache::get('projectSupportHours'));
        }

        $projectsArray = array();

        TeamWorkPmAuth::set(config('constants.teamwork.API_KEY'));

        $projects = \App\Entities\Project::where('support_hours', '<>', null)
            ->where('task_list_id', '<>', null)
            ->get();

        foreach ($projects as $project) {

            $tasksList = self::getAllTasksOfTaskList($project->task_list_id, null, true);

            $projectsArray[$project->id] = [
                'support_hours' => $project->support_hours,
                'total'         => isset($tasksList['total']) ? $tasksList['total'] : $tasksList[$project->task_list_id]['total']
            ];
        }

        Cache::put('projectSupportHours', serialize($projectsArray), config('constants.cache_data.projects_support_hours'));

        return $projectsArray;
    }

    /**
     * @param      $taskList
     * @param null $request
     * @param bool $returnOnlyValueSpecific
     *
     * @return mixed
     */
    public static function getAllTasksOfTaskList($taskList, $request = null, $returnOnlyValueSpecific = false)
    {
        $listAux = unserialize(Cache::get('projectsTaskOfTaskList'));

        if (!empty($listAux) && isset($listAux[$taskList])) {

            if ($returnOnlyValueSpecific) {
                return $listAux[$taskList];
            }

            return $listAux;
        }

        TeamWorkPmAuth::set(config('constants.teamwork.API_KEY'));

        $varAux = TeamworkBuilder::build('task_list_custom');

        $completedAfterDate  = Carbon::now()->firstOfMonth()->format('YmdHis');
        $completedBeforeDate = Carbon::now()->lastOfMonth()->format('Ymd') . '235959';

        if ($request) {
            $date                = explode('/', $request->get('date'));
            $completedAfterDate  = Carbon::createFromDate($date[1], $date[0])->startOfMonth()->format('YmdHis');
            $completedBeforeDate = Carbon::createFromDate($date[1], $date[0])->lastOfMonth()->format('Ymd') . '235959';
        }

        $varAux = $varAux->getAllTaskOnTaskList($taskList, [
            'includeCompletedTasks' => true,
            'completedAfterDate'    => $completedAfterDate,
            'completedBeforeDate'   => $completedBeforeDate
        ]);

        $timeTotal = 0;

        foreach ($varAux as $var) {

            $timeAux = TeamWorkPmFactory::build('time');
            $timeAux = json_decode($timeAux->getByTask($var['id']), true);

            $time = 0;

            if (is_array($timeAux)) {

                $min = 0;

                foreach ($timeAux as $aux) {
                    $min += ($aux['minutes'] + (60 * $aux['hours']));
                }

                $time = Functions::convertMinsToHours($min, '%02d:%02d', true);

                $timeTotal += $time;
            }

            $listAux[$taskList]['tasks'][] = [
                'id'      => $var['id'],
                'content' => $var['content'],
                'time'    => $time
            ];

        }

        $listAux[$taskList]['total'] = $timeTotal;

        Cache::put('projectsTaskOfTaskList', serialize($listAux), config('constants.cache_data.projects_task_list_times'));


        if ($returnOnlyValueSpecific) {
            return $listAux[$taskList];
        }

        return $listAux;

    }

    /**
     * @param $projectId
     */
    public static function removeProjectOfSupportHours($projectId)
    {
        $supportHours = unserialize(Cache::get('projectSupportHours'));

        if ($supportHours[$projectId]) {
            unset($supportHours[$projectId]);

            Cache::put('projectSupportHours', serialize($supportHours), config('constants.cache_data.projects_support_hours'));
        }
    }

    /**
     * @return array|mixed
     */
    public static function getAllTags()
    {

        if (Cache::has('projectsTags')) {
            return unserialize(Cache::get('projectsTags'));
        }

        TeamWorkPmAuth::set(config('constants.teamwork.API_KEY'));

        $tags = TeamWorkPmFactory::build('tag');
        $tags = $tags->getAllTags();

        $arrayAux = array();

        foreach ($tags as $tag) {
            $arrayAux[$tag['id']] = $tag['name'];
        }

        Cache::put('projectsTags', serialize($arrayAux), config('constants.cache_data.projects_tags'));

        return $arrayAux;

    }

    /**
     * @param      $projectId
     * @param bool $returnOnlyValueSpecific
     *
     * @return mixed
     */
    public static function getProjectTaskList($projectId, $returnOnlyValueSpecific = false)
    {
        $listAux = unserialize(Cache::get('projectsTaskList'));

        if (!empty($listAux) && isset($listAux[$projectId])) {

            if ($returnOnlyValueSpecific) {
                return $listAux[$projectId];
            }

            return $listAux;
        }

        TeamWorkPmAuth::set(config('constants.teamwork.API_KEY'));

        $varAux = TeamWorkPmFactory::build('task_list');
        $varAux = $varAux->getByProject($projectId);

        foreach ($varAux as $var) {
            $listAux[$projectId][] = [
                'id'   => $var['id'],
                'name' => $var['name'],
            ];
        }

        Cache::put('projectsTaskList', serialize($listAux), config('constants.cache_data.projects_task_list'));

        if ($returnOnlyValueSpecific) {
            return $listAux[$projectId];
        }

        return $listAux;

    }

    /**
     * @param $id
     *
     * @return bool|mixed
     */
    public function find($id)
    {
        $projectAll = $this->all();
        $project    = false;

        foreach ($projectAll as $item) {
            if ($id == $item['id']) {
                $project = $item;
            }
        }

        return $project;
    }

    /**
     * @return bool|mixed
     */
    public function all()
    {
        $projectAll = unserialize(Cache::get('projectAll'));

        if (empty($projectAll)) {
            $projectAll = Teamwork::project()->all(['status' => 'ALL']);

            if ($projectAll['STATUS'] != 'OK') {
                return false;
            }

            foreach ($projectAll['projects'] as $key => $project) {
                $timeAll = Teamwork::project((integer)$project['id'])->timeTotal();
                if ($timeAll['STATUS'] == 'OK') {
                    $projectAll['projects'][$key]['timeAll']['time-estimates'] = $timeAll['projects'][0]['time-estimates'];
                    $projectAll['projects'][$key]['timeAll']['time-totals']    = $timeAll['projects'][0]['time-totals'];
                    $projectAll['projects'][$key]['timeAll']['order']          = $timeAll['projects'][0]['time-totals']['total-mins-sum'];
                }
            }

            Cache::put('projectAll', serialize($projectAll['projects']), 7200);

            return $projectAll['projects'];
        }

        return $projectAll;
    }

}