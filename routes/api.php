<?php

use App\Http\Controllers\LyricsController;
use App\Http\Controllers\PhotoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\LettersController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix'=>'auth'],function(){
    Route::post('login', [AuthController::class,'login']);
    Route::post('registration', [AuthController::class,'registration']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});

Route::group(['middleware' => 'jwt.auth'], function () {

    Route::post('artists', [ArtistController::class,'create']);
    Route::patch('artists', [ArtistController::class,'update']);

});



Route::post('photo', [PhotoController::class,'create']);




Route::get('trans',[LettersController::class, 'trans']);

Route::patch('artists/rate', [ArtistController::class,'rate']);
Route::resource('artists', ArtistController::Class,[
    'only' => ['index', 'show']
]);

Route::resource('lyrics', LyricsController::Class,[
    'only' => ['index', 'show']
]);


