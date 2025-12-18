<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\AttendanceSession;

class AttendanceSessionResolver extends BaseResolver
{
    // Teacher: buat sesi absensi
    public function store($_, array $args)
    {
        $this->authorizeTeacher();

        return AttendanceSession::create([
            'course_id' => $args['course_id'],
            'title'     => $args['title'],
            'opened_at' => $args['opened_at'],
            'closed_at' => $args['closed_at'],
        ]);
    }

    // Teacher: list sesi absensi per course
    public function listByCourse($_, array $args)
    {
        $this->authorizeTeacher();

        return AttendanceSession::where('course_id', $args['course_id'])->get();
    }
}
