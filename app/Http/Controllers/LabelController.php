<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
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
            'labels' => Label::paginate(15)
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
    public function store(StoreLabelRequest $request)
    {
        $this->authorize('create', Label::class);

        $validated = $request->validated();

        $label = new Label();

        $label->fill($validated);
        $label->save();

        flash(__('controllers.label_create'))->success();
        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $label = Label::findOrFail($id);
        $this->authorize('create', Label::class);
        return view('pages.label', [
            'label' => $label,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label)
    {
        $this->authorize('update', [Label::class]);
        $validated = $request->validated();

        $label->fill($validated);
        $label->save();

        flash(__('controllers.label_update'))->success();
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $this->authorize('delete', Label::class);
        if ($label->tasks()->count() > 0) {
            flash(__('controllers.label_statuses_destroy_failed'))->error();
            return back();
        }
        $label->delete();
        flash(__('controllers.label_destroy'))->success();
        return redirect()->route('labels.index');
    }
}
