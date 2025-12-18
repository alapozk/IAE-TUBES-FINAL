<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class QuizResolver
{
    public function show($_, array $args)
    {
        $this->authorize();

        return Quiz::findOrFail($args['quiz_id']);
    }


    public function store($_, array $args)
    {
        return Quiz::create([
            'course_id'   => $args['course_id'],
            'title'       => $args['title'],
            'max_attempt' => $args['max_attempt'],
            'duration'    => $args['duration'] ?? null,
            'deadline'    => $args['deadline'] ?? null,
            'show_review' => $args['show_review'],
            'is_published'=> false,
        ]);
    }

    public function update($_, array $args)
    {
        $quiz = Quiz::findOrFail($args['quiz_id']);

        $quiz->update([
            'title'       => $args['title'],
            'max_attempt' => $args['max_attempt'],
            'duration'    => $args['duration'] ?? null,
            'deadline'    => $args['deadline'] ?? null,
            'show_review' => $args['show_review'],
        ]);

        return $quiz;
    }

    public function togglePublish($_, array $args)
    {
        $quiz = Quiz::findOrFail($args['quiz_id']);
        $quiz->update([
            'is_published' => !$quiz->is_published
        ]);

        return $quiz;
    }

    private function authorize()
    {
        $token = Request::header('Authorization');

        if ($token !== 'Bearer ' . env('GRAPHQL_DEV_TOKEN')) {
            abort(401, 'Unauthorized');
        }
    }

    

}
