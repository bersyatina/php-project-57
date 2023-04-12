<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\LabelTask;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        dd($_ENV);
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::exact('created_by_id'),
            ])
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
    public function create()
    {
        return Auth::user()
            ? view('pages.task', [
                'task' => new Task(),
                'statuses' => TaskStatus::all(),
                'labels' => Label::all(),
                'users' => User::all(),
                'taskLabels' => [],
            ])
            : abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()) {
            return abort(403);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

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
    public function show(string $id)
    {
        $task = Task::find($id);
        $taskLabels = $task->labels;
        return view('pages.task-show', [
            'task' => $task,
            'taskLabels' => $taskLabels,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()) {
            return abort(403);
        }
        $task = Task::findOrFail($id);
        $taskLabels = $task->labels;

        return view('pages.task', [
            'task' => $task,
            'statuses' => TaskStatus::all(),
            'labels' => Label::all(),
            'taskLabels' => $taskLabels->pluck('id')->toArray(),
            'users' => User::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!Auth::user()) {
            return abort(403);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
            'labels' => 'nullable|array',
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status_id' => $request->get('status_id'),
            'assigned_to_id' => $request->get('assigned_to_id'),
        ]);

        $toManyLabelsTask = LabelTask::where('task_id', $task->id)->get()->pluck('label_id')->toArray();

        if (!empty($labels = $request->get('labels'))) {
            array_map(fn($label) => !in_array($label, $toManyLabelsTask) ? LabelTask::insert([
                    'label_id' => $label,
                    'task_id' => $task->id]) : true, $labels);
        }
        array_map(fn($label) => !in_array($label, $labels) ? $task->labels()->detach($label) : true, $toManyLabelsTask);
        flash('Задача успешно изменена')->success();
        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::user()) {
            return abort(403);
        }
        $task = Task::find($id);

        if (Auth::id() === $task->created_by_id) {
            $task->delete();
            flash('Задача успешно удалена')->success();
            return redirect('/tasks');
        }
        return redirect('/tasks');
    }
}
