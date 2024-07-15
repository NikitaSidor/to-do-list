<?php

namespace App\Http\Controllers;

use App\Events\DeleteTaskEvent;
use App\Events\DoneTaskEvent;
use App\Events\StoreTaskEvent;
use App\Events\UpdateTaskEvent;
use App\Http\Requests\Task\StoreRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $task = Task::create($data);

        event(new StoreTaskEvent($task));
        if ($request->ajax()) {
            // If the request is AJAX, return a JSON response
            return response()->json([
                'message' => 'Task created successfully',
                'id' => $task->id,
                'task' => TaskResource::make($task)->resolve(),
                'date_update' => $task->updated_at,
            ]);
        } else {
            // If it's a regular HTTP request, you can redirect or return a view
            return redirect()->route('dashboard')->with('success', 'Task created successfully');
        }
    }
    public function show(Request $request)
    {
        $task = Task::findOrFail($request->id);
        if ($request->ajax()) {
            // If the request is AJAX, return a JSON response
            return response()->json([
                'message' => 'Task created successfully',
                'id' => $task->id,
                'task' => TaskResource::make($task)->resolve(),
                'date_update' => $task->updated_at,
            ], 200);

        } else {
            // If it's a regular HTTP request, you can redirect or return a view
            return redirect()->route('dashboard')->with('success', 'Task created successfully');
        }
    }

    public function update(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $task = Task::find($validatedData['id']);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        try {
            // Update task with validated data
            $task->update($validatedData);
            event(new UpdateTaskEvent($task));
            return response()->json([
                'message' => 'Task updated successfully',
                'id' => $task->id,
                'task' => TaskResource::make($task)->resolve(),
                'date_update' => $task->updated_at,
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to update task: ' . $e->getMessage()], 500);
        }
    }
    public function updateDone(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'done' => 'required|boolean',
        ]);

        $id = $request->input('id');

        // Find the record in the database
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }
        $done = $request->input('done'); // Получаем значение done из запроса

        try {
            // Обновляем только поле done
            $task->done = $done;
            $task->save();

            // Отправляем событие, если необходимо
            event(new DoneTaskEvent($task));

            return response()->json([
                'message' => 'Task updated done successfully',
                'id' => $task->id,
                'done' => $task->done,
                'date_update' => $task->updated_at,
            ], 200);
        } catch (\Exception $e) {
            // Обрабатываем исключения или ошибки
            return response()->json(['error' => 'Failed to update task: ' . $e->getMessage()], 500);
        }
    }
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer', // Example validation rule for id
            // Add more validation rules as needed
        ]);

        // Retrieve the id from the request
        $id = $request->input('id');

        // Find the record in the database
        $record = Task::find($id);

        // Check if record exists
        if (!$record) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        try {
            $record->delete();
            event(new DeleteTaskEvent($id));

            return response()->json(['message' => 'Record deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            return response()->json(['error' => 'Failed to delete record: ' . $e->getMessage()], 500);
        }
    }
}
