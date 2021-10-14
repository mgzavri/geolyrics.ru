<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Lyrics;
use Illuminate\Http\Request;

class LyricsController extends Controller
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


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Lyrics $lyrics
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|object
     */
    public function show($alias)
    {
         $lyrics = Lyrics::with('artist')->with('songs')->with('videos')->where('alias', $alias)->first();
        if (!$lyrics) {
            return response()->json([
                "status" => "false",
                "message" => "Lyrics not found"
            ]);
        } else {
            $this->hits($lyrics->id);
        }
        return $lyrics;
    }


    private function hits($id)
    {
        $lyrics = Lyrics::find($id);
        if ($lyrics) {
            $lyrics->hits = $lyrics->hits + 1;
            $lyrics->update();

            return response()->json(['message' => 'hit successfully']);
        } else {
            return response()->json(['message' => 'lyrics not found']);
        }
    }

    public function rate(Request $request)
    {
        $artist = Artist::find($request->id);
        if ($artist) {
            $artist->rate_points = $artist->rate_points + $request->rate;
            $artist->raters = $artist->raters + 1;
            $artist->update();

            return response()->json(['message' => 'rate successfully']);
        } else {
            return response()->json(['message' => 'artist not found']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Lyrics $lyrics
     * @return \Illuminate\Http\Response
     */
    public function edit(Lyrics $lyrics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Lyrics $lyrics
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lyrics $lyrics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Lyrics $lyrics
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lyrics $lyrics)
    {
        //
    }
}
