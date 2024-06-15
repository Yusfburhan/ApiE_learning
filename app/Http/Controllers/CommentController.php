<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class CommentController extends Controller
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
            'user_id' => 'required', 
            'content_id' => 'required|exists:contents,id', 
            'comment' => 'required', ]
        );
      if (!$validator->fails()) {
                // $course = new courses();
                $comment =comment::create([
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
                return response()->json(['error'=>$validator->errors()->all()], 401);
        }
   
    }

    /**
     * Display the specified resource.
     */
    public function show(comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, comment $comment,$id)
    {
        $comment=comment::find($id);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(comment $comment,$id)
    {
        $comment=comment::find($id);
        if ($comment) {
            $comment->delete();
            return response()->json(['ok'=>'comment deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'comment not found '], 400);
        }
    }
}
