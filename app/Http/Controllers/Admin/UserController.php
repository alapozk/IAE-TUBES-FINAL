<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller {
    public function __invoke() {
        $teachers = User::where('role','teacher')->where('status','active')->get();
        return view('admin.users', ['users'=>$teachers]);
    }
}
