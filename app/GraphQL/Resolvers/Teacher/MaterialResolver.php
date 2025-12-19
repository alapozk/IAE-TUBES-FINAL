<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialResolver
{
    /**
     * List materials by course
     */
    public function listByCourse($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        
        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $course->materials()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Show single material
     */
    public function show($_, array $args)
    {
        $material = Material::with('course')->findOrFail($args['id']);
        
        if ($material->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $material;
    }

    /**
     * Create material with file upload
     */
    public function store($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        
        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $file = $args['file'];
        $path = $file->store("materials/{$course->id}", 'public');

        return Material::create([
            'course_id' => $course->id,
            'title' => $args['title'],
            'description' => $args['description'] ?? null,
            'file_path' => $path,
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => strtolower($file->getClientOriginalExtension()),
        ]);
    }

    /**
     * Update material
     */
    public function update($_, array $args)
    {
        $material = Material::with('course')->findOrFail($args['id']);
        
        if ($material->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $update = [
            'title' => $args['title'],
            'description' => $args['description'] ?? $material->description,
        ];

        if (isset($args['file']) && $args['file']) {
            // Delete old file
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $args['file'];
            $path = $file->store("materials/{$material->course_id}", 'public');

            $update['file_path'] = $path;
            $update['mime'] = $file->getMimeType();
            $update['size'] = $file->getSize();
            $update['extension'] = strtolower($file->getClientOriginalExtension());
        }

        $material->update($update);
        return $material->fresh();
    }

    /**
     * Delete material
     */
    public function delete($_, array $args)
    {
        $material = Material::with('course')->findOrFail($args['id']);
        
        if ($material->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        return true;
    }
}
