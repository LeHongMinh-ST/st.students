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
        Schema::table('roles', function (Blueprint $table): void {
            if (!Schema::hasColumn('roles', 'faculty_id')) {
                $table->unsignedBigInteger('faculty_id')->nullable();
            }

            if (!Schema::hasColumn('roles', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            if (Schema::hasColumn('roles', 'faculty_id')) {
                $table->dropColumn('faculty_id');
            }

            if (Schema::hasColumn('roles', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
