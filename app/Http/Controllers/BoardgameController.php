<?php

namespace App\Http\Controllers;

use App\Models\Boardgame;
use Illuminate\Http\Request;

class BoardgameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Boardgame::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required'
        ]);

        $file = $request->file('image');
        $file->move('img/boardgames/', $file->getClientOriginalName());

        $boardgame = Boardgame::create([
            'name' => $request->name,
            'image_path' => 'img/boardgames/' . $file->getClientOriginalName()
        ]);

        return $boardgame;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Boardgame  $boardgame
     * @return \Illuminate\Http\Response
     */
    public function show(Boardgame $boardgame)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Boardgame  $boardgame
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Boardgame $boardgame)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Boardgame  $boardgame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boardgame $boardgame)
    {
        //
    }
}
