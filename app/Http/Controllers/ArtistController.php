<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ArtistController extends Controller
{

    public function index()
    {

        $artists = array();

        $result = Artist::with('photos')->with('lyrics:id')->select('id', 'alias', 'title_ru', 'title_ge', 'title_en','rate_points','raters','hits')->get();
        foreach ($result as $row) {
            $photos = $row->photos;
            $pic = (sizeof($photos) > 0) ? $photos[0]->path : 'design/no-image.jpg';
            $lyrics = ($row->lyrics) ? sizeof($row->lyrics) : 0;
            $artists[] = array('id' => $row->id, 'alias' => $row->alias, 'title_ru' => $row->title_ru, 'title_ge' => $row->title_ge, 'title_en' => $row->title_en, 'photo' => $pic, 'num' => $lyrics,'rate_points'=>$row->rate_points,'raters'=>$row->raters,'hits'=>$row->hits);
        }

        return $artists;
    }

    public function show($alias)
    {
        $artist = Artist::with('photos')->with('lyrics')->where('alias', $alias)->first();

        if (!$artist) {
            return response()->json([
                "status" => "false",
                "message" => "Artist not found"
            ]);
        }else{
            $this->hits($artist->id);
        }
        return $artist;
    }

    public function create(Request $request)
    {
        $artist = new Artist;
        $postbody = $request->json()->all();
        $artist->alias = $postbody['alias'];
        $artist->title_ru = $postbody['title_ru'];
        $artist->title_en = $postbody['title_en'];
        $artist->title_ge = $postbody['title_ge'];
        $artist->desc_ru = $postbody['desc_ru'];
        $artist->desc_en = $postbody['desc_en'];
        $artist->desc_ge = $postbody['desc_ge'];
        $artist->save();

    }

    private function hits($id)
    {
        $artist = Artist::find($id);
        if ($artist) {
            $artist->hits = $artist->hits + 1;
            $artist->update();

            return response()->json(['message' => 'hit successfully']);
        } else {
            return response()->json(['message' => 'artist not found']);
        }
    }

    public function rate(Request $request){
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


    public function update(Request $request, $id)
    {

        $artist = Artist::find($id);
        if ($artist) {

            return response()->json(['message' => 'updated successfully']);
        } else {
            return response()->json(['message' => 'artist not found']);
        }

    }
}
