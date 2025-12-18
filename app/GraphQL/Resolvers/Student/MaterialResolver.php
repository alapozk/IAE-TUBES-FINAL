<?php

namespace App\GraphQL\Resolvers\Student;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\Material;

class MaterialResolver extends BaseResolver
{
    // Student: lihat material per course
    public function listByCourse($_, array $args)
    {
        $this->authorizeStudent();

        return Material::where('course_id', $args['course_id'])->get();
    }
}
