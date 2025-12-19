<?php

namespace App\GraphQL\Resolvers\Admin;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsResolver
{
    /**
     * Get admin dashboard stats
     */
    public function get($_, array $args)
    {
        // Verify admin role
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            throw new \Exception('Unauthorized. Admin access required.');
        }

        try {
            // Use direct DB queries to handle multi-database
            $totalCourses = DB::connection('guru')->table('courses')->count();
            $activeCourses = DB::connection('guru')->table('courses')
                ->whereIn('status', ['active', 'published'])->count();
            $totalEnrollments = DB::connection('siswa')->table('enrollments')->count();
            
            // Users from main database
            $totalTeachers = User::where('role', 'teacher')->count();
            $totalStudents = User::where('role', 'student')->count();

            return [
                'totalCourses' => $totalCourses,
                'totalTeachers' => $totalTeachers,
                'totalStudents' => $totalStudents,
                'totalEnrollments' => $totalEnrollments,
                'activeCourses' => $activeCourses,
            ];
        } catch (\Exception $e) {
            // Fallback for when tables don't exist yet
            return [
                'totalCourses' => 0,
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalStudents' => User::where('role', 'student')->count(),
                'totalEnrollments' => 0,
                'activeCourses' => 0,
            ];
        }
    }
}
