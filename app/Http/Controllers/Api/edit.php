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


class edit extends Controller
{
    public function editcourse(Request $request ,$id){
        $course = Courses::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'description' => 'string',
            'imageofcourse' => 'image',
            'category_id'=>'integer|exists:categories,id',
            'duration'=>'double',
            'instructor'=>'string',
            'price'=>'double',
            'currency'=>'string',
        ]);
        if (!$validator->fails()) {
            $course->title = $request->input('title', $course->title);
            $course->description = $request->input('description', $course->description);
            $course->imageofcourse = $request->input('imageofcourse', $course->imageofcourse);
            $course->category_id = $request->input('category_id', $course->category_id);
            $course->duration = $request->input('duration', $course->duration);
            $course->instructor = $request->input('instructor', $course->instructor);
            $course->price = $request->input('price', $course->price);
            $course->currency = $request->input('currency', $course->currency);
            $course->save();
            return response()->json(['message' => 'Course updated successfully'], 200);
        }else {
            return response()->json(['error' => $validator->errors()->all()], 400);
        } 

    }
    public function editcategory(Request $request ,$id){
        $category = categories::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
    
        if ($category) {
            if (!$validator->fails()) {
                // Access the 'name' field from the form-data
                $category->name = $request->input('name', $category->title);
                $category->save();
                return response()->json(['ok' => 'category updated successfully'], 200);
            } else {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }
        } else {
            return response()->json(['error' => 'category not found'], 400);
        }
    }
    public function editcontent(Request $request ,$id){
        $content = contents::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'desc' => 'string',
            'data' => '',
        ]);
    
        if ($content) {
            if (!$validator->fails()) {
                // Access the 'name' field from the form-data
                $content->title = $request->input('title', $content->title);
                $content->desc = $request->input('desc', $content->desc);
                $content->data = $request->input('data', $content->data);
                $content->save();
                return response()->json(['ok' => 'content updated successfully'], 200);
            } else {
                return response()->json(['error' => $validator->errors()->all()], 400);
            }
        } else {
            return response()->json(['error' => 'content not found'], 400);
        }
    }
    public function editquiz(Request $request ,$id){
        $quiz=quizzes::findOrFail($id);
        $validator = Validator::make($request->all(),[ 
            'title' => 'required', 
            'question' => 'required', 
            'options' => '', 
            'correct_answer' => 'required',
        ]);
        if ($quiz) {
            if (!$validator->fails()) {
                $quiz->title=$request->input('title', $quiz->title);
                $quiz->question=$request->input('question', $quiz->question);
                $quiz->options=$request->input('options', $quiz->options);
                $quiz->correct_answer=$request->input('correct_answer', $quiz->correct_answer);
                $quiz->save();
                return response()->json(['ok'=>'quiz update seccussfully '], 200);
            } else {
                return response()->json(['error'=>$validator->errors()->all()], 200);
            }
            
        } else {
            return response()->json(['error'=>'quiz not found '], 400);
        }
    }
    public function editcomment(Request $request ,$id){
        $comment=comments::findOrFail($id);
        $validator = Validator::make($request->all(),[ 
            'comment' => 'required', 
        ]);
        if ($comment) {
            if (!$validator->fails()) {
                $comment->comment=$request->input('comment', $comment->comment);
                $comment->save();
                return response()->json(['ok'=>'comment update seccussfully '], 200);
            } else {
                return response()->json(['error'=>$validator->errors()->all()], 200);
            }
            
        } else {
            return response()->json(['error'=>'comment not found '], 400);
        }
        

    }
    public function editerollment(Request $request ,$id){
       return 'ernrollment update ';
    }
}
