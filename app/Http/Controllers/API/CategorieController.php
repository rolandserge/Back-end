<?php

namespace App\Http\Controllers\API;

use App\Models\Categorie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    public function index() {

        $categories = Categorie::all();

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function store(Request $request) {

        $validation = Validator::make($request->all(), [
            'nom' => 'required',
            'description' => 'required',
            'user' => "required",
        ]);

        if($validation->fails()) {

            return response()->json([
                'errors' => $validation->messages(),
                'message' => 'Veillez renseigner tout les champs'
            ]);
        }

        $categorie = Categorie::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'user_id' => $request->user,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Success, la categorie a été creé',
        ]);
    }
}