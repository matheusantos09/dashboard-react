<?php

namespace App\Http\Controllers;

use App\Helpers\Chart as ChartHelper;
use App\Repositories\Project as ProjectRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProjectController
 *
 * @package App\Http\Controllers
 */
class ProjectController
{
    /**
     * @var string
     */
    private $permission = 'analytics';

    /**
     * @param         $id
     * @param Request $request
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function saveEstimateTime($id, Request $request)
    {
        try {

            if (!auth()->user()->can($this->permission . '-list')) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Você não tem permissão para essa ação'
                ], 403);
            }

            $projectIds = ProjectRepository::getAllProjectsId();

            if (!isset($projectIds[$id])) {
                return response()->json([
                    'error'   => true,
                    'message' => 'Projeto não encontrado, por favor atualize o cache'
                ], 404);
            }

            $project = ProjectRepository::setNewEstimateTime($id, $request);

            return response()->json([
                'error'   => false,
                'message' => 'Tempo estimado alterado',
                'content' => [
                    'estimateTime' => $project['totalHoursEstimated'],
                    'value'        => ChartHelper::getValueTime($project),
                    'maxValue'     => ChartHelper::gaugeMaxValue($project),
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error'   => false,
                'message' => $e->getMessage()
            ]);
        }

    }
}