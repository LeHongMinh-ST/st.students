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
        Schema::table('semesters', function (Blueprint $table): void {
            if (Schema::hasColumn('semesters', 'start_year')) {
                $table->dropColumn('start_year');
            }
            if (Schema::hasColumn('semesters', 'end_year')) {
                $table->dropColumn('end_year');
            }
            if (!Schema::hasColumn('semesters', 'school_year_id')) {
                $table->unsignedBigInteger('school_year_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table): void {
            if (!Schema::hasColumn('semesters', 'start_year')) {
                $table->string('start_year')->nullable();
            }
            if (!Schema::hasColumn('semesters', 'end_year')) {
                $table->string('end_year')->nullable();
            }
            if (Schema::hasColumn('semesters', 'school_year_id')) {
                $table->dropColumn('school_year_id');
            }
        });
    }
};
