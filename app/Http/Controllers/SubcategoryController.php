<?php

namespace App\Http\Controllers;

use App\Models\subcategory;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; // Import File facade

class SubcategoryController extends Controller
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
            'name' => 'required|string',
            'image' => 'required|file',
            'category_id'=>'exists:categories,id'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
    
        // Get the file from the request
        $file = $request->file('image');
    
        // Generate a unique name for the file
        $fileName = $request->name.uniqid(). '.' . $file->getClientOriginalExtension();
    
        // Move the file to the public directory under the course name
        $filePath = $file->move(public_path("assets/imagesubCategories"), $fileName);
    
        // Check if file move is successful
        if (!$filePath) {
            return response()->json(['error' => 'Failed to save file'], 500);
        }
    
        // Create a new category instance
        $subcategory = subcategory::create([
            'name' => $request->name,
            'image' => "assets/imagesubCategories/".$fileName, // Use $fileName directly
            'category_id'=>$request->category_id
        ]);
    
        return response()->json([
            'message' => 'Sub Category created successfully',
            'category' => $subcategory
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(subcategory $subcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, subcategory $subcategory)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(subcategory $subcategory ,$id)
    {
        // Find the subcategory by ID
        $subcategory = Subcategory::findOrFail($id);

        // Get the image path
        $imagePath = public_path($subcategory->image);

        // Check if the image exists and delete it
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Delete the subcategory
        $subcategory->delete();

        // Return a response
        return response()->json([
            'message' => 'Subcategory deleted successfully!'
        ], 200);
    }
}
