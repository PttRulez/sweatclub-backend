<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClubResource;
use App\Models\Club;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ClubResource::collection(Club::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:clubs',
            'avatar' => 'nullable',
        ]);

        $club = Club::create([
            'name' => $request->name
        ]);

        if ($request->hasFile('avatar')) {
            $avatar_path = (new FileService())->storePublicImageFromInput('avatar', 'img/clubs/', $club->id . '_club');
            $club->avatar = $avatar_path;
            $club->save();
        }

        return $club;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Club $club
     * @return array
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:clubs,name,' . $id,
            'avatar' => 'nullable'
        ]);

        $club = Club::find($id);

        $club->fill(request()->all());
        if (request()->hasFile('avatar')) {
            (new FileService())->updatePublicImageFromInput('avatar', 'img/clubs/', $club->id . '_club', $club, 'avatar');
        }


        $club->save();

        return $club;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $club = Club::find($id);
        $club->delete();
    }
}
