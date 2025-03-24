<?php

namespace App\Http\Controllers;

use App\Models\Rab;
use App\Http\Requests\StoreRabRequest;
use App\Http\Requests\UpdateRabRequest;

class RabController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('rab.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rab.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRabRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rab $rab)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rab $rab)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRabRequest $request, Rab $rab)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rab $rab)
    {
        //
    }
}
