<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subcategory;
use App\Models\content;
use App\Models\history;
use App\Models\course;
use App\Models\enrollment;
use App\Models\quiz;
use App\Models\comment;
use App\Models\certificate;
use App\Models\Savedvideo;
use App\Models\User;
use Nette\Utils\Validators;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class api extends Controller
{
    public function dashboard($userid){
        $theUser=user::find($userid);
        $user = User::with('enrollments.course')->find($userid);
        $check =enrollment::select('user_id')->where('user_id',$userid)->exists();
        if ($check) {
            $enrollmentcourse=$user->enrollments->map(function ($enrollment) {
                return $enrollment->course;
            });
        }else {
            $enrollmentcourse=['unjoined'=>'no course Joined '];
        }
        return response()->json([
            'azkar'=>"alhamdwlila",
            'numberOfCourse'=>course::count(),
            'numOfCategories'=>subcategory::count(),
            'numOfUsers'=>user::count(),
            'numOfEnrollmet'=>enrollment::count(),
            'numOfTeacher'=>user::where('role',1)->count(),
            'numOfStudent'=>user::where('role',2)->count(),
            'popular'=>Course::select('courses.id', 'courses.title','price','description','imageofcourse','duration', 'courses.subcategory_id','courses.instructor', DB::raw('COUNT(enrollments.id) as enrollments_count'))
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->groupBy('courses.id', 'courses.subcategory_id', 'courses.title','price','description','duration','imageofcourse','instructor') // Include only necessary columns in GROUP BY
            ->orderByDesc('enrollments_count')
            ->limit(9)
            ->get(),
            'categoures'=>subcategory::latest()->take(8)->get(),
            'courseEnrolled'=>$enrollmentcourse,
            'userid'=>$check,
            // 'freeCourses'=>Course::where('price', '=', 0)->whereIn('subcategory_id', [$theUser->category1,$theUser->category2,$theUser->category3])->get()->take(30),
            'freeCourses'=>Course::join('subcategories', 'courses.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->where('price', '=', 0)
            ->whereIn('categories.id', [$theUser->category1, $theUser->category2,$theUser->category3])
            ->select(
                'courses.*', 
                'subcategories.name as subcategory_name', 
                'categories.name as category_name'
            )->orderBy('created_at', 'desc')->take(30)
            ->get(),
            // 'shortAndSweetCourses'=>Course::where('duration', '<', 16)->whereIn('subcategory_id', [$theUser->category1,$theUser->category2,$theUser->category3])->take(20)->get(),
            'shortAndSweetCourses'=>Course::join('subcategories', 'courses.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->where('duration', '<', 16)
            ->whereIn('categories.id', [$theUser->category1, $theUser->category2,$theUser->category3])
            ->select(
                'courses.*', 
                'subcategories.name as subcategory_name', 
                'categories.name as category_name'
            )->orderBy('created_at', 'desc')->take(20)
            ->get(),
            // 'newestCourses'=>Course::orderBy('created_at', 'desc')->whereIn('subcategory_id', [$theUser->category1,$theUser->category2,$theUser->category3])->take(25)->get(), // lwanaya xalat bet 
            'newestCourses'=>Course::join('subcategories', 'courses.subcategory_id', '=', 'subcategories.id')
            ->join('categories', 'subcategories.category_id', '=', 'categories.id')
            ->whereIn('categories.id', [$theUser->category1, $theUser->category2,$theUser->category3])
            ->select(
                'courses.*', 
                'subcategories.name as subcategory_name', 
                'categories.name as category_name'
            )->orderBy('created_at', 'desc')->take(25)
            ->get()
        ],200);
    }
    public function mycourses($userid){
        $user=user::findOrFail($userid);

        $check =enrollment::select('user_id')->where('user_id',$userid)->exists(); 
        $created=course::where('teacherid',$userid)->get();
        $courses = Course::whereHas('enrollments', function($query) use ($userid) {
            $query->where('user_id', $userid);
        })->get();    
        $subcategory=subcategory::all();  
        return [
            'azkar' => 'alhamdulillah',
            'courseEnrolled' => $courses,
            'courseteachercreated'=>$created,
            'categories'=>$subcategory,
        ];
    }
    public function content_course($course_id,$content_id,$user_id){
        $check = enrollment::select('course_id')->where([
            ['course_id', '=', $course_id],
            ['user_id', '=', $user_id],
        ])->exists();
        $check1 =Course::where('id', $course_id)->where('teacherid', $user_id)->exists();;
        $contents=content::where('course_id',$course_id)->get();
        $quizs=quiz::where('course_id',$course_id)->get();

        if ($check || $check1) { 
            $commentsData = DB::table('comments')
        ->join('contents', 'comments.content_id', '=', 'contents.id')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->leftJoin('replays', 'comments.id', '=', 'replays.comment_id')
        ->leftJoin('users AS replay_user', 'replays.user_id', '=', 'replay_user.id')
        ->where('comments.content_id', $content_id)
        ->where('contents.course_id', $course_id)
        ->select(
            'comments.id as comment_id',
            'comments.comment',
            'users.name as nameUserWhoComment',
            'users.image as image',
            'replays.id as replay_id',
            'replays.user_id as replay_user_id',
            'replay_user.name as nameUserWhoReplied',
            'replay_user.image as replay_image',
            'replays.replaycomment',
            'replays.created_at as replay_created_at'
        )
        ->orderBy('comments.id')
        ->get();
        $comments = [];

            foreach ($commentsData as $commentData) {
                    $commentId = $commentData->comment_id;

                if (!isset($comments[$commentId])) {
                    $comments[$commentId] = [
                        'comment_id' => $commentData->comment_id,
                        'comment' => $commentData->comment,
                        'nameUserWhoComment' => $commentData->nameUserWhoComment,
                        'image' => $commentData->image,
                        'replays' => [],
                    ];
                }

                if ($commentData->replay_id !== null) {
                    $comments[$commentId]['replays'][] = [
                        'replay_id' => $commentData->replay_id,
                        'replay_user_id' => $commentData->replay_user_id,
                        'nameUserWhoReplied' => $commentData->nameUserWhoReplied,
                        'replay_image' => $commentData->replay_image,
                        'replaycomment' => $commentData->replaycomment,
                        'replay_created_at' => $commentData->replay_created_at,
                    ];
                }  
            }
        $commentsArray = array_values($comments);
        $allActive = Content::where('course_id', $course_id)->where('active', true)->count() == Content::where('course_id', $course_id)->count();
            return [
                'azkar'=>'alhamdwlila',
                'course'=>course::findOrFail($course_id),
                'teacher'=>'$teacher',  // nay srtto 
                'comments' => $commentsArray,
                'contents'=>content::where('course_id',$course_id)->get(),
                'quizzes'=>quiz::where('course_id',$course_id)->get(),
                'entolcheck'=>$check,
                'createcheck'=>$check1,
                'checkEndOfTheCourse'=>$allActive

            ];
        }else{
            return [
                'notjoined'=>'not joined'
            ];
        }

    }
    public function courses(Request $request){
        $courses = course::all();
        // Get all subcategory
        $subcategory = subcategory::select('id', 'name')->get();
        // Check if category ID is provided in the request
        if ($request->has('subcategory_id')) {
            // Filter courses by category ID
            $filteredCourses = course::where('subcategory_id', $request->input('subcategory_id'))->get();
        } else {
            // If category ID is not provided, return all courses
            $filteredCourses = $courses;
        }

        // Return the response with courses and categories
        return [
            'azkar' => 'alhamdulillah',
            'course' => $filteredCourses,
            'categories' => $subcategory
        ];
    }
    public function readmore($course_id){
        // $check = courses::select('course_id')->where('course_id',$course_id)->exists();

            $course=course::find($course_id);
            $numofpeopleenrolledthiscourse=enrollment::where('course_id',$course_id)->count();
            $triller=Content::where('course_id',$course_id)->take(1)->get();
        
        return [
            'azkar'=>'الحمدلله علی کل حال ',
            'course'=>$course,
            'numOfPeopleEnrolledThisCourse'=>$numofpeopleenrolledthiscourse,
            'triler'=>$triller,
            'teacher'=>user::find($course->teacherid)

            
        ];
    }
    public function myProfile($user_id){
        return [
            'azkar'=>'allhamdwlila  ',
            'user'=>user::find($user_id)
        ];
    }  
    public function savedVideo($user_id){
        $savedvideo=savedVideo::where('user_id',$user_id)->orderBy('created_at', 'desc')->get();
        // $history=history::where('user_id',$user_id)->take(15)->latest()->first()->get();
        $history = history::where('user_id', $user_id)
        ->latest() // or orderBy('created_at', 'desc')
        ->take(15)
        ->get();
        return [
            'azkar'=>'allhamdwlial',
            'savedVdeo'=>$savedvideo,
            'history'=>$history
        ];
    }
    public function certificate($id){
            // $certificates = Certificate::with('course', 'teacher')->where('user_id', $id)->get();
            $certificates = Certificate::with('course', 'teacher')
            ->select('user_id', 'teacher_id', 'course_id', DB::raw('MAX(url) as url'),DB::raw('MAX(created_at) as created_at'))
            ->where('user_id', $id)
            ->groupBy('user_id', 'teacher_id', 'course_id')
            ->get();
    
            return response()->json([
                'azakar' => 'alhamdulillah',
                'certificates' => $certificates,
            ], 200);
    }
    public function register(){
        $subcategory=subcategory::all();  
        return response()->json([
            'categories'=>$subcategory,
        ], 200);
    }

    
}
