<?php

namespace App\Http\Controllers;

use App\Models\history;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class HistoryController extends Controller
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
        $history = Validator::make($request->all(), [
            'url' => 'required|string',
            'user_id' => 'required',
        ]);
      
        // Create a new category instance
        $history = history::create([
            'url' => $request->url,
            'user_id' => $request->user_id, // Use $fileName directly
        ]);
    
        return response()->json([
            'message' => 'Sub history created successfully',
            'history' => $history
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(history $history)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(history $history)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, history $history)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(history $history)
    {
        //
    }
}
