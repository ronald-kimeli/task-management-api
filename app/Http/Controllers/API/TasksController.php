<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Requests\Task\ImageTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource with filtering and pagination.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        
        $totalRecords = Task::count();

        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('due_date')) {
            $query->where('due_date', $request->input('due_date'));
        }

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        $page = $request->input('page', 1);
        $perPage = 10;

        $tasks = $query->paginate($perPage, ['*'], 'page', $page);

        if ($tasks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tasks found matching the given criteria',
                'total_records' => $totalRecords,
                'data' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Tasks fetched successfully',
            'data' => TaskResource::collection($tasks),
            'total_records' => $totalRecords,
            'pagination' => [
                'total' => $tasks->total(),
                'current_page' => $tasks->currentPage(),
                'per_page' => $tasks->perPage(),
                'last_page' => $tasks->lastPage(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl()
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
            'due_date' => $validated['due_date'],
        ]);

        if ($request->has('user_ids')) {
            $task->users()->attach($request->user_ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => new TaskResource($task)
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        $task->load(['category:id,name', 'users:id,name']);
        return response()->json([
            'success' => true,
            'message' => 'Task fetched successfully',
            'data' => new TaskResource($task)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Task\UpdateTaskRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $validated = $request->validated();

            $task->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'category_id' => $validated['category_id'],
                'status' => $validated['status'],
                'due_date' => $validated['due_date'],
            ]);

            if ($request->has('user_ids')) {
                $task->users()->sync($request->user_ids);
            }

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'data' => new TaskResource($task)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of the specified resource in storage.
     *
     * @param  \App\Http\Requests\Task\UpdateTaskStatusRequest  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        try {
            $validated = $request->validated();

            $task->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Task Status updated successfully',
                'data' => new TaskResource($task)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating task status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        try {
            $task->users()->detach(); 
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the task.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
