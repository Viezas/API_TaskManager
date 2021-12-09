<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Transformers\TaskTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->completed) {
            if($request->completed !== "true" && $request->completed !== "false" && $request->completed !== "1" && $request->completed !== "0") {
                return response()->json([
                    'error' => Config::get('error.filter')
                ], 400);
            }
            $tasks = Task::where('completed', $request->completed)->where('user_id', Auth::user()->id)->get();
        } else {
            $tasks = Task::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        }

        return response()->json(fractal()
                ->collection($tasks)
                ->transformWith(new TaskTransformer)
                ->toArray(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $validated = $request->validated();

        $validated['user_id'] = Auth::user()->id;
        $task = Task::create($validated);

        return response()->json([
            'message' => 'Tâche crée'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->id)->get();
        if(count($task) == 0){
            return response()->json([
                'error' => Config::get('error.task')
            ], 404);
        }
        
        return response()->json(fractal()
                ->collection($task)
                ->transformWith(new TaskTransformer)
                ->toArray(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, int $id)
    {
        $validated = $request->validated();

        $task = Task::where('id', $id)->where('user_id', Auth::user()->id)->get();
        if(count($task) == 0){
            return response()->json([
                'error' => Config::get('error.task')
            ], 404);
        }
        
        Task::where('id', $id)->where('user_id', Auth::user()->id)->update($validated);
        return response()->json([
            'message' => 'Tâche mise à jour'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->id)->get();
        if(count($task) == 0){
            return response()->json([
                'error' => Config::get('error.task')
            ], 404);
        }
        Task::where('id', $id)->delete();
        return response()->noContent();
    }
}
