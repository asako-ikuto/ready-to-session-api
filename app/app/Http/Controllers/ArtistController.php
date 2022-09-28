<?php

namespace App\Http\Controllers;

use App\Artist;
use Illuminate\Http\Request;
use App\Http\Requests\ArtistRequest;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::get()->toJson(JSON_PRETTY_PRINT);
        return $artists;
    }

    public function store(ArtistRequest $request)
    {
        $artist_name = $request->name;
        //アーティストが存在しなければ作成
        if(!Artist::where('name', $artist_name)->exists()){
            $artist = new Artist;
            $artist->name = $artist_name;
            $artist->save();

            return response()->json([
                "message" => "artist record created"
            ], 201);
        } else {
            return response()->json([
                "message" => "{$artist_name} is already registered"
            ], 404);
        }
    }

    public function update(ArtistRequest $request, $id)
    {
        if(Artist::where('id', $id)->exists()){
            $artist = Artist::find($id);
            $artist->name = $request->name;
            $artist->save();

            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "artist not found"
            ], 404);
        }
    }

    public function destroy($id){
        if(Artist::where('id', $id)->exists()){
            $artist = Artist::find($id);
            $artist->delete();

            return response()->json([
                "message" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "artist not found"
            ], 404);
        }
    }
}
