<?php

namespace App\Http\Controllers;

use App\Models\replay;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class ReplayController extends Controller
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
            'comment_id' => 'required|integer|exists:comments,id',
            'user_id'=>'required|integer',
            'replaycomment'=>'required|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }
        // Create a new category instance
        $replay = replay::create([
            'user_id' => $request->user_id,
            'comment_id' => $request->comment_id,
            'replaycomment' => $request->replaycomment,
        ]);
        return response()->json([
            'message' => 'replay created successfully',
            'replay' => $replay
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(replay $replay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(replay $replay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, replay $replay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(replay $replay,$id)
    {
        $replay=replay::find($id);
        if ($replay) {
            $replay->delete();
            return response()->json(['ok'=>'replay deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'replay not found '], 400);
        }
    }
}
