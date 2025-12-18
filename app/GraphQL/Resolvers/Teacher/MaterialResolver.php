<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\Material;

class MaterialResolver extends BaseResolver
{
    // Teacher: buat material
    public function store($_, array $args)
    {
        $this->authorizeTeacher();

        return Material::create([
            'course_id' => $args['course_id'],
            'title'     => $args['title'],
            'content'   => $args['content'],
        ]);
    }

    // Teacher: list material per course
    public function listByCourse($_, array $args)
    {
        $this->authorizeTeacher();

        return Material::where('course_id', $args['course_id'])->get();
    }
}
