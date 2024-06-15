<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\enrollment;
use App\Models\course;
use App\Models\subcategory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthsOfYear = range(1, 12); // Array representing all months of the year

        // Generate a subquery to create a table with all months of the year
        $subQuery = DB::table(DB::raw('(SELECT ' . implode(' AS month UNION SELECT ', $monthsOfYear) . ' AS month) months'))->distinct();
        
        // Your original query to count enrollments for each month
        $query = DB::table('enrollments')
            ->select(DB::raw('DATE_FORMAT(enrollment_date, "%m") AS month'), DB::raw('COUNT(*) AS count'))
            ->whereYear('enrollment_date', date('Y'))
            ->groupBy(DB::raw('DATE_FORMAT(enrollment_date, "%m")'));
        
        // Left join the subquery with your original query
        $result = $subQuery->leftJoinSub($query, 'enrollments', function ($join) {
            $join->on('months.month', '=', 'enrollments.month');
        })->select('months.month', DB::raw('COALESCE(count, 0) as count'))->orderBy('months.month')->get();

        $countenrollmets = [];

        // Loop through the result set and populate the $count array
        foreach ($result as $row) {
            $countenrollmets[$row->month] = $row->count;
        }

        ///-----------------------------
        $currentYear = date('Y');

        // Generate a subquery to create a table with all months of the current year
        $subQuery = DB::table(DB::raw('(SELECT 1 AS month UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) months'))->distinct();
        
        // Your original query
        $enrollmentPayments = $subQuery
            ->leftJoinSub(Enrollment::select(
                    DB::raw('DATE_FORMAT(enrollment_date, "%Y") AS year'),
                    DB::raw('DATE_FORMAT(enrollment_date, "%m") AS month'),
                    DB::raw('SUM(payment_amount) AS total_payment')
                )
                ->whereYear('enrollment_date', $currentYear)
                ->groupBy(DB::raw('DATE_FORMAT(enrollment_date, "%Y")'), DB::raw('DATE_FORMAT(enrollment_date, "%m")')), 'enrollments', function ($join) {
                    $join->on('months.month', '=', 'enrollments.month');
                })
            ->select('months.month', DB::raw('COALESCE(SUM(total_payment), 0) AS total_payment'))
            ->groupBy('months.month')
            ->orderBy('months.month', 'asc')
            ->get();

            $totalpayment = [];

            // Loop through the result set and populate the $count array
            foreach ($enrollmentPayments as $row) {
                $totalpayment[$row->month] = $row->total_payment;
            }
        return response()->json([
            'teachers' => User::where('role', 1)->get(),
            'countcourse' => Course::all()->count(),
            'countenrollment' => Enrollment::all()->count(),
            'countuser' => User::all()->count(),
            'countsubcategory' => Subcategory::all()->count(),

            'enrollmentPerMonth' => $countenrollmets,    
            'sumPerMonth' => $totalpayment,

            'countAAdmin' => User::where('role', 0)->count(),
            'countTeacher' => User::where('role', 1)->count(),
            'countStudent' => User::where('role', 2)->count()
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
