<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tasks = Task::paginate(15);

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
                'users' => User::all(),
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

        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('pages.task-show', [
            'task' => Task::find($id),
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
        return view('pages.task', [
            'task' => Task::findOrFail($id),
            'statuses' => TaskStatus::all(),
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
        ]);

        $task = Task::findOrFail($id);
        $task->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status_id' => $request->get('status_id'),
            'assigned_to_id' => $request->get('assigned_to_id'),
        ]);

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
        $task->delete();
        return redirect('/tasks');
    }
}
