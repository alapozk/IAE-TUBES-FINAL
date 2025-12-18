<?php

namespace App\GraphQL\Resolvers;

use Illuminate\Support\Facades\Request;

abstract class BaseResolver
{
    protected function authorizeTeacher()
    {
        $token = Request::header('Authorization');

        if ($token !== 'Bearer ' . env('GRAPHQL_DEV_TOKEN')) {
            abort(401, 'Unauthorized');
        }
    }

    protected function authorizeStudent()
    {
        $token = Request::header('Authorization');

        if ($token !== 'Bearer ' . env('GRAPHQL_DEV_TOKEN')) {
            abort(401, 'Unauthorized');
        }
    }
}
