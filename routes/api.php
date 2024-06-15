<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\api;
use App\Http\Controllers\Api\insertdata;
use App\Http\Controllers\Api\edit;
use App\Http\Controllers\Api\delete;
use App\Http\Controllers\Api\auth;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ReplayController;
use App\Http\Controllers\SavedvideoController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CoursesController;
use App\Http\Controllers\Admin\ProfiteController;

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
    Route::get('register',[api::class,'register']);
    Route::get('detailmycourses/{course_id}/{content_id}/{user_id}',[api::class,'content_course']);
    Route::get('courses',[api::class,'courses']);
    Route::get('readmore/{courseid}',[api::class,'readmore']);
    Route::get('myprofile/{userid}',[api::class,'myProfile']);
    Route::get('savedvideo/{userid}',[api::class,'savedVideo']);
    Route::get('certificate/{userid}',[api::class,'certificate']);
    Route::prefix('admin')->group(function () {
        Route::get('dashboard',[DashboardController::class,'index']);
        Route::get('users',[UserController::class,'index']);
        Route::get('courses',[CoursesController::class,'index']);
        Route::get('profit',[ProfiteController::class,'index']);
    });
    
    // auth routess
    Route::post('login',[auth::class,'login']);
    Route::post('register',[auth::class,'register']);
    
    Route::group(['middleware'=>['auth:sanctum']],function (){
        //logout
        Route::post('logout',[auth::class,'logout']);
        Route::put('edituser/{userid}',[auth::class,'update']);
        
        //post metthod
        Route::post('addcategory',[CategoryController::class,'store']);
        Route::post('addcertificate',[CertificateController::class,'store']);
        Route::post('addcourse',[CourseController::class,'store']);
        Route::post('addsubcategory', [SubcategoryController::class, 'store']);
        Route::post('addcontent', [ContentController::class, 'store']);
        Route::post('addquiz', [QuizController::class, 'store']);
        Route::post('addcomment', [CommentController::class, 'store']);
        Route::post('addenrollment', [EnrollmentController::class, 'store']);
        Route::post('addsavedvideo', [SavedvideoController::class, 'store']);
        Route::post('addhistory', [HistoryController::class, 'store']);
        Route::post('addreplay', [ReplayController::class, 'store']);
        

        Route::post('/generate-pdf', [CertificateController::class, 'generatePdf']);


        //edit metthod
        Route::put('editcategory/{id}',[CategoryController::class,'update']);
        Route::put('editcertificate/{id}',[CertificateController::class,'update']);
        Route::put('editsubcategory/{id}', [SubcategoryController::class, 'update']);
        Route::put('editcourse/{id}',[CourseController::class,'update']);
        Route::put('editcontent/{id}', [ContentController::class, 'update']);
        Route::put('editquiz/{id}', [QuizController::class, 'update']);
        Route::put('editcomment/{id}', [CommentController::class, 'update']);
        Route::put('editenrollment/{id}', [EnrollmentController::class, 'update']);
        Route::put('savedvideo/{id}', [SavedvideoController::class, 'update']);
        Route::put('editactivecontent/{id}', [ContentController::class, 'editActive']);

        //delete metthod
        Route::delete('deletecategory/{id}',[CategoryController::class,'destroy']);
        Route::delete('deletecertificate/{id}',[CertificateController::class,'destroy']);
        Route::delete('deletesubcategory/{id}', [SubcategoryController::class, 'destroy']);
        Route::delete('deletecourse/{id}',[CourseController::class,'destroy']);
        Route::delete('deletecontent/{id}', [ContentController::class, 'destroy']);
        Route::delete('deletequiz/{id}', [QuizController::class, 'destroy']);
        Route::delete('deletecomment/{id}', [CommentController::class, 'destroy']);
        Route::delete('deleteenrollment/{id}', [EnrollmentController::class, 'destroy']);
        Route::delete('deletereplay/{id}', [ReplayController::class, 'destroy']);
        Route::delete('savedvideo/{id}', [SavedvideoController::class, 'destroy']);
      
        
    });
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

