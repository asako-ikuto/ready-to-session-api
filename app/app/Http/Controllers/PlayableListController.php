<?php

namespace App\Http\Controllers;

use App\User;
use App\Artist;
use App\Song;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\PlayableListRequest;

class PlayableListController extends Controller
{
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $playable_list = $user->playable_lists()->get()->toJson(JSON_PRETTY_PRINT);
        return $playable_list;
    }

    public function indexDetail()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $playable_list = $user->playable_lists()->select('songs.id', 'songs.name', 'playable_lists.updated_at as updated_at', 'artists.id as artist_id', 'artists.name as artist_name')->join('artists', 'songs.artist_id', '=', 'artists.id')->get()->toJson(JSON_PRETTY_PRINT);
        return $playable_list;
    }

    public function userPlayableList($user_id)
    {
        $user = User::find($user_id);
        $playable_list = $user->playable_lists()->select('songs.id', 'songs.name', 'playable_lists.updated_at as updated_at', 'artists.id as artist_id', 'artists.name as artist_name')->join('artists', 'songs.artist_id', '=', 'artists.id')->get()->toJson(JSON_PRETTY_PRINT);
        return $playable_list;
    }

    public function store($song_id)
    {
        $user_id = auth()->user()->id;
        $song = Song::find($song_id);
        $song->playable_users()->attach($user_id);
        return response()->json([
            "message" => "playablelist record created"
        ], 201);
    }

    public function destroy($song_id)
    {
        $user_id = auth()->user()->id;
        $song = Song::find($song_id);
        $song->playable_users()->detach($user_id);
        return response()->json([
            "message" => "playablelist record removed"
        ], 201);
    }
}
