<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BillRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $bills = Bill::paginate();

        return view('bill.index', compact('bills'))
            ->with('i', ($request->input('page', 1) - 1) * $bills->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $bill = new Bill();

        return view('bill.create', compact('bill'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BillRequest $request): RedirectResponse
    {
        Bill::create($request->validated());

        return Redirect::route('bills.index')
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $bill = Bill::find($id);

        return view('bill.show', compact('bill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $bill = Bill::find($id);

        return view('bill.edit', compact('bill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BillRequest $request, Bill $bill): RedirectResponse
    {
        $bill->update($request->validated());

        return Redirect::route('bills.index')
            ->with('success', 'Bill updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Bill::find($id)->delete();

        return Redirect::route('bills.index')
            ->with('success', 'Bill deleted successfully');
    }
}
