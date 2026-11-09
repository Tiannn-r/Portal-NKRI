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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description', 500)->nullable();
            $table->string('icon')->nullable();
            $table->string('url', 2048);
            $table->foreignId('app_category_id')
                ->constrained('app_categories')
                ->restrictOnDelete();
            $table->foreignId('pic_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('open_in_new_tab')->default(true);
            $table->string('login_hint')->nullable();
            $table->timestamps();

            $table->index(['status', 'sort_order']);
            $table->index(['app_category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
