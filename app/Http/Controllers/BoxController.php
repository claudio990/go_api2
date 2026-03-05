<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BoxRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BoxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $boxes = Box::paginate();

        return view('box.index', compact('boxes'))
            ->with('i', ($request->input('page', 1) - 1) * $boxes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $box = new Box();

        return view('box.create', compact('box'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoxRequest $request): RedirectResponse
    {
        Box::create($request->validated());

        return Redirect::route('boxes.index')
            ->with('success', 'Box created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $box = Box::find($id);

        return view('box.show', compact('box'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $box = Box::find($id);

        return view('box.edit', compact('box'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BoxRequest $request, Box $box): RedirectResponse
    {
        $box->update($request->validated());

        return Redirect::route('boxes.index')
            ->with('success', 'Box updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Box::find($id)->delete();

        return Redirect::route('boxes.index')
            ->with('success', 'Box deleted successfully');
    }
}
