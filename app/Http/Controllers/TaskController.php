<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
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
        return response()->json($task);
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
