<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        try {
            $tasks = Task::orderBy('created_at', 'desc')->get();
            return response()->json($tasks, 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to Fetch Tasks'
            ], 500);
        }
    }


    /**
     * Store a newly created task in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            // 1. Access Validated Request
            $validated = $request->validated();

            // 2. Store the result of the transaction
            $task = DB::transaction(function () use ($validated) {
                return Task::create($validated);
            });

            // 3. Return a response
            return response()->json([
                'message' => 'Task created successfully',
                'data' => $task
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to create task',
            ], 500);
        }
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            return response()->json($task, 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to Fetch Tasks'
            ], 500);
        }
    }


    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $data = $request->toArray();
            $task = Task::findOrFail($id);
            DB::transaction(function () use ($data, $task) {
                $task->update($data);
            });

            return response()->json(['message' => 'Task Updated'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update'], 500);
        }
    }

    /**
     * Update the status of a specified task in storage.
     */
    public function statusUpdate(Request $request, string $id)
    {

        try {
            $data = $request->toArray();
            $task = Task::findOrFail($id);
            DB::transaction(function () use ($data, $task) {
                $task->update($data);
            });

            return response()->json(['message' => 'Status Updated'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Failed to update'], 500);
        }
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json(['message' => 'task deleted'], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to delete Task'
            ], 500);
        }
    }
}
