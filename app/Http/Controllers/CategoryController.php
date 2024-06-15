<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
            'name' => 'required|string|min:3',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }
        // Create a new category instance
        $category = category::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category,$id)
    {
        $category=category::find($id);
        $validator = Validator::make($request->all(),[ 
            'name' => 'required', 
        ]);
        if ($category) {
            if (!$validator->fails()) {
                $category->name=$request->input('name', $category->name);
                $category->save();
                return response()->json(['ok'=>'Category update seccussfully '], 200);
            } else {
                return response()->json($validator->errors(), 400);
            }
            
        } else {
            return response()->json(['error'=>'Category not found '], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category,$id)
    {
        $category=category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(['ok'=>'category deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'category not found '], 400);
        }
    }
}
