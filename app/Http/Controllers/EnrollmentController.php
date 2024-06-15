<?php

namespace App\Http\Controllers;

use App\Models\enrollment;
use App\Models\course;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;

class EnrollmentController extends Controller
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
        $validator = Validator::make($request->all(),[
            'course_id' => 'required|integer|exists:courses,id', 
            'user_id' => 'required|integer', 
        ]);
        $course=course::find($request->course_id);
        // Create a new category instance
        if (!$validator->fails()) {
            $enrollment =enrollment::create([
                'course_id'=>$request->course_id,
                'user_id'=>$request->user_id,
                'enrollment_date' => now(),
                'payment_amount'=>$course->price
            ]);
            return response()->json([
                'message' => 'enrollment created successfully',
                'enrollmet' => $enrollment
            ], 201);
        } else {
            return response()->json(['errors'=>$validator->errors()->all()],401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(enrollment $enrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(enrollment $enrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, enrollment $enrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(enrollment $enrollment,$id)
    {
        $enrollment=enrollment::find($id);
        if ($enrollment) {
            $enrollment->delete();
            return response()->json(['ok'=>'enrollment deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'enrollment not found '], 400);
        }
    }
}
