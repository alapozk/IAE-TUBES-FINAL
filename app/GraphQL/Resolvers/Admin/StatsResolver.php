<?php

namespace App\GraphQL\Resolvers\Admin;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StatsResolver
{
    /**
     * Get admin dashboard stats
     */
    public function get($_, array $args)
    {
        // Verify admin role
        if (Auth::user()->role !== 'admin') {
            throw new \Exception('Unauthorized. Admin access required.');
        }

        return [
            'totalCourses' => Course::count(),
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalStudents' => User::where('role', 'student')->count(),
            'totalEnrollments' => Enrollment::count(),
            'activeCourses' => Course::where('status', 'active')->count(),
        ];
    }
}
