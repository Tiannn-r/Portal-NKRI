<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pivot Aplikasi dan Role (Spatie)
        Schema::create('application_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();
            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['application_id', 'role_id']);
        });

        // Pivot Aplikasi dan Departemen
        Schema::create('application_department', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['application_id', 'department_id']);
        });

        // Pivot Pengecualian Akses Perorangan (type: allow | deny)
        Schema::create('application_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                ->constrained('applications')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->enum('type', ['allow', 'deny'])->default('allow');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'user_id']);
            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_user');
        Schema::dropIfExists('application_department');
        Schema::dropIfExists('application_role');
    }
};
