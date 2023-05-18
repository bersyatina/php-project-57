<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        return view('pages.labels', [
            'labels' => Label::all(),
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Label::class);
        return view('pages.label', [
            'label' => new Label()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Label::class);

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'unique:' . Label::class],
                'description' => 'nullable|string'
            ],
            $messages = [
                'name.unique' => 'Метка с таким именем уже существует',
                'name.required' => 'Это обязательное поле'
            ],
        );

        Label::create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);
        flash('Метка успешно создана')->success();
        return redirect('/labels');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('create', Label::class);
        return view('pages.label', [
            'label' => Label::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('create', Label::class);

        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('labels')->ignore($id)
                ],
                'description' => 'nullable|string'
            ],
            $messages = ['unique' => 'Метка с таким именем уже существует']
        );

        $label = Label::findOrFail($id);
        $label->update([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);
        flash('Метка успешно изменена')->success();
        return redirect('/labels');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', Label::class);
        $label = Label::findOrFail($id);
        if ($label->tasks()->count() > 0) {
            flash('Не удалось удалить метку')->error();
            return redirect('/labels');
        }
        $label->delete();
        flash('Метка успешно удалена')->success();
        return redirect('/labels');
    }
}
