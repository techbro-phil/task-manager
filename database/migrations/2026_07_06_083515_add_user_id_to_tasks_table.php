<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add the user_id column.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Adds a foreign key column linked to the ID column on the users table.
            // cascadeOnDelete ensures that if a user deletes their account, their tasks are wiped too.
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations (drop the column if rolled back).
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
