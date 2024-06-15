<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\user;
use App\Models\certificate;
use App\Models\course;
use Illuminate\Http\Request;
use Validator;
use Nette\Utils\Validators;
use Illuminate\Support\Str;
use PDF;


class CertificateController extends Controller
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
            'student_id' => 'required|integer',
            'teacher_id' =>'required|integer',
            'course_id' =>'required|integer|exists:courses,id'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }
        // Create a new category instance
      
        $teacher=user::find($request->teacher_id);
        $student=user::find($request->student_id);
        $course=course::find($request->course_id);
        $currentDate = Carbon::now();
    
        // Example of formatting the date
        $formattedDate = $currentDate->toDateString(); // Format: YYYY-MM-DD
        $formattedDateTime = $currentDate->toDateTimeString(); // Format: YYYY-MM-DD 

        $pdf = PDF::loadView('pdf_template',['teacher'=>$teacher ,'student'=>$student,'course'=>$course ,'date'=>$formattedDate ,'time'=>$formattedDateTime]);

        // Save the PDF to the public path
        $fileName = $student->name.'_'. $teacher->name .'_'.$course->title. $formattedDate .'_'  . time() . '.pdf';
        $path = public_path('certificates/' . $fileName);

        // Ensure the 'pdfs' directory exists in the public path
        if (!file_exists(public_path('certificates'))) {
            mkdir(public_path('certificates'), 0777, true);
        }

        $pdf->save($path);
        
        $certificate =certificate::create([
            'teacher_id' => $request->teacher_id,
            'user_id' => $request->student_id,
            'course_id' => $request->course_id,
            'url' => 'certificates/' . $fileName,
        ]);
        return response()->json([
            'message' => 'certificate created successfully',
            'certificate' => $certificate,
            'url' => url('certificates/' . $fileName)
        ], 201); 

    }
    public function generatePdf(Request $request)
    {
        // Assuming data is being sent via the request
        $data =certificate::all();
        // Design the PDF in a Blade view
        

        // Optionally return the path or URL to the PDF
    }

    /**
     * Display the specified resource.
     */
    public function show(certificate $certificate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, certificate $certificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(certificate $certificate,$id)
    {
        $certificate=certificate::find($id);
        if ($certificate) {
            $certificate->delete();
            return response()->json(['ok'=>'certificate deleted succesfully'], 200);
        } else {
           return response()->json(['error'=>'certificate not found '], 400);
        }
    }
}
