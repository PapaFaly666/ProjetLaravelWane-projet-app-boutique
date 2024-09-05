<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

// Route pour récupérer l'utilisateur connecté (protégée)

Route::get('/qrcode', function () {
    return QrCode::size(200)->generate('https://example.com');
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Groupe de routes protégées pour les utilisateurs
Route::middleware('auth:sanctum')->prefix('v1/users')->group(function () {
    
Route::get('/',[UserController::class,'index']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'delete']);
});
Route::post('/v1/users', [UserController::class, 'store']);

// Groupe de routes protégées pour les clients
Route::middleware('auth:sanctum')->group(function () {
    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{id}', [ClientController::class, 'show']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::put('clients/{id}', [ClientController::class, 'update']);
    Route::delete('clients/{id}', [ClientController::class, 'destroy']);
    Route::post('clients/telephone', [ClientController::class, 'searchParTelephone']);
    Route::post('clients/{id}/dettes', [ClientController::class, 'listerDettes']);
    Route::post('/clients/{clientId}/user', [ClientController::class, 'afficherCompteUser']);


});

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('articles', ArticleController::class);
    Route::post('/articles/stock', [ArticleController::class, 'addStockArticle']);
    Route::post('/articles/restaurer/{id}', [ArticleController::class, 'restore']);
    Route::post('/articles/libelle',[ArticleController::class,'getByLibelle']);
    //Route::put('/articles',[ArticleController::class,'update']);
    //Route::delete('/articles', [ArticleController::class,'delete']);
    //Route::patch('/articles/{id}',[ArticleController::class, 'getByLibelle']);
});






// Routes publiques pour l'authentification
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


