<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder {
    public function run(): void {
        $admin = User::create([
            'name'=>'Admin','email'=>'admin@demo.test',
            'password'=>Hash::make('password'),'role'=>'admin','status'=>'active'
        ]);
        $teacher = User::create([
            'name'=>'Guru Satu','email'=>'guru@demo.test',
            'password'=>Hash::make('password'),'role'=>'teacher','status'=>'active'
        ]);
        $student = User::create([
            'name'=>'Siswa Satu','email'=>'siswa@demo.test',
            'password'=>Hash::make('password'),'role'=>'student','status'=>'active'
        ]);
    }
}

