<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request) {

        $validation = Validator::make($request->all(), [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if($validation->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validation->messages(),
            ]);
        } else {

            $user = User::create([
                'name' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken($request->email.'_Token')->plainTextToken;

            return response()->json([
                'status' => 200,
                'token' => $token,
                'message' => 'Personnel créee avec success'
            ]);

        }
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if($validator->fails()) {

            return response()->json([
                'error' => $validator->messages(),
                'status' => 422
            ]);

        } else {

            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                // $request->session()->regenerate();
                $token = $user->createToken($request->email.'_Token')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'user' => Auth::user(),
                    'token' => $token,
                    'message' => 'connexion reussi avec succes'
                ]);
            } else  {

                return response()->json([
                    'message' => 'Vos identifiants sont incorrect',
                    'status' => 401
                ]);
            }
        }

    }

    public function me() {

        $auth_user = Auth::user();

        return response()->json([
            'status' => 200,
            "user" => $auth_user
        ]);
    }

    public function logout() {

        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'deconnexion reussie'
        ]);
    }
}
