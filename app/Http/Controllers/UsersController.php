<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
  public function index()
  {
    $users = User::all(); // Ambil semua data users

    return view('users.index', compact('users'));
  }
}
