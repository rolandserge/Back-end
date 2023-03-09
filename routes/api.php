<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LivreController;
use App\Http\Controllers\API\CategorieController;
use App\Http\Controllers\API\CommantaireController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->group(function() {

    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/me', [UserController::class, 'me']);

    //livre action route authentifier
    Route::post('livres/add-livre', [LivreController::class, 'store']);
    Route::get('/livres/show-livre/{id}', [LivreController::class, 'show']);
    Route::patch('/livres/update-livre/{id}', [LivreController::class, 'update']);
    Route::delete('livres/delete-livre/{id}', [LivreController::class, 'delete']);

    //action route categorie
    Route::get('/categories/liste-categorie', [CategorieController::class, 'index']);
    Route::post('/categories/add-categorie', [CategorieController::class, 'store']);

    //route commentaire action
    Route::get('/commentaires/liste-commentaire', [CommantaireController::class, 'index']);
    Route::post('/commentaires/add-commentaire', [CommantaireController::class, 'store']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//authentication routes action
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

//route action livre
Route::get('/livres/liste-livre', [LivreController::class, 'index']);
