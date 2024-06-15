<?php

namespace App\Http\Controllers;

use App\Models\savedvideo;
use App\Models\content;
use Illuminate\Http\Request;
use Nette\Utils\Validators;
use Illuminate\Support\Str;
use Validator;


class SavedvideoController extends Controller
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
            'videourl' => 'required|string',
            'user_id' => 'required|integer',
        ]);
      
        // Create a new category instance
        $savedvideo = savedvideo::create([
            'videourl' => $request->videourl,
            'user_id' => $request->user_id, // Use $fileName directly
        ]);
    
        return response()->json([
            'message' => 'Sub saveVideo created successfully',
            'category' => $savedvideo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(savedvideo $savedvideo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(savedvideo $savedvideo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, savedvideo $savedvideo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(savedvideo $savedvideo)
    {
        //
    }
}
