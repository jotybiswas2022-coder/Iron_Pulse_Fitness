<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // List all non-admin users
    public function index()
    {
        $users = User::where('is_admin', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.customer.index', compact('users'));
    }

    // Show create user form
    public function create()
    {
        return view('backend.customer.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 0,
        ]);

        return redirect('/admin/customers')->with('success', 'User account created successfully!');
    }

    // Update existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/admin/customers')->with('success', 'User updated successfully!');
    }

    // Delete user
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin/customers')->with('success', 'User deleted successfully!');
    }

    // Make Admin
    public function makeAdmin($id){
        $user = User::findOrFail($id);
        $user->is_admin = 1;
        $user->save();
        return redirect()->back()->with('success', $user->name.' is now an Admin!');
    }

    // Make User Again
    public function makeUser($id){
        $user = User::findOrFail($id);
        $user->is_admin = 0;
        $user->save();
        return redirect()->back()->with('success', $user->name.' is now a regular User!');
    }
}