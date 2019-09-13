<?php

namespace App\Http\Controllers;

use App\Helpers\Chart as ChartHelper;
use App\Repositories\Project as ProjectRepository;
use App\Traits\HandleException;
use Illuminate\Http\JsonResponse;
use JWTAuth;

/**
 * Class AnalyticController
 *
 * @package App\Http\Controllers
 */
class AnalyticController
{
    use HandleException;

    /**
     * @var string
     */
    private $permission = 'analytics';

    /**
     * @return JsonResponse
     */
    public function getInformationsActive()
    {

        if (!auth()->user()->can($this->permission . '-list')) {
            return response()->json([
                'error'   => true,
                'message' => 'Você não tem permissão para essa ação'
            ], 403);
        }

        $projectIds = ProjectRepository::getAllProjectsId();
        $projects   = ProjectRepository::getAllProjectsTime();

        $data = [];

        foreach ($projects as $key => $project) {
            $data[] = [
                'title'    => $projectIds[$key] ?? 'Não encontrado, atualize o Cache',
                'value'    => ChartHelper::getValueTime($project),
                'minValue' => 0,
                'maxValue' => ChartHelper::gaugeMaxValue($project),
            ];
        }

        return response()->json([
            'error'   => false,
            'message' => '',
            'content' => $data,
        ]);

    }
}