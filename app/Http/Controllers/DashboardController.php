<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Course;

class DashboardController extends Controller {
  public function index(Request $r) {
    $role = $r->user()->role;
    return match ($role) {
      'admin'   => view('dashboard.admin'),
      'teacher' => view('dashboard.teacher'),
      'student' => view('dashboard.student'),
      default   => abort(403),
    };
  }
}
