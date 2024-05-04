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
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class insertdata extends Controller
{
    public function addcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'required|file',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    
        // Get the file from the request
        $file = $request->file('image');
    
        // Generate a unique name for the file
        $fileName = $request->name. '.' . $file->getClientOriginalExtension();
    
        // Move the file to the public directory under the course name
        $filePath = $file->move(public_path("assets/userCategories"), $fileName);
    
        // Check if file move is successful
        if (!$filePath) {
            return response()->json(['error' => 'Failed to save file'], 500);
        }
    
        // Create a new category instance
        $category = categories::create([
            'name' => $request->name,
            'image' => "assets/userCategories/".$fileName, // Use $fileName directly
        ]);
    
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }
    
   

    public function addContent(Request $request) {   
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'courseid' => 'required|exists:courses,id',
            'title' => 'required',
            'description' => 'required',
            'dataa' => 'required|file', // Adjusted validation rule to ensure it's a file
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    
        // Get the course
        $course = Courses::findOrFail($request->courseid);
    
        // Get the file from the request
        $file = $request->file('dataa');
    
        // Generate a unique name for the file
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
    
        // Move the file to the public directory under the course name
        $filePath = $file->move(public_path("assets/courses/{$course->title}"), $fileName);
    
        // Check if file move is successful
        if (!$filePath) {
            return response()->json(['error' => 'Failed to save file'], 500);
        }
    
        // Create a new content instance
        $content = Contents::create([
            'title' => $request->title,
            'course_id' => $request->courseid,
            'desc' => $request->description,
            'data' =>"assets/courses/{$course->title}/{$fileName}", // Store file path as JSON
        ]);
    
        // Check if content creation is successful
        if (!$content) {
            unlink($filePath); // Delete the uploaded file if content creation failed
            return response()->json(['error' => 'Failed to create content'], 500);
        }
    
        // Return success response
        return response()->json([
            'message' => 'Content created successfully',
            'content' => $content
        ], 200);
    }
    
    


    public function addquiz(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string', 
            'course_id' => 'required|integer|exists:courses,id', 
            'question' => 'required|string', 
            'options' => '', 
            'correct_answer' => 'required|string', 
        ]);
        if (!$validator->fails()) {
            $quiz = quizzes::create([
            'title' => $request->title,
            "course_id" => $request->course_id,
            'question' => $request->question,
            "options" => $request->correct_answer,
            'correct_answer' => $request->correct_answer,
        ]);
        
        return response()->json([
            'message' => 'quiz created successfully',
            'category' => $quiz
            ], 201);
        }else {
            return response()->json(['errors'=>$validator->errors()->all()],401);
            
        }
    }
    public function addcourse(Request $request){
        $validator = Validator::make($request->all(), [
            'category_id'=>'required|exists:categories,id',
            'teacherid'=>'required',
            'title'=>'required',
            'description'=>'required',
            'imageofcourse'=>'required|file',
            'duration'=>'required',
            'instructor'=>'required',
            'price'=>'required',
            'currency'=>'required'
            // 'name' => 'required|string', // You can adjust validation rules as needed
        ]);

        // Create a new course instance
        if (!$validator->fails()) {
            // $course = new courses();
    
            // Get the file from the request
            $file = $request->file('imageofcourse');
        
            // Generate a unique name for the file
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        
            // Move the file to the public directory under the course name
            $filePath = $file->move(public_path("assets/courses/{$request->title}"), $fileName);
        
            // Check if file move is successful
            if (!$filePath) {
                return response()->json(['error' => 'Failed to save file'], 500);
            }
            $course =courses::create([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'teacherid' => $request->teacherid,
                'description' => $request->description,
                'imageofcourse' => "assets/courses/{$request->title}/{$fileName}",
                'duration' => $request->duration,
                'instructor' => $request->instructor,
                'price' => $request->price,
                'currency' => $request->currency,
            // $category->name = $data['name'];
            ]);
            // $course->save();
    
            return response()->json([
                'message' => 'course created successfully',
                'course' => $course
            ], 201);    
        }else
        {
            return response()->json(['error'=>$validator], 401);
        }
        
    
    } 
    public function addcomment(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', 
            'content_id' => 'required|exists:contents,id', 
            'comment' => 'required', ]
        );
      if (!$validator->fails()) {
                // $course = new courses();
                $comment =comments::create([
                    'comment' => $request->comment,
                    'content_id' => $request->content_id,
                    'user_id' => $request->user_id,
                ]);
                // $course->save();
                
                return response()->json([
                    'message' => 'comment created successfully',
                    'comment' => $comment
                ], 201);    
            }else
            {
                return response()->json(['error'=>$validator], 401);
            }
    }
    public function addenrollment(Request $request){
        $validator = Validator::make($request->all(),[
            'course_id' => 'required|integer|exists:courses,id', 
            'user_id' => 'required|integer', 
            'enrollment_date' => 'required|date', 
        ]);
        // Create a new category instance
        if (!$validator->fails()) {
            $enrollment =enrollments::create([
                'course_id'=>$request->course_id,
                'user_id'=>$request->user_id,
                'enrollment_date'=>$request->enrollment_date
            ]);
            
            return response()->json([
                'message' => 'enrollment created successfully',
                'enrollmet' => $enrollment
            ], 201);
        } else {
            return response()->json(['errors'=>$validator->errors()->all()],401);
        }
       
        
    }
}