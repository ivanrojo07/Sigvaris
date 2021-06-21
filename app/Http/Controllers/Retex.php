<?php

namespace App\Http\Controllers;

use App\retex;
use Illuminate\Http\Request;

class Retex extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        return "Retex edicion";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function show(retex $retex)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function edit(retex $retex)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, retex $retex)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\retex  $retex
     * @return \Illuminate\Http\Response
     */
    public function destroy(retex $retex)
    {
        //
    }
}
