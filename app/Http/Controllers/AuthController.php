<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!auth()->attempt($credentials)) {
        return response()->json(['message' => 'Identifiants incorrects'], 401);
    }

    $user = auth()->user();

    if (!$user->is_active) {
        auth()->logout();
        return response()->json(['message' => 'Compte désactivé'], 403);
    }

    $token = $user->createToken('admin-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }
}