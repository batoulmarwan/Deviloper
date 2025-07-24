<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class TastController extends BaseController
{
    public function storeTask(StoreTaskRequest $request)
{   
    $data = $request->validated();
    $data['created_by'] = Auth::id(); 
    $data['is_completed'] = false;
    $task = Task::create($data);
    return $this->sendResponse(new TaskResource($task), 'Task recorded successfully',200);
}
    public function show($id)
    {
         $task = Task::find($id);
         if ($task) {
           return $this->sendResponse(new TaskResource($task), 'tasking successfuly', 200);
         }
        return $this->sendError('task not found', [], 404);
    }
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return response()->json($tasks);
    }
   public function update(Request $request, $id)
{
    
    
        $task = Task::findOrFail($id);
    
        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);
    
        $task->update($data);
    
        return response()->json($task);
    
    
       
}


    public function destroy($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

    public function markComplete($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $task->is_completed = !$task->is_completed;
        $task->save();

        return response()->json(['message' => 'Task status updated', 'task' => $task]);
    }
}
