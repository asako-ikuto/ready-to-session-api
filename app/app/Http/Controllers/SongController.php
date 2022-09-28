<?php

namespace App\Http\Controllers;

use App\Song;
use App\Artist;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\SongRequest;

class SongController extends Controller
{
    public function index()
    {
        $songs = DB::table('songs')
            ->select('songs.id', 'songs.name', 'artists.id as artist_id', 'artists.name as artist_name')
            ->join('artists', 'songs.artist_id', '=', 'artists.id')
            ->get()->toJson(JSON_PRETTY_PRINT);
        return $songs;
    }

    public function store(SongRequest $request)
    {
        $artist_id = $request->artist_id;
        $song_name = $request->name;
        $artist_songs = Artist::find($artist_id)->songs;
        $artist_song_collection = collect($artist_songs);
        $filterd_artist_song = $artist_song_collection->where('name', $song_name)->first();
        //曲が存在しなければ作成
        if(!isset($filterd_artist_song)){
            $song = new Song;
            $song->name = $song_name;
            $song->artist_id = $artist_id;
            $song->save();
            return response()->json([
                "message" => "song record created"
            ], 201);
        } else {
            return response()->json([
                "message" => "{$song_name} is already registerd"
            ], 404);
        }
    }

    public function update(SongRequest $request, $id)
    {
        if(Song::where('id', $id)->exists()){
            $song = Song::find($id);
            $song->name = $request->name;
            $song->artist_id = $request->artist_id;
            $song->save();

            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "song not found"
            ], 404);
        }
    }

    public function destroy($id){
        if(Song::where('id', $id)->exists()){
            $song = Song::find($id);
            $song->delete();

            return response()->json([
                "message" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "song not found"
            ], 404);
        }
    }
}
