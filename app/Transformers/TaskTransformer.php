<?php

namespace App\Transformers;

use App\Models\Task;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Task $task)
    {
        return [
            'body' => $task->body,
            'completed' => $task->completed,
            'created_at' => Carbon::parse($task->created_at)->format('d/m/Y H:i'),
            'updated_at' => Carbon::parse($task->updated_at)->format('d/m/Y H:i'),
        ];
    }
}
