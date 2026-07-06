<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the database table.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key ID
            $table->string('title'); // The name/title of the task
            $table->boolean('is_completed')->default(false); // Task status flag
            $table->timestamps(); // Automatically adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations (drop the table if needed).
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
