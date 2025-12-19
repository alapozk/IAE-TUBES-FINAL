<?php

namespace App\GraphQL\Resolvers\Teacher;

use Illuminate\Support\Facades\DB;

class AssignmentFieldResolver
{
    /**
     * Get total submissions count for this assignment
     */
    public function submissionsCount($assignment, array $args)
    {
        return DB::connection('siswa')
            ->table('submissions')
            ->where('assignment_id', $assignment->id)
            ->count();
    }
}
