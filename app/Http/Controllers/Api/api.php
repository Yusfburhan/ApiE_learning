<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\categories;
use App\Models\contents;
use App\Models\courses;
use App\Models\enrollments;
use App\Models\quizzes;
use App\Models\cost;
use App\Models\comments;
use App\Models\User;
use Nette\Utils\Validators;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class api extends Controller
{
    public function dashboard($userid){
        $user = User::with('enrollments.course')->find($userid);
        $check =enrollments::select('user_id')->where('user_id',$userid)->exists();
        if ($check) {
            $enrollmentcourse=$user->enrollments->map(function ($enrollment) {
                return $enrollment->course;
            });
        }else {
            $enrollmentcourse=['unjoined'=>'no course Joined '];
        }
        return response()->json([
            'azkar'=>"alhamdwlila",
            'numberOfCourse'=>courses::count(),
            'numOfCategories'=>categories::count(),
            'numOfUsers'=>user::count(),
            'numOfEnrollmet'=>enrollments::count(),
            'numOfTeacher'=>user::where('role','teacher')->count(),
            'numOfStudent'=>user::where('role','student')->count(),
            'popular'=>Courses::select('courses.id', 'courses.title','price','description','imageofcourse','duration', 'courses.category_id','courses.instructor', DB::raw('COUNT(enrollments.id) as enrollments_count'))
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->groupBy('courses.id', 'courses.category_id', 'courses.title','price','description','duration','imageofcourse','instructor') // Include only necessary columns in GROUP BY
            ->orderByDesc('enrollments_count')
            ->limit(7
            )
            ->get(),
            'categoures'=>categories::latest()->take(8)->get(),
            'courseEnrolled'=>$enrollmentcourse,
            // 'userid'=>$check,
            'freeCourses'=>Courses::where('price', '=', 0)->get(),
            'shortAndSweetCourses'=>Courses::where('duration', '<', 16)->get(),
            'courseBYCategories'=>Categories::with('courses')->take(3)->latest()->get(),
            'newestCourses'=>Courses::orderBy('created_at', 'desc')->take(10)->get(), // lwanaya xalat bet 


        ],200);
    }
    public function mycourses($userid){
        // $user = User::with('enrollments.course')->find($userid);
        $user=user::findOrFail($userid);
        $check =enrollments::select('user_id')->where('user_id',$userid)->exists();
        $created=courses::where('teacherid',$userid)->get();
        $categories=categories::all();  
        if ($check && $user) {
            $enrollmentcourse=$user->enrollments->map(function ($enrollment) {
                return $enrollment->course;
            });
        }else {
            $enrollmentcourse=['unjoined'=>'no course Joined '];
        }
       
        return [
            'azkar' => 'alhamdulillah',
            'courseEnrolled' => $enrollmentcourse,
            'courseteachercreated'=>$created,
            'categories'=>$categories,
            
        ];
    }
    public function content_course($course_id,$content_id,$user_id){
        $check = enrollments::select('course_id')->where([
            ['course_id', '=', $course_id],
            ['user_id', '=', $user_id],
        ])->exists();
        $contents=contents::where('course_id',$course_id)->get();
        $quizs=quizzes::where('course_id',$course_id)->get();
        // if ($check) {   
            return [
                'azkar'=>'alhamdwlila',
                'course'=>courses::findOrFail($course_id),
                'teacher'=>'$teacher',
                'comments'=>DB::table('comments')
                ->join('contents', 'comments.content_id', '=', 'contents.id')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->where('comments.content_id', $content_id)
                ->where('contents.course_id', $course_id)
                ->select('comments.*', 'users.name as nameUserWhoComment')
                ->get(),
                'contents'=>contents::where('course_id',$course_id)->get(),
                'quizzes'=>quizzes::where('course_id',$course_id)->get(),
                // 'entolcheck'=>$check,
            ];
        // }else{
        //     return [
        //         'notjoined'=>'not joined'
        //     ];
        // }

    }
    public function courses(Request $request){
        $courses = courses::all();

       
        // Get all categories
        $categories = categories::select('id', 'name')->get();
        // Check if category ID is provided in the request
        if ($request->has('category_id')) {
            // Filter courses by category ID
            $filteredCourses = courses::where('category_id', $request->input('category_id'))->get();
        } else {
            // If category ID is not provided, return all courses
            $filteredCourses = $courses;
        }
    
        // Return the response with courses and categories
        return [
            'azkar' => 'alhamdulillah',
            'course' => $filteredCourses,
            'categories' => $categories
        ];
    }
    public function readmore($course_id){
        // $check = courses::select('course_id')->where('course_id',$course_id)->exists();


            $course=courses::findOrFail($course_id);
            $numofpeopleenrolledthiscourse=enrollments::where('course_id',$course_id)->count();
            $triller=Contents::where('course_id',$course_id)->take(1)->get();
        
        return [
            'azkar'=>'الحمدلله علی کل حال ',
            'course'=>$course,
            'numOfPeopleEnrolledThisCourse'=>$numofpeopleenrolledthiscourse,
            'triler'=>$triller,
            
        ];
    }
  public function myProfile($user_id){
    return [
        'azkar'=>'allhamdwlila  ',
        'user'=>user::findOrFail($user_id)
    ];
  }  
}
