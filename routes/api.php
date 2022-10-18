<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\ProductController;
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
Route::get('login', function(){
       return response()->json([
        'status' => false,
        'message' => 'Token not found.',
    ], 401);
})->name('login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('sub_categories', SubCategoryController::class);
    Route::apiResource('products', ProductController::class);
});

// Verb          Path                        Action  Route Name
// GET           /users                      index   users.index
// POST          /users                      store   users.store
// GET           /users/{user}               show    users.show
// PUT|PATCH     /users/{user}               update  users.update
// DELETE        /users/{user}               destroy users.destroy
// Company
// User
// Products
// Pricing
// : Company will belong to a user
// category with parent id same table
// : A user can have multiple companies,
// : And a company can have multiple products
// : A product will have a pricing plan
// : A product can have multiple images
// : User can favourite multiple companies
// : User can follow a company
// : User can favourite multiple products
