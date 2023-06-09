<?php

namespace App\Http\Controllers;

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
            'user' => $request->user(),
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
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:' . Task::class],
                'description' => 'nullable|string',
                'status_id' => 'required|exists:task_statuses,id',
                'assigned_to_id' => 'nullable|exists:users,id',
                'labels' => 'nullable|array',
            ],
            $messages = ['unique' => 'Задача с таким именем уже существует']
        );

        Task::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status_id' => $request->get('status_id'),
            'assigned_to_id' => $request->get('assigned_to_id'),
            'created_by_id' => Auth::id(),
        ]);
        flash('Задача успешно создана')->success();
        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $task = Task::findOrFail($id);
        $taskLabels = $task->labels()->get();
        return view('pages.task-show', [
            'task' => $task,
            'taskLabels' => $taskLabels->count() > 0 ? $taskLabels : [],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);
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
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('tasks')->ignore($id)
                ],
                'description' => 'nullable|string',
                'status_id' => 'required|exists:task_statuses,id',
                'assigned_to_id' => 'nullable|exists:users,id',
                'labels' => 'nullable|array',
            ],
            $messages = ['unique' => 'Задача с таким именем уже существует']
        );
        $task = Task::findOrFail($id);
        $this->authorize('update', $task);

        $task->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status_id' => $request->get('status_id'),
            'assigned_to_id' => $request->get('assigned_to_id'),
        ]);

        $toManyLabelsTask = LabelTask::select('label_id')->where('task_id', $task['id'])->get()->toArray();

        $labels = $request->get('labels');

        if (is_array($labels) && count($labels) > 0) {
            array_map(fn($label) => !in_array($label, $toManyLabelsTask, true) ? LabelTask::insert([
                'label_id' => $label,
                'task_id' => $task->id]) : true, $labels);
        }
        array_map(fn($label) => !in_array($label, $labels, true)
            ? $task->labels()->detach($label)
            : true, $toManyLabelsTask);
        flash('Задача успешно изменена')->success();
        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('delete', $task);
        if (Auth::id() === $task->created_by_id) {
            $task->delete();
            flash('Задача успешно удалена')->success();
            return redirect('/tasks');
        }
        return redirect('/tasks');
    }
}
