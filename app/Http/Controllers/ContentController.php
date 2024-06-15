<?php

namespace App\Http\Controllers;

use App\Models\content;
use App\Models\course;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class ContentController extends Controller
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
        $course = Course::findOrFail($request->courseid);
    
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
        $content = Content::create([
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

    /**
     * Display the specified resource.
     */
    public function show(content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, content $content,$id)
    {
        $content = content::findOrFail($id);
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
    public function editActive($id, Request $request){
        $content = Content::findOrFail($id); // Assuming your model name is Content, and 'Content' should start with a capital letter
        
        $validator = Validator::make($request->all(), [
            'active' => 'required|boolean',
        ]);
    
        if (!$validator->fails()){
            $content->active = $request->input('active');
            $content->save(); // You need to save the changes to the model
    
            return response()->json(['success' => 'success'], 200);
        } else {
            return response()->json($validator->errors()->all(), 401);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(content $content)
    {
        //
    }
}
