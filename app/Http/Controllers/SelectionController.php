<?php

namespace Liamtseva\Cinema\Http\Controllers;

use Liamtseva\Cinema\Http\Requests\StoreSelectionRequest;
use Liamtseva\Cinema\Http\Requests\UpdateSelectionRequest;
use Liamtseva\Cinema\Models\Selection;

class SelectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selections = Selection::query()->name()->dumpRawSql();
        //dd($selections);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSelectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Selection $selection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Selection $selection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSelectionRequest $request, Selection $selection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Selection $selection)
    {
        //
    }
}
