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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing column if it exists
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            // Add the new enum column
            $table->enum('role', ['user', 'admin', 'moderator'])->default('user')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the enum column
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            // Revert to a string column (assuming it was a string before)
            $table->string('role')->default('user')->after('email'); // Adjust default if necessary
        });
    }
};
