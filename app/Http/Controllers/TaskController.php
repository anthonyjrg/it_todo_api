<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\TaskCreated;

class TaskController extends Controller implements ShouldBroadcast
{
    function create(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'location' => 'string'
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->task_description = $request->description;
        $task->location = $request->location;
        $task->assigned_to = $request->assigned_to;
        $task->due_date = $request->due_date;
        $task->created_by = Auth::user()->id;

        //Save task
        $result = Auth::user()->tasks()->save($task);

        if($result)
            event(new TaskCreated($task->title));


        return response()->json($result);
    }

    function delete(Request $request){
        $request->validate([
            'id' => 'required',
        ]);

        //Save task
        $result = Task::find($request->input('id'))->delete();

        return response()->json($result);
    }

    function update(Request $request){
        $request->validate([
            'id' => 'required|integer',
            'completed_date' => 'nullable',
            'resolution' => 'nullable'
        ]);

        $task = Task::find($request->id);
        $task->title = $request->input('title');
        if($request->input('completed_date') != 'null' || $request->input('completed_date') == null)
            $task->completed_date = $request->input('completed_date');

        $task->resolution_description = $request->input('resolution');
        if($request->input('due_date')!=null)
            $task->due_date = $request->input('due_date');
        $task->location = $request->input('location');
        $task->assigned_to = json_decode($request->input('assigned_to'));
        if($request->input('completed_date')!=null)
            $task->completed_by = Auth::user()->id;

        return $task->save();
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
        return Task::taskAtLocation($location, true)->sortBy(
            "due_date"
        )->values();
    }

    function incompleteTaskByLocation(Request $request){
        $location = $request->input('location');
        return Task::taskAtLocation($location, false)->sortBy(
                "created_at"
            )->values();
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

    public function broadcastOn()
    {
        // TODO: Implement broadcastOn() method.
    }
}
