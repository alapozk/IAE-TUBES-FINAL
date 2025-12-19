<?php

namespace App\GraphQL\Resolvers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthResolver
{
    /**
     * Login mutation
     */
    public function login($_, array $args)
    {
        $user = User::where('email', $args['email'])->first();

        if (!$user || !Hash::check($args['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        Auth::login($user);

        return [
            'user' => $user,
            'message' => 'Login berhasil'
        ];
    }

    /**
     * Register mutation
     */
    public function register($_, array $args)
    {
        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'role' => 'student', // default role
        ]);

        Auth::login($user);

        return [
            'user' => $user,
            'message' => 'Registrasi berhasil'
        ];
    }

    /**
     * Logout mutation
     */
    public function logout($_, array $args)
    {
        Auth::logout();

        return [
            'user' => null,
            'message' => 'Logout berhasil'
        ];
    }

    /**
     * Get current authenticated user
     */
    public function me($_, array $args)
    {
        return Auth::user();
    }
}
