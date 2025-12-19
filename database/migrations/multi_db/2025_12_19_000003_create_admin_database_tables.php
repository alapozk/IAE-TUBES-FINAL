<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for Admin Database
 * Tables: activity_logs, system_settings, audit_trails
 * Run with: php artisan migrate --database=admin
 */
return new class extends Migration
{
    protected $connection = 'admin';

    public function up(): void
    {
        // Activity Logs table
        Schema::connection($this->connection)->create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // login, logout, create, update, delete
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index(['model_type', 'model_id']);
            $table->index('action');
            $table->index('created_at');
        });

        // System Settings table
        Schema::connection($this->connection)->create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Audit Trails table (for compliance/security)
        Schema::connection($this->connection)->create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('action');
            $table->string('target_type'); // user, course, etc
            $table->unsignedBigInteger('target_id');
            $table->text('reason')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
            
            $table->index('admin_id');
            $table->index(['target_type', 'target_id']);
        });

        // Admin Notifications table
        Schema::connection($this->connection)->create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // warning, info, error
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('admin_notifications');
        Schema::connection($this->connection)->dropIfExists('audit_trails');
        Schema::connection($this->connection)->dropIfExists('system_settings');
        Schema::connection($this->connection)->dropIfExists('activity_logs');
    }
};
