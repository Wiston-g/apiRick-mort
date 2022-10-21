<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validateData = $request->validate([
            'page' => 'int',
        ]);
        
        $page = $validateData['page'];
        
        $result = HTTP::get('https://rickandmortyapi.com/api/character/?page=' . $page);
        $characters = $result->json();
        $character = array();

        foreach($characters['results'] as $value){
            $personaje = [
                'id' => $value['id'],
                'name' => $value['name'],
                'image' => $value['image'],
            ];

            array_push($character, $personaje);
        }

        return response()->json([
            "page" => $page,
            "status" => 1,
            "msg" => "Personaje",
            'characters' => $character,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        
        $validateData = $request->validate([
            'id' => 'string',
        ]);

        $personaje = $validateData['id'];

        $firstFavorite = Favorite::where("ref_api", "=", $personaje)->first();

        if (!isset($firstFavorite->id)) {

            $favorite = Favorite::create([
                'id_usuario' => $user->id, 
                'ref_api' => $validateData['id'],
            ]);

            
            $result = HTTP::get('https://rickandmortyapi.com/api/character/' . $personaje);
            $character = $result->json();
            
            return response()->json([
                "status" => 1,
                "msg" => "Este personaje es tu nuevo favorito",
                'favorite' => $favorite,
                'character' => $character
            ],201);
        }else{
            $result = HTTP::get('https://rickandmortyapi.com/api/character/' . $personaje);
            $character = $result->json();

            return response()->json([
                "status" => 1,
                "msg" => "Este personaje ya es favorito",
                'character' => $character
            ],200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $user = auth()->user();

        $allFavorite = Favorite::where("id_usuario", "=", $user->id)->get();

        //$result = HTTP::get('https://rickandmortyapi.com/api/character/' . $personaje);
        //$character = $result->json();

        return response()->json([
            "status" => 1,
            "msg" => "Este personaje ya es favorito",
            'allFavorite' => $allFavorite,
            //'character' => $character
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $validateData = $request->validate([
            'id' => 'string',
        ]);
        
        $personaje = $validateData['id'];
        
        $result = HTTP::get('https://rickandmortyapi.com/api/character/' . $personaje);
        $character = $result->json();
        
        return response()->json([
            "status" => 1,
            "msg" => "Personaje",
            'character' => $character
        ],200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function edit(Favorite $favorite)
    {
        //
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorite  $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Favorite $favorite)
    {
        //
    }
}
