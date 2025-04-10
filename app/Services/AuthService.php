<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
  public function register(array $data): User
  {
    // by default registerd account with user role
    $role = Role::where('name', 'user')->first();

    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'role_id' => $role->id,
    ]);

    return $user->with('roles')->find($user->id);
  }
}