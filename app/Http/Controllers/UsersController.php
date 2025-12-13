<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
  /**
   * Display a listing of the users.
   */
  public function index()
  {
    $users = User::with(['role', 'prodi'])->get();

    return view('users.index', compact('users'));
  }

  /**
   * Show the form for creating a new user.
   */
  public function create()
  {
    $roles = Role::all();
    $prodis = Prodi::all();

    return view('users.create', compact('roles', 'prodis'));
  }

  /**
   * Store a newly created user in storage.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'username' => 'required|string|max:255|unique:users,username',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'role_id' => 'required|exists:roles,id',
      'prodi_id' => 'nullable|exists:prodis,id',
    ]);

    // Hash password
    $validated['password'] = Hash::make($validated['password']);

    // Create user
    User::create($validated);

    return redirect()->route('users.index')
      ->with('success', 'User berhasil ditambahkan!');
  }

  /**
   * Display the specified user.
   */
  public function show(User $user)
  {
    $user->load(['role', 'prodi']);

    return view('users.show', compact('user'));
  }

  /**
   * Show the form for editing the specified user.
   */
  public function edit(User $user)
  {
    $roles = Role::all();
    $prodis = Prodi::all();

    return view('users.edit', compact('user', 'roles', 'prodis'));
  }

  /**
   * Update the specified user in storage.
   */
  public function update(Request $request, User $user)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
      'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
      'password' => 'nullable|string|min:8|confirmed',
      'role_id' => 'required|exists:roles,id',
      'prodi_id' => 'nullable|exists:prodis,id',
    ]);

    // Only update password if provided
    if (!empty($validated['password'])) {
      $validated['password'] = Hash::make($validated['password']);
    } else {
      unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('users.index')
      ->with('success', 'User berhasil diupdate!');
  }

  /**
   * Remove the specified user from storage.
   */
  public function destroy(User $user)
  {
    // Prevent deleting yourself
    if ($user->id === auth()->id()) {
      return redirect()->route('users.index')
        ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
    }

    $user->delete();

    return redirect()->route('users.index')
      ->with('success', 'User berhasil dihapus!');
  }
}
