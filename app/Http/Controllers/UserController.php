<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends \App\Http\Controllers\Controller
{
    // Hanya admin yang bisa akses controller ini
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // List semua user
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Detail user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,user',
        ]);
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User updated!');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted!');
    }
}
