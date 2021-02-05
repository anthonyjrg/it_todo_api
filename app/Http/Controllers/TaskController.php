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

    function completeTaskByLocation(Request $request){
        $location = $request->input('location');
        return Task::taskAtLocation($location, true)->values();
    }

    function incompleteTaskByLocation(Request $request){
        $location = $request->input('location');
        return Task::taskAtLocation($location, false)->values();
    }

    function taskListCount(){
        $taskcounts = [
            "CHB_Complete" => Task::taskAtLocation("CHB", true)->count(),
            "CHB_Incomplete" => Task::taskAtLocation("CHB", false)->count(),
            "JFK_Incomplete" => Task::taskAtLocation("JFK", false)->count(),
            "JFK_Complete" => Task::taskAtLocation("JFK", true)->count(),
            "MISC_Complete" => Task::taskAtLocation("MISC", true)->count(),
            "MISC_Incomplete" => Task::taskAtLocation("MISC", false)->count(),
            "User_Complete" => Auth::user()->completeTasks()->count(),
            "User_Incomplete" => Auth::user()->incompleteTasks()->count(),
        ];

        return $taskcounts;
    }
}
