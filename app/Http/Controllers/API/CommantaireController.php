<?php

namespace App\Http\Controllers\API;

use App\Models\Commentaire;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommantaireController extends Controller
{
    public function index($id) {

        $commentaires = Commentaire::where('projet_id','=', $id)->get();

        return response()->json([
            'status' => 200,
            'commentaires' => $commentaires
        ]);
    }

    public function store(Request $request) {

        $validate = Validator::make($request->all(), [
            'evaluation' => 'required',
            'message' => 'required',
            'user' => 'required',
            'livre' => 'required',
        ]);

        if($validate->fails()) {

            return response()->json([
                'errors' => $validate->messages(),
                'message' => "Veillez renseigner tout les champs"
            ]);
        } else {

            $commentaire = Commentaire::create([
                'message' => $request->message,
                'evaluation' => $request->evaluation,
                "user_id" => $request->user,
                "livre_id" => $request->livre
            ]);

            return response()->json([
                "status" => 200,
                'message' => "Commmentaire créée avec succes"
            ]);
        }
    }
}