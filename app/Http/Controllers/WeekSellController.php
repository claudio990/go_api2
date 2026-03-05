<?php

namespace App\Http\Controllers;

use App\Models\WeekSell;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\WeekSellRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class WeekSellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $weekSells = WeekSell::paginate();

        return view('week-sell.index', compact('weekSells'))
            ->with('i', ($request->input('page', 1) - 1) * $weekSells->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $weekSell = new WeekSell();

        return view('week-sell.create', compact('weekSell'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WeekSellRequest $request): RedirectResponse
    {
        WeekSell::create($request->validated());

        return Redirect::route('week-sells.index')
            ->with('success', 'WeekSell created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $weekSell = WeekSell::find($id);

        return view('week-sell.show', compact('weekSell'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $weekSell = WeekSell::find($id);

        return view('week-sell.edit', compact('weekSell'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WeekSellRequest $request, WeekSell $weekSell): RedirectResponse
    {
        $weekSell->update($request->validated());

        return Redirect::route('week-sells.index')
            ->with('success', 'WeekSell updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        WeekSell::find($id)->delete();

        return Redirect::route('week-sells.index')
            ->with('success', 'WeekSell deleted successfully');
    }
}
