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
        Schema::create('habits', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name', 255);
            $table->tinyText('description')->nullable();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('category_id')->constrained('habit_categories', 'id');
            $table->enum('entry_type', ['number', 'boolean']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habits');
    }
};
