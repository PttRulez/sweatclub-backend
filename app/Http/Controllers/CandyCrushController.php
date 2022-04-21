<?php

namespace App\Http\Controllers;

use App\Http\Resources\CandyCrushResource;
use App\Models\CandyCrush;
use Illuminate\Http\Request;

class CandyCrushController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CandyCrushResource::collection(CandyCrush::orderBy('points', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'points' => 'required',
        ]);
        $candycrush = CandyCrush::where('user_id', $request->user_id)->first();

        if ($candycrush) {
            if ($candycrush->points < $request->input('points')) {
                $candycrush->points = $request->input('points');
                $candycrush->save();
            }

        } else {
            $candycrush = CandyCrush::create($request->all());
        }
        return $candycrush;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CandyCrush $candyCrush
     * @return \Illuminate\Http\Response
     */
    public function show(CandyCrush $candyCrush)
    {
        //
    }

}
