<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
class TaskController extends Controller
{
    public function index(Request $request)
    {
        //$tasks = Task::all();
        //$tasks = Task::with('user')->latest()->paginate(10);
        $tasks = $request->user()->tasks()->with('user')->latest()->paginate(10);
        return TaskResource::collection($tasks);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'sometimes|in:pending,done',
            'priority' => 'sometimes|in:low,medium,high',
        ]);
        $task = Task::create($validated);
        return response()->json($task, 201); 
    }
    public function show(Task $task)
    {
        return new TaskResource($task);
    }
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:pending,done',
            'priority' => 'sometimes|in:low,medium,high',
        ]);
        $task->update($validated);
        return response()->json($task);
    }
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
