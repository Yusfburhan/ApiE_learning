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


class delete extends Controller
{
    public function deletecategory($id){
        $category=categories::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['ok'=>'category deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'category not found '], 400);
        }
        
    }
    public function deletecourse($id){
        $course=courses::find($id);
        if ($course) {
            $course->delete();
            return response()->json(['ok'=>'course deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'course not found '], 400);
        }
        
    }
    // public function deletecontent($id){
    //     $content=contents::find($id);
    //     if ($content) {
    //         $content->delete();
    //         return response()->json(['ok'=>'content deleted succesfully'], 200);
    //     } else {
    //        return response()->json(['error'=>'content not found '], 400);
    //     }
        
    // }
    public function deleteContent($id) {
        $content = Contents::find($id);
    
        if (!$content) {
            return response()->json(['error' => 'Content not found'], 404);
        }
    
        // Decode the JSON data into an array
        $data = json_decode($content->data, true);
    
        // Check if the data is valid JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON data'], 500);
        }
    
        // Get the file path from the decoded data
        $filePath = public_path($data['file_path']);
    
        // Check if the file exists
        if (file_exists($filePath)) {
            // Delete the file
            unlink($filePath);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    
        // Delete the content record from the database
        $content->delete();
    
        return response()->json(['message' => 'Content deleted successfully'], 200);
    }
    
    
    
    

    
    
    public function deletequiz($id){
        $quiz=quizzes::find($id);
        if ($quiz) {
            $quiz->delete();
            return response()->json(['ok'=>'quiz deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'quiz not found '], 400);
        }
        
    }
    public function deletecomment($id){
        $comment=comments::find($id);
        if ($comment) {
            $comment->delete();
            return response()->json(['ok'=>'comment deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'comment not found '], 400);
        }
        
    }
    public function deleteenrollment($id){
        $enrollment=enrollments::find($id);
        if ($enrollment) {
            $enrollment->delete();
            return response()->json(['ok'=>'enrollment deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'enrollment not found '], 400);
        }
        
    }
    
}
