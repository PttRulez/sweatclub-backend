<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardGameResource;
use App\Http\Resources\GameResource;
use App\Models\Boardgame;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BoardgameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BoardGameResource::collection(Boardgame::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'has_points' => 'nullable',
                'image' => 'required',
            ],
            [
                'name.required' => 'Нужно название игры',
                'image.required' => 'Обязательно приложите картинку игры',
            ]
        );

        $boardgame = Boardgame::create([
            'name' => $request->name,
            'has_points' => json_decode($request->has_points),
            'image_path' => (new FileService())->storePublicImageFromInput('image', 'img/boardgames/', $request->name . '_image')
        ]);

        return $boardgame;
    }

    /**
     * Display the specified resource.
     *
     * @return BoardGameResource[]
     */
    public function show($id)
    {
        $boardgame = Boardgame::findOrFail($id);
        return [
            'boardgame' => new BoardGameResource($boardgame),
            'games' => GameResource::collection($boardgame->games()->orderBy('date_played', 'desc')->with('players')->get()),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Boardgame $boardgame
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $boardgame = Boardgame::find($id);

        request()->validate([
            'name' => 'required',
            'has_points' => 'nullable',
            'image' => 'nullable',
        ]);

        $boardgame->name = request('name');
        $boardgame->has_points = json_decode(request('has_points'));  // можно так filter_var(request('has_points'), FILTER_VALIDATE_BOOL)
        (new FileService())->updatePublicImageFromInput('image', 'img/boardgames/', $boardgame->name . '_image', $boardgame, 'image_path');

        $boardgame->save();
        return new BoardGameResource($boardgame);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Boardgame $boardgame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boardgame $boardgame)
    {
        //
    }
}
