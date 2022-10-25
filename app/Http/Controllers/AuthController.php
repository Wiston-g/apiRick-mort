<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validateData['name'], 
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
        ]);

       
        return response()->json([
            "status" => 1,
            "msg" => "Registro exitoso",
            "user" => $user,
        ],201);
    }

    public function login(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where("email", "=", $validateData['email'])->first();

        if (isset($user->id)) {
            if (Hash::check($validateData['password'], $user->password)) {
                
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    "status" => 1,
                    "msg" => "Usuario logeado",
                    'access_token' => $token,
                    'token_type'=> 'Bearer',
                ],200);

            }else{
                return response()->json([
                    "status" => 0,
                    "msg" => "Password incorrecto",
                ],400);    
            }
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "Usuario no registrado",
            ],400);  
        }
    }

    public function completeInformation(Request $request)
    {
        $user = auth()->user();
        
        $userData = User::find($user->id);

        if (isset($userData->id)){
            
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
                'address' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'city' => 'required|string|max:255'
            ]);
            
            $userData->update([
                'name' => $validateData['name'], 
                'email' => $validateData['email'],
                'password' => Hash::make($validateData['password']),
                'address' => $validateData['address'],
                'birthdate' => $validateData['birthdate'],
                'city' => $validateData['city']
            ]);

            $userData = User::find($user->id);

            return response()->json([
                "status" => 1,
                "msg" => "Usuario Actualizado ",
                "user" => $userData,
            ],201);

        }else{
            return response()->json([
                "status" => 0,
                "msg" => "Usuario no actulizado",
            ],400);  
        }
      
    }

    public function userProfile()
    {
        return response()->json([
            "status" => 1,
            "msg" => "Mostrando Usuario ",
            "user" => auth()->user()
        ],200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        
        return response()->json([
            "status" => 1,
            "msg" => "Logout",
        ],200);
    }
}
