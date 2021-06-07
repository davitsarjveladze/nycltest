<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\TagsController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('articles')->group(function () {
   Route::get('/',[ArticlesController::class,'getArticles']);
   Route::get('/{id}/comments',[ArticlesController::class,'getCommentsById']);
});
Route::prefix('tags')->group(function () {
    Route::get('/',[TagsController::class,'getTSortedTags']);
    Route::get('/{id}/articles',[ArticlesController::class,'getArticleByTag']);
});
