<?php

namespace App\Http\Controllers\API;

use App\Models\Livre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LivreController extends Controller
{
    public function index() {

        //recuperer tous livres
        $livres = Livre::with('categorie')->get();

        return response()->json([
            'status' => 200,
            'livres' => $livres,
        ]);
    }

    public function store(Request $request) {

        //verifier les saisis de l'utilisateur
        $validation = Validator::make($request->all(), [
            'titre' => 'required',
            'auteur' => 'required',
            'cover' => 'required|mimes:png,jpg,jpeg',
            'description' => 'required',
            'user' => 'required',
            'categorie' => 'required'
        ]);

        //renvoyer une reponse au frond en cas d'echec
        if($validation->fails()) {

            return response()->json([
                'errors' => $validation->messages(),
                'message' => 'Veillez remplir tout les champs'
            ]);

        } else {

            //verifier si la photo de couverture existe
            if($request->hasFile('cover')) {

                $file = $request->file("cover");
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move(public_path('uploads/couverture/'), $filename);
                $image = 'uploads/couverture/'.$filename;

            }

            //creer un nouveau livre
            $livre = Livre::create([
                'titre' => $request->titre,
                'auteur' => $request->auteur,
                'cover' => $image,
                'description' => $request->description,
                'user_id' => $request->user,
                'categorie_id' => $request->categorie
            ]);

            //renvoyer une reponse en format json
            return response()->json([
                'status' => 200,
                'message' => 'Success ! le livre a été cree'
            ]);
        }
    }

    public function show($id) {

        //verifier si le livre existe a travers l'id passer en parametre
        $livre = Livre::find($id);

        //Si le livre existe, on renvoie le livre au format json
        if($livre) {

            return response()->json([
                "status" => 200,
                'livre' => $livre
            ]);

        } else {
            //Envoyer une reponse d'erreur
            return response()->json([
                "status" => 404,
                'message' => 'Livre pas trouvé'
            ]);
        }
    }

    public function update(Request $request, $id) {

        $validation = Validator::make($request->all(), [
            'titre' => 'required',
            'auteur' => 'required',
            'cover' => 'required|mimes:png,jpg,jpeg',
            'description' => 'required',
            'user' => 'required',
            'categorie' => 'required'
        ]);

        if($validation->fails()) {

            return response()->json([
                'errors' => $validation->messages(),
                'message' => 'Veillez remplir tout les champs'
            ]);

        } else {

            $livre_a_modifier = Livre::find($id);

            if($request->hasFile('cover')) {

                $path = $livre_a_modifier->cover;

                if($path){

                    $exists = Storage::disk('public')->exists("uploads/couverture/{$livre_a_modifier->cover}");

                    if($exists){

                        Storage::disk('public')->delete("uploads/couverture/{$livre_a_modifier->cover}");
                    }
                }

                $file = $request->file("cover");
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move(public_path('uploads/couverture/'), $filename);

                $image = 'uploads/couverture/'.$filename;
            }

            if($livre_a_modifier) {

                $livre_a_modifier->titre = $request->titre;
                $livre_a_modifier->auteur = $request->auteur;
                $livre_a_modifier->cover = $image;
                $livre_a_modifier->description = $request->description;
                $livre_a_modifier->user_id = $user;
                $livre_a_modifier->categorie_id = $categorie;
                $livre_a_modifier->update();

                return response()->json([
                    'status' => 200,
                    'message' => "Produit modifié avec succes"
                ]);
            }
        }
    }

    public function delete($id) {

        $livre_a_supprimer = Livre::find($id);

        Storage::disk('public')->delete("uploads/couverture/{$livre_a_supprimer->cover}");

        if($livre_a_supprimer) {

            $livre_a_supprimer->delete();

            return response()->json([
                'status' => 200,
                'message' => "Livre supprimé avec succes"
            ]);
        } else {

            return response()->json([
                'status' => 404,
                'message' => "Livre n'existe pas"
            ]);
        }
    }
}
