<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    function create(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->task_description = $request->description;
        $task->created_by = Auth::user()->id;

        //Save task
        $result = Auth::user()->tasks()->save($task);

        return response()->json($result);
    }

    function updateTaskStatus(Request $request){
        $request->validate([
            'id' => 'required|integer',
            'completed_date' => 'nullable|date',
            'resolution' => 'required'
        ]);

        $task = Task::find($request->id);
        $task->completed_date = $request->completed_date;
        $task->resolution_description = $request->resolution;

        return $task->save();
    }

    function incompleteTask(){
        return Task::incompleteTask();
    }

    function completeTask(){
        return Task::completeTask();
    }
}
