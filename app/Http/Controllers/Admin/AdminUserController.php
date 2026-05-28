<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = User::whereIn('role', ['owner', 'admin'])
            ->orderByRaw("CASE WHEN role = 'owner' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('admin.admin-users.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin-users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin employee added.');
    }

    public function destroy(User $adminUser)
    {
        if ($adminUser->isOwner()) {
            return back()->with('error', 'Owner account cannot be deleted.');
        }

        if ($adminUser->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($adminUser->role !== 'admin') {
            return back()->with('error', 'Only admin employee accounts can be deleted here.');
        }

        $adminUser->delete();

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin employee deleted.');
    }
}
