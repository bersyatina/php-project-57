<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TaskStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request): View
    {
        return view('pages.statuses', [
            'statuses' => TaskStatus::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Auth::user()
            ? view('pages.status', [
                'status' => [],
            ])
            : abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): object
    {
        if (!Auth::user()) {
            return abort(403);
        }

        $request->validate([
            'name' => 'required|string'
        ]);

        TaskStatus::create([
            'name' => $request->get('name')
        ]);
        flash('Статус успешно создан')->success();
        return redirect('/task_statuses');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Auth::user()) {
            return abort(403);
        }
        return view('pages.status', [
            'status' => TaskStatus::findOrFail($id),
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
            'name' => 'required|string'
        ]);

        $status = TaskStatus::findOrFail($id);
        $status->update([
            'name' => $request->get('name')
        ]);
        flash('Статус успешно изменён')->success();
        return redirect('/task_statuses');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        if (!Auth::user()) {
            return abort(403);
        }
        $status = TaskStatus::find($id);
        if ($status->tasks->count() > 0) {
            flash('Не удалось удалить статус')->error();
            return redirect('/task_statuses');
        }
        $status->delete();
        flash('Статус успешно удалён')->success();
        return redirect('/task_statuses');
    }
}
