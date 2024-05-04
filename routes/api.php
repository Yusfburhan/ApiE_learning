<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\api;
use App\Http\Controllers\Api\insertdata;
use App\Http\Controllers\Api\edit;
use App\Http\Controllers\Api\delete;
use App\Http\Controllers\Api\auth;

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
Route::group(['middleware'=>['json']],function (){

    // Route::prefix('admin')->group(function () {
    //     Route::resource('products', api::class);
    // });
    //other routes 
    Route::get('dashboard/{userid}',[api::class,'dashboard']);
    Route::get('mycourses/{user_id}',[api::class,'mycourses']);
    Route::get('detailmycourses/{course_id}/{content_id}/{user_id}',[api::class,'content_course']);
    Route::get('courses',[api::class,'courses']);
    Route::get('readmore/{courseid}',[api::class,'readmore']);
    Route::get('myprofile/{userid}',[api::class,'myProfile']);
    

    
    
    // auth routess
    Route::post('login',[auth::class,'login']);
    Route::post('register',[auth::class,'register']);
    
    Route::group(['middleware'=>['auth:sanctum']],function (){
        //logout
        Route::post('logout',[auth::class,'logout']);
        Route::post('edituser/{userid}',[auth::class,'editUser']);
        
        //post metthod
        Route::post('addcourse',[insertdata::class,'addcourse']);
        Route::post('addcategory', [insertdata::class, 'addcategory']);
        Route::post('addcontent', [insertdata::class, 'addcontent']);
        Route::post('addquiz', [insertdata::class, 'addquiz']);
        Route::post('addcomment', [insertdata::class, 'addcomment']);
        Route::post('addenrollment', [insertdata::class, 'addenrollment']);

        //edit metthod
        Route::put('editcategory/{id}', [edit::class, 'editcategory']);
        Route::put('editcourse/{id}',[edit::class,'editcourse']);
        Route::put('editcontent/{id}', [edit::class, 'editcontent']);
        Route::put('editquiz/{id}', [edit::class, 'editquiz']);
        Route::put('editcomment/{id}', [edit::class, 'editcomment']);
        Route::put('editenrollment/{id}', [edit::class, 'editenrollment']);
        //delete metthod
        Route::delete('deletecategory/{id}', [delete::class, 'deletecategory']);
        Route::delete('deletecourse/{id}',[delete::class,'deletecourse']);
        Route::delete('deletecontent/{id}', [delete::class, 'deletecontent']);
        Route::delete('deletequiz/{id}', [delete::class, 'deletequiz']);
        Route::delete('deletecomment/{id}', [delete::class, 'deletecomment']);
        Route::delete('deleteenrollment/{id}', [delete::class, 'deleteenrollment']);
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
