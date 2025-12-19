<?php

namespace App\GraphQL\Resolvers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResolver
{
    /**
     * Verify admin access
     */
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            throw new \Exception('Unauthorized. Admin access required.');
        }
    }

    /**
     * List users filtered by role (admin only)
     */
    public function list($_, array $args)
    {
        $this->checkAdmin();

        $query = User::query();

        // Filter by role if provided
        if (!empty($args['role'])) {
            $query->where('role', $args['role']);
        } else {
            // Exclude admins from general list
            $query->whereIn('role', ['teacher', 'student']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create new user (admin only)
     */
    public function create($_, array $args)
    {
        $this->checkAdmin();

        // Check if email already exists
        if (User::where('email', $args['email'])->exists()) {
            throw new \Exception('Email sudah terdaftar.');
        }

        // Validate role
        if (!in_array($args['role'], ['teacher', 'student', 'admin'])) {
            throw new \Exception('Role tidak valid. Pilih: teacher, student, atau admin.');
        }

        return User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'role' => $args['role'],
        ]);
    }

    /**
     * Update existing user (admin only)
     */
    public function update($_, array $args)
    {
        $this->checkAdmin();

        $user = User::findOrFail($args['id']);

        // Check if email is taken by another user
        if (User::where('email', $args['email'])->where('id', '!=', $user->id)->exists()) {
            throw new \Exception('Email sudah digunakan user lain.');
        }

        // Validate role
        if (!in_array($args['role'], ['teacher', 'student', 'admin'])) {
            throw new \Exception('Role tidak valid.');
        }

        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->role = $args['role'];

        // Update password if provided
        if (!empty($args['password'])) {
            $user->password = Hash::make($args['password']);
        }

        $user->save();

        return $user;
    }

    /**
     * Delete user (admin only)
     */
    public function delete($_, array $args)
    {
        $this->checkAdmin();

        $user = User::findOrFail($args['id']);

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            throw new \Exception('Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return true;
    }
}
