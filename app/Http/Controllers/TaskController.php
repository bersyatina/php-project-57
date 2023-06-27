<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Label;
use App\Models\LabelTask;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::exact('created_by_id'),
            ])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pages.tasks', [
            'users' => User::all(),
            'tasks' => $tasks,
            'statuses' => TaskStatus::all(),
            'filter' => $request->get('filter') ?? [
                'status_id' => '',
                'assigned_to_id' => '',
                'created_by_id' => ''
                ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Task::class);
        return view('pages.task', [
            'task' => new Task(),
            'statuses' => TaskStatus::all(),
            'labels' => Label::all(),
            'users' => User::all(),
            'taskLabels' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('store', Task::class);
        $validated = $request->validated();
        $createdById = Auth::id();
        $data = [...$validated, 'created_by_id' => $createdById];

        $task = new Task();
        $task->fill($data);
        $task->save();

        if (array_key_exists('labels', $validated)) {
            $task->labels()->attach($validated['labels']);
        }

        $message = __('controllers.tasks_create');
        flash($message)->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $taskLabels = $task->labels()->get();
        return view('pages.task-show', [
            'task' => $task,
            'taskLabels' => $taskLabels->count() > 0 ? $taskLabels : [],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $this->authorize('view', $task);
        $taskLabels = $task->labels()->select('id')->get()->toArray();
        $taskLabels = array_map(fn($item) => $item['id'], $taskLabels);
        return view('pages.task', [
            'task' => $task,
            'statuses' => TaskStatus::all(),
            'labels' => Label::all(),
            'taskLabels' => $taskLabels,
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validated();
        $createdById = $task->created_by_id;
        $data = [...$validated, 'created_by_id' => $createdById];

        $task->fill($data);

        if (array_key_exists('labels', $validated)) {
            $task->labels()->sync($validated['labels']);
        }
        $task->save();

        $message = __('controllers.tasks_update');
        flash($message)->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        if (Auth::id() === $task->created_by_id) {
            $task->labels()->detach();
            $task->delete();
            flash(__('controllers.tasks_destroy'))->success();
        } else {
            flash(__('tasks_destroy_failed'))->error();
        }
        return redirect()->route('tasks.index');
    }
}
