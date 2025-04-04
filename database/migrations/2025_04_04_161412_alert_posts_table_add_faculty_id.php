<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            if (!Schema::hasColumn('posts', 'faculty_id')) {
                $table->unsignedBigInteger('faculty_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {

            if (Schema::hasColumn('posts', 'faculty_id')) {
                $table->dropColumn('faculty_id');
            }
        });
    }
};
