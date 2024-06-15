<?php

namespace App\Http\Controllers;

use App\Models\quiz;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class QuizController extends Controller
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
            'title' => 'required|string', 
            'course_id' => 'required|integer|exists:courses,id', 
            'question' => 'required|string', 
            'option1' => '', 
            'option1' => '', 
            'option1' => '', 
            'option1' => '', 
            'correct_answer' => 'required|string', 
        ]);
        if (!$validator->fails()) {
            $quiz = quiz::create([
            'title' => $request->title,
            "course_id" => $request->course_id,
            'question' => $request->question,
            "option1" => $request->option1,
            "option2" => $request->option2,
            "option3" => $request->option3,
            "option4" => $request->option4,
            'correct_answer' => $request->correct_answer,
        ]);
        
        return response()->json([
            'message' => 'quiz created successfully',
            'category' => $quiz
            ], 201);
        }else {
            return response()->json(['errors'=>$validator->errors()],401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(quiz $quiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(quiz $quizm)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, quiz $quiz,$id)
    {
        $quiz=quiz::find($id);
        $validator = Validator::make($request->all(),[ 
            'title' => 'required', 
            'question' => 'required', 
            'option1' => '', 
            'option2' => '', 
            'option3' => '', 
            'option4' => '', 
            'correct_answer' => 'required',
        ]);
        if ($quiz) {
            if (!$validator->fails()) {
                $quiz->title=$request->input('title', $quiz->title);
                $quiz->question=$request->input('question', $quiz->question);
                $quiz->option1=$request->input('option1', $quiz->option1);
                $quiz->option2=$request->input('option2', $quiz->option2);
                $quiz->option3=$request->input('option3', $quiz->option3);
                $quiz->option4=$request->input('option4', $quiz->option4);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(quiz $quiz,$id)
    {
        $quiz=quiz::find($id);
        if ($quiz) {
            $quiz->delete();
            return response()->json(['ok'=>'quiz deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'quiz not found '], 400);
        }
    }
}
