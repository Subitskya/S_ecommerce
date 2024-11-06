<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }

    public function create()
    {
        return view('admin.create'); 
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,user,seller',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user,seller',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password);
        }

        $user->update($validatedData);

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully');
    }
}
