<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return User::select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        // Seul le super_admin peut créer des admins
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:super_admin,stock_manager,order_manager',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        \App\Models\ActivityLog::record(
            'admin_created',
            "Nouveau admin créé : {$user->name} ({$user->email}) — Rôle : {$user->role}",
            'User',
            $user->id
        );

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'role' => 'sometimes|in:super_admin,stock_manager,order_manager',
            'is_active' => 'sometimes|boolean',
            'password' => 'sometimes|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        \App\Models\ActivityLog::record(
            'admin_updated',
            "Admin modifié : {$user->name} ({$user->email})",
            'User',
            $user->id
        );

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Impossible de supprimer votre propre compte'], 400);
        }

        $name = $user->name;
        $user->delete();

        \App\Models\ActivityLog::record(
            'admin_deleted',
            "Admin supprimé : {$name}",
            'User'
        );

        return response()->json(['message' => 'Admin supprimé']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
} 