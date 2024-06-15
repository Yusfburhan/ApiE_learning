<?php

namespace App\Http\Controllers;

use App\Models\course;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id'=>'required|exists:subcategories,id',
            'teacherid'=>'required',
            'title'=>'required',
            'description'=>'required',
            'imageofcourse'=>'required|file',
            'duration'=>'required',
            'instructor'=>'required',
            'price'=>'required',
            'currency'=>'required',
            'rating'=>'required'
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
            $course =course::create([
                'title' => $request->title,
                'subcategory_id' => $request->subcategory_id,
                'teacherid' => $request->teacherid,
                'description' => $request->description,
                'imageofcourse' => "assets/courses/{$request->title}/{$fileName}",
                'duration' => $request->duration,
                'instructor' => $request->instructor,
                'price' => $request->price,
                'currency' => $request->currency,
                'rating' => $request->rating,
            ]);
            // $course->save();
    
            return response()->json([
                'message' => 'course created successfully',
                'course' => $course
            ], 201);    
        }else
        {
            // return response()->json($validator->errors(), 400);

            return response()->json($validator->errors(), 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, course $course,$id)
    {
        $course = Course::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'string',
            'description' => 'string',
            'imageofcourse' => 'image',
            'subcategory_id'=>'integer|exists:subcategories,id',
            'duration'=>'double',
            'instructor'=>'string',
            'price'=>'double',
            'currency'=>'string',
            'rating'=>'integer',
        ]);
        if (!$validator->fails()) {
            $course->title = $request->input('title', $course->title);
            $course->description = $request->input('description', $course->description);
            $course->imageofcourse = $request->input('imageofcourse', $course->imageofcourse);
            $course->subcategory_id = $request->input('subcategory_id', $course->subcategory_id);
            $course->duration = $request->input('duration', $course->duration);
            $course->instructor = $request->input('instructor', $course->instructor);
            $course->price = $request->input('price', $course->price);
            $course->currency = $request->input('currency', $course->currency);
            $course->rating = $request->input('rating', $course->rating);
            $course->save();
            return response()->json(['message' => 'Course updated successfully'], 200);
        }else {
            return response()->json(['error' => $validator->errors()->all()], 400);
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course,$id)
    {
        $course=course::find($id);
        if ($course) {
            $course->delete();
            return response()->json(['ok'=>'course deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'course not found '], 400);
        }
    }
}
