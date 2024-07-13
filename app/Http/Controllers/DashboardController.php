<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\View\Components\Table\CheckoutList;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Define table headers
        $thead = [
            [
                'class' => "table__cell--checkbox table__cell--no-wrap",
                'value' => "",
            ],
            [
                'class' => "table__cell--title table__cell--no-wrap",
                'value' => "Название", // Title in Russian, adjust as needed
            ],
            [
                'class' => "table__cell--remainder",
                'value' => "Описание" // Description in Russian, adjust as needed
            ],
            [
                'class' => "table__cell--remainder",
                'value' => "Дата" // Date in Russian, adjust as needed
            ],
        ];

        // Initialize table body
        $tbody = [];

        // Fetch tasks from database, ordered by date ascending
        $tasks = Task::orderBy('date', 'asc')->get(['id', 'title', 'description', 'date']);

        // Transform tasks using TaskResource
        $formattedTasks = TaskResource::collection($tasks)->resolve();

        // Build table body rows
        foreach ($formattedTasks as $task) {
            $tbody[] = [
                [
                    'class' => "task__id",
                    'name' => 'table__cell-id',
                    'key' => $task['checkbox']['key']
                ],
                [
                    'class' => 'table__cell--title table__cell--no-wrap task__title',
                    'value' => $task['title']['value']
                ],
                [
                    'class' => 'table__cell--remainder task__description',
                    'value' => $task['description']['value']
                ],
                [
                    'class' => 'table__cell--remainder task__date',
                    'value' => $task['date']['value'] ? Carbon::parse($task['date']['value'])->format('d.m.Y') : null
                ],
            ];
        }

        // Create CheckoutList component with table data
        $table = CheckoutList::setDataTable($thead, $tbody);

        // Return view with table data
        return view('dashboard.index', ['table' => $table]);
    }
}
