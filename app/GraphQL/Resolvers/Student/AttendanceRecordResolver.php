<?php

namespace App\GraphQL\Resolvers\Student;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use Carbon\Carbon;

class AttendanceRecordResolver extends BaseResolver
{
    // Student: absen ke sesi
    public function checkIn($_, array $args)
    {
        $this->authorizeStudent();

        $session = AttendanceSession::findOrFail($args['attendance_session_id']);

        $now = Carbon::now();

        // Validasi waktu
        if ($now->lt($session->opened_at) || $now->gt($session->closed_at)) {
            abort(422, 'Attendance session is closed');
        }

        // Cegah absen ganda
        return AttendanceRecord::firstOrCreate(
            [
                'attendance_session_id' => $session->id,
                'user_id'               => $args['user_id'],
            ],
            [
                'checked_in_at' => $now,
            ]
        );
    }
}
