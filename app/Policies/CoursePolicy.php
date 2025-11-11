<?php


namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Hanya guru pemilik course yang bisa mengedit/menghapus.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->teacher_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $user->id === $course->teacher_id;
    }
}
