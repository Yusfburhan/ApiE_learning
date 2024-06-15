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
use Exception;

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

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required',
                'category1' => '',
                'category2' => '',
                'category3' => '',
                'work' => 'string',
                'image' => 'required|file',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            // The rest of your function code...
    
            // Get the file from the request
            $file = $request->file('image');
    
            // Generate a unique name for the file
            $fileName = $request->name . '___' . uniqid() . '.' . $file->getClientOriginalExtension();
    
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
                'work' => $request->work,
                'category1' => $request->category1,
                'category2' => $request->category2,
                'category3' => $request->category3,
                'image' => "assets/userImages/{$fileName}",
            ]);
    
            // Generate token
            $token = $user->createToken('authToken')->plainTextToken;
    
            // Return token
            return response()->json(['token' => $token, 'user' => $user], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
        {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:8',
            'phone' => 'sometimes|nullable|string',
            'image' => 'sometimes|nullable|file|image|max:2048', // Image validation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user
        $user = User::findOrFail($id);
        // Update user information
        $user->name = $request->get('name', $user->name);
        $user->email = $request->get('email', $user->email);
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->phone = $request->get('phone', $user->phone);
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($user->image && Storage::exists($user->image)) {
                Storage::delete($user->image);
            }
            // Store the new image
            $path = $request->file('image')->store('public/asstes/userImages');
            $user->image = $path;
        }

        // Save the user
        $user->save();

        return response()->json(['user' => $user], 200);
    }

    
    

}
