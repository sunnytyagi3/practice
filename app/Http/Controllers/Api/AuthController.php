<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use PhpParser\Node\Stmt\TryCatch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function createUser(Request $request){
     
        try {
        
        
     
        $validateuser = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'

        ]);


        if($validateuser->fails()){
            return response() -> json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateuser->errors()
            ], 401);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);



        return response() -> json([
            'status' => true,
            'message' => 'User created successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);



    }
       catch (\Throwable $th) {
        return response() -> json([
            'status' => false,
            'message' => $th->getMessage()
            
        ], 500);
       }
    }



    public function loginUser(Request $request){
        try {



            $validateuser = Validator::make($request->all(),[
                
                'email' => 'required|email',
                'password' => 'required'
    
            ]);
    
    
            if($validateuser->fails()){
                return response() -> json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateuser->errors()
                ], 401);
            }



            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()-json([
                    'status' => false,
                    'message' => 'user password not match'
                    
                ], 402);
            }

            $user = user::where('email', $request->email)->first();

            return response() -> json([
                'status' => true,
                'message' => 'User Logged In successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'token_type' => 'Bearer'
            ], 200);
    






        }
        
        
        catch (\Throwable $th) {
        

            return response() -> json([
                'status' => false,
                'message' => $th->getMessage()
                
            ], 501);


        }
    }
}    
