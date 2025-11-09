<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrier;

class AuthController extends Controller
{
        public function register(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            
            $userCount = User::count();

            
            $role = $userCount < 2 ? 'admin' : 'carrier';

            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role,
            ]);

            
            if ($role === 'carrier') {
                Carrier::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'user_id' => $user->id,
                ]);
            }

            return response()->json([
                'message' => 'Sikeres regisztráció!',
                'user' => $user
            ], 201);
        }



        public function login(Request $request)
        {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Hibás bejelentkezési adatok!'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Sikeres bejelentkezés!',
                'token' => $token,
                'user' => $user,
            ]);
        }
}
