<?php

namespace App\Http\Controllers;

use App\Models\WeekClientSell;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\WeekClientSellRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class WeekClientSellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $weekClientSells = WeekClientSell::paginate();

        return view('week-client-sell.index', compact('weekClientSells'))
            ->with('i', ($request->input('page', 1) - 1) * $weekClientSells->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $weekClientSell = new WeekClientSell();

        return view('week-client-sell.create', compact('weekClientSell'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WeekClientSellRequest $request): RedirectResponse
    {
        WeekClientSell::create($request->validated());

        return Redirect::route('week-client-sells.index')
            ->with('success', 'WeekClientSell created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $weekClientSell = WeekClientSell::find($id);

        return view('week-client-sell.show', compact('weekClientSell'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $weekClientSell = WeekClientSell::find($id);

        return view('week-client-sell.edit', compact('weekClientSell'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WeekClientSellRequest $request, WeekClientSell $weekClientSell): RedirectResponse
    {
        $weekClientSell->update($request->validated());

        return Redirect::route('week-client-sells.index')
            ->with('success', 'WeekClientSell updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        WeekClientSell::find($id)->delete();

        return Redirect::route('week-client-sells.index')
            ->with('success', 'WeekClientSell deleted successfully');
    }
}
