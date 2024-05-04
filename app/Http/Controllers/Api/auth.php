<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\user;
use Nette\Utils\Validators;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\verified;

class auth extends Controller
{
    public function login(Request $request){
        $validator= Validator::make($request->all(),[
            'email'=>'required|email|exists:users|email',
            'password' =>'required|min:8',
        ]);
        if (!$validator->fails()) {
            $user =user::where('email',$request->email)->first();
            if (Hash::check($request->password, $user->password)) {
            // if($request->password === $user->password){
                return response()->json([
                    'user'=>$user,
                    'token'=>$user->createToken('authToken')->plainTextToken
                ],200);
            }else{
                return response()->json( ['password'=>[__('auth.failed')]],401);
            }
        }else {
            return response()->json(['errors'=>$validator->errors()->all()],401);
        }
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['logout'=>'alhamdwlila logout suseccfully'],200);
    }
//     public function register(Request $request)
// {
//     // Validate incoming request
//     $validatedData = $request->validate([
//         'name' => 'required|string|max:255',
//         'email' => 'required|email|unique:users,email',
//         'password' => 'required|string|min:8',
//         'image' => 'required|file',
//         'phone'=>'',
//         'role' => 'required' // Assuming roles can be 'user' or 'admin'
//     ]);

//     // Get the file from the request
//     $file = $request->file('image');

//     // Generate a unique name for the file
//     $fileName = $validatedData['name'] . '.' . $file->getClientOriginalExtension();

//     // Move the file to the public directory under the course name
//     $filePath = $file->move(public_path("assets/userImages"), $fileName);

//     // Check if file move is successful
//     if (!$filePath) {
//         return response()->json(['error' => 'Failed to save file'], 500);
//     }

//     // Update the image field in validated data
//     $validatedData['image'] = 'assets/userImages/'.$fileName;

//     // Hash the password
//     $validatedData['password'] = Hash::make($request->password);

//     // Create a new user
//     $user = User::create($validatedData);

//     // Return response with user data and token
//     return response()->json([
//         'user' => $user,
//         'token' => $user->createToken('authToken')->plainTextToken
//     ], 201);
// }
public function register(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            // 'role' => ['required', Rule::in(['student', 'teacher'])], // Role can be either student or teacher
            'role' => 'required', // Role can be either student or teacher
            'image' => 'required|file',
        ]);
                // $course = new courses();
    
            // Get the file from the request
            $file = $request->file('image');
        
            // Generate a unique name for the file
            $fileName = $request->name.'___'.uniqid() . '.' . $file->getClientOriginalExtension();
        
            // Move the file to the public directory under the course name
            $filePath = $file->move(public_path("assets/userImages"), $fileName);
        
            // Check if file move is successful
            if (!$filePath) {
                return response()->json(['error' => 'Failed to save file'], 500);
            }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'image' => "assets/userImages/{$fileName}",
        ]);

        // Generate token
        $token = $user->createToken('authToken')->plainTextToken;

        // Return token
        return response()->json(['token' => $token,'user'=>$user], 201);
    }

public function editUser($userid ,Request $request){}

    
    

}
