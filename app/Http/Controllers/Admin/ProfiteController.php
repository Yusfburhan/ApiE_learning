<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class ProfiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Calculate the total payment amount for the current year
        $totalPaymentCurrentYear = Enrollment::whereYear('enrollment_date', $currentYear)->sum('payment_amount');

        // Calculate the total payment amount for the current month
        $totalPaymentCurrentMonth = Enrollment::whereYear('enrollment_date', $currentYear)
            ->whereMonth('enrollment_date', $currentMonth)
            ->sum('payment_amount');

        //---------------------
        $currentYear = Carbon::now()->year;

        // Calculate the range of years for the last 10 years
        $lastTenYears = range($currentYear - 9, $currentYear);
        
        // Initialize an array to store total payments for each year
        $totalPayments = [];

        // Loop through the last 10 years
        foreach ($lastTenYears as $year) {
            // Calculate total payment for the current year
            $totalPayment = Enrollment::whereYear('enrollment_date', $year)->sum('payment_amount');
        
            // Store total payment for the current year in the format {year: total_payment}
            $totalPayments[$year] = $totalPayment;
        }
        // ----------------------------------week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Calculate the sum of payment_amount for the current week
        $sumPaymentAmount = Enrollment::whereBetween('enrollment_date', [$startOfWeek, $endOfWeek])
                                      ->sum('payment_amount');

        return response()->json([
            'PaymentYears'=>$totalPaymentCurrentYear,
            'PaymentMonth'=>$totalPaymentCurrentMonth,
            'PaymentWeek'=>$sumPaymentAmount,
            'TotalPerYears'=>$totalPayments,
            'TotalPerMounthYears'=>Enrollment::select(DB::raw('MONTH(enrollment_date) as month'), DB::raw('YEAR(enrollment_date) as year'), DB::raw('sum(payment_amount) as total'))
            ->groupBy(DB::raw('MONTH(enrollment_date)'), DB::raw('YEAR(enrollment_date)'))
            ->orderBy('enrollment_date','desc')
            ->get()
            ->map(function ($item) {
                return [
                    "date"=>"{$item->month}/{$item->year}",
                    "total"=>"{$item->total}"

                ];
            })

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
