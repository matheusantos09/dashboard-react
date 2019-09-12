<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Project
 *
 * @package App
 */
class Project extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'estimate_time',
        'support_hours',
        'task_list_id',
    ];

}
