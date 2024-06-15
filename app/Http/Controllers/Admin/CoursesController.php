<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\course;
use App\Models\subcategory;
use App\Models\category;
use App\Models\enrollment;
use Illuminate\Support\Facades\DB;


class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::with('user', 'course')->get();

        // Transform the data into the desired format
        $enrollmentData = $enrollments->map(function ($enrollment) {
            return [
                'enrollment_username' => $enrollment->user->name, // Assuming 'name' is the username field
                'enrollment_coursename' => $enrollment->course->title   , // Assuming 'name' is the course name field
                'amount_payment' => $enrollment->payment_amount,
                'date' => $enrollment->enrollment_date,
            ];
        });
        return response()->json([
            'courseCount'=>course::all()->count(),
            'courses'=>course::all(),
            'categouries'=>category::all(),
            'subCategories'=>subcategory::all(),
            'categories'=>category::all(),
            'enrollments'=>$enrollmentData,
            'enrollmentPerMonth'=>DB::table('enrollments')
            ->select(DB::raw('MONTH(enrollment_date) as month'), DB::raw('count(*) as count'))
            ->whereYear('enrollment_date', date('Y'))
            ->groupBy(DB::raw('MONTH(enrollment_date)'))
            ->get(),
            'courseCreatedPerMonth' => Course::select(DB::raw('MONTH(created_at) AS month'), DB::raw('COUNT(*) AS total_courses_created'))
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
