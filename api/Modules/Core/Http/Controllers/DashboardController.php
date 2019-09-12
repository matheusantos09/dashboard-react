<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Repository\Project as ProjectRepository;

/**
 * Class DashboardController
 *
 * @package Modules\Core\Http\Controllers
 */
class DashboardController
{

    /**
     * @return JsonResponse
     */
    public function getInformations()
    {

        if (Cache::has('dashboard.infos')) {

            $data = Cache::get('dashboard.infos');
            return response()->json([
                'error'   => false,
                'content' => [
                    'smallBoxes'    => $data['smallBoxes'],
                    'doughnutChart' => $data['doughnutChart'],
                    'barChart'      => $data['barChart']
                ]
            ]);
        }

        $projectRepo = new ProjectRepository();

        $projects = $projectRepo->all();

        $projectStatus    = [];
        $projectCategory  = [];
        $projectTop       = [];
        $projectCompleted = [];
        $titlesCategories = [];
        $colorsCategories = [];
        $totalCategories  = [];
        $titlesTop        = [];
        $colorsTop        = [];
        $totalTop         = [];
        $totalCompleted   = [];

        foreach ($projects as $project) {
            $projectStatus[$project['subStatus']][] = $project;

            $category = (integer)$project['category']['id'];

            if ($category) {
                if (isset($projectCategory[$category]['total'])) {
                    $projectCategory[$category]['total']++;
                } else {
                    $projectCategory[$category]['total'] = 1;
                    $projectCategory[$category]['nome']  = $project['category']['name'];
                    $projectCategory[$category]['color'] = '#' . dechex(rand(0x000000, 0xFFFFFF));
                }
            }

            if ((integer)$project['timeAll']['order'] > 0) {
                $projectTop[$project['id']]['order'] = $project['timeAll']['order'];
                $projectTop[$project['id']]['nome']  = $project['name'];
                $projectTop[$project['id']]['color'] = '#' . dechex(rand(0x000000, 0xFFFFFF));
            }
        }

        foreach ($projectCategory as $category) {
            $titlesCategories[] = $category['nome'];
            $colorsCategories[] = $category['color'];
            $totalCategories[]  = $category['total'];
        }

        $this->array_sort_by_column($projectTop, 'order');

        $maximumData = 5;
        $startCount  = 1;

        foreach ($projectTop as $top) {

            if ($startCount > $maximumData) {
                break;
            }

            $titlesTop[] = $top['nome'];
            $colorsTop[] = $top['color'];
            $totalTop[]  = floor($top['order'] / 60);

            $startCount++;
        }

        if (isset($projectStatus['completed'])) {
            foreach ($projectStatus['completed'] as $comp) {
                if (!empty($comp['date-archived'])) {
                    $ano = date('Y', strtotime($comp['date-archived']));
                    $mes = date('m', strtotime($comp['date-archived']));

                    if (isset($projectCompleted[$ano][$mes])) {
                        $projectCompleted[$ano][$mes]++;
                    } else {
                        $projectCompleted[$ano][$mes] = 1;
                    }
                }
            }
        }

        /* Mount SmallBox
          Example:
          <SmallBox
            value="999"
            title='Projetos ativos'
            colorBox='bg-success'
            iconBox='ion-ios-compose-outline'
           />
        */

        $smallBoxes = [
            [
                'value'    => isset($projectStatus['current']) ?
                    count($projectStatus['current']) : 0,
                'title'    => 'Projetos Ativos',
                'colorBox' => 'bg-success',
                'iconBox'  => 'ion-ios-compose-outline'
            ],
            [
                'value'    => isset($projectStatus['late']) ?
                    count($projectStatus['late']) : 0,
                'title'    => 'Projetos abertos',
                'colorBox' => 'bg-warning',
                'iconBox'  => 'ion-ios-paper-outline',
            ],
            [
                'value'    => isset($projectStatus['completed']) ?
                    count($projectStatus['completed']) : 0,
                'title'    => 'Projetos concluídos',
                'colorBox' => 'bg-info',
                'iconBox'  => 'ion-ios-checkmark',
            ],
            [
                'value'    => count($projects),
                'title'    => 'Todos os projetos',
                'colorBox' => 'bg-purple',
                'iconBox'  => 'ion-ios-browsers-outline',
            ],
        ];

        /*
          Mount DoughnutChart
          <DoughnutChart
            options={{
                maintainAspectRatio: false,
                legend: {
                    fullWidth: true,
                    position: 'bottom',

                    labels: {
                        usePointStyle: true,
                    }
                },
            }}
            data={height: "500",
                    labels: [
                        'Red Red RedRed Red',
                        'Blue',
                        'Yellow'
                    ],
                    datasets: [{
                        data: [300, 50, 100],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56'
                        ],
                        hoverBackgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56'
                        ]
                    }]}
            />
         */

        $doughnutChart = [
            [
                'title'   => 'Top projetos',
                'options' => [
                    'maintainAspectRatio' => false,
                    'legend'              => [
                        'fullWidth' => true,
                        'position'  => 'bottom',

                        'labels' => [
                            'usePointStyle' => true,
                        ]
                    ],
                ],
                'data'    => [
                    'labels'   => $titlesTop,
                    'datasets' => [
                        [
                            'data'            => $totalTop,
                            'backgroundColor' => $colorsTop
                        ]
                    ]
                ]
            ],
            [
                'title'   => 'Projetos por categoria',
                'options' => [
                    'maintainAspectRatio' => false,
                    'legend'              => [
                        'fullWidth' => true,
                        'position'  => 'bottom',

                        'labels' => [
                            'usePointStyle' => true,
                        ]
                    ],
                ],
                'data'    => [
                    'labels'   => $titlesCategories,
                    'datasets' => [
                        [
                            'data'            => $totalCategories,
                            'backgroundColor' => $colorsCategories
                        ]
                    ]
                ]
            ]
        ];

        $ano              = date('Y');
        $totalCompleted[] = $projectCompleted[$ano]['01'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['02'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['03'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['04'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['05'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['06'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['07'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['08'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['09'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['10'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['11'] ?? 0;
        $totalCompleted[] = $projectCompleted[$ano]['12'] ?? 0;

        /*
            Mount Bar Chart

             <BarChart
                    options={{
                        legend: {
                            position: 'bottom',
                        },
                    }}
                    data={
                         labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                         datasets: [
                            {
                                label: 'My First dataset',
                                backgroundColor: 'rgba(255,99,132,0.2)',
                                borderColor: 'rgba(255,99,132,1)',
                                borderWidth: 1,
                                hoverBackgroundColor: 'rgba(255,99,132,0.4)',
                                hoverBorderColor: 'rgba(255,99,132,1)',
                                data: [65, 59, 80, 81, 56, 55, 40]
                            }
                        ]
                   }
            />
         */

        $barChart = [
            [
                'title'   => 'Projetos concluídos',
                'options' => [
                    'legend' => [
                        'display' => false,
                    ]
                ],
                'data'    => [
                    'labels'   => [
                        'Jan',
                        'Fev',
                        'Mar',
                        'Abr',
                        'Mai',
                        'Jun',
                        'Jul',
                        'Ago',
                        'Set',
                        'Out',
                        'Nov',
                        'Dez'
                    ],
                    'datasets' => [
                        [
                            'label'                => 'Projetos concluídos',
                            'backgroundColor'      => 'rgba(255,99,132,0.2)',
                            'borderColor'          => 'rgba(255,99,132,1)',
                            'borderWidth'          => 1,
                            'hoverBackgroundColor' => 'rgba(255,99,132,0.4)',
                            'hoverBorderColor'     => 'rgba(255,99,132,1)',
                            'data'                 => $totalCompleted
                        ]
                    ]
                ]
            ]
        ];

        /* Salve data in cache */

        Cache::put('dashboard.infos',
            [
                'smallBoxes'    => $smallBoxes,
                'doughnutChart' => $doughnutChart,
                'barChart'      => $barChart
            ],
            config('constants.cache.dashboard_informations'));

        return response()->json([
            'error'   => false,
            'content' => [
                'smallBoxes'    => $smallBoxes,
                'doughnutChart' => $doughnutChart,
                'barChart'      => $barChart
            ]
        ]);

    }

    /**
     * @param     $arr
     * @param     $col
     * @param int $dir
     */
    public function array_sort_by_column(&$arr, $col, $dir = SORT_DESC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}