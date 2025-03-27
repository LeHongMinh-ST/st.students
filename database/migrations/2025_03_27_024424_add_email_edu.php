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
        Schema::table('students', function (Blueprint $table): void {
            if (!Schema::hasColumn('students', 'email_edu')) {
                $table->string('email_edu')->nullable();
            }

            if (!Schema::hasColumn('students', 'code_import')) {
                $table->string('code_import')->nullable();
            }

            if (Schema::hasColumn('students', 'school_year')) {
                $table->dropColumn('school_year');
            }

            if (!Schema::hasColumn('students', 'school_year_start')) {
                $table->string('school_year_start')->nullable();
            }

            if (!Schema::hasColumn('students', 'school_year_end')) {
                $table->string('school_year_end')->nullable();
            }
        });

        Schema::table('class_students', function (Blueprint $table): void {
            if (Schema::hasColumn('class_students', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('class_students', 'end_date')) {
                $table->dropColumn('end_date');
            }

            if (!Schema::hasColumn('class_students', 'start_year')) {
                $table->string('start_year')->nullable();
            }

            if (!Schema::hasColumn('class_students', 'end_year')) {
                $table->string('end_year')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            if (Schema::hasColumn('students', 'email_edu')) {
                $table->dropColumn('email_edu');
            }

            if (Schema::hasColumn('students', 'code_import')) {
                $table->dropColumn('code_import');
            }

            if (Schema::hasColumn('students', 'school_year_start')) {
                $table->dropColumn('school_year_start');
            }

            if (Schema::hasColumn('students', 'school_year_end')) {
                $table->dropColumn('school_year_end');
            }
        });

        Schema::table('class_students', function (Blueprint $table): void {
            if (!Schema::hasColumn('class_students', 'start_date')) {
                $table->date('start_date')->nullable();
            }

            if (!Schema::hasColumn('class_students', 'end_date')) {
                $table->date('end_date')->nullable();
            }

            if (Schema::hasColumn('class_students', 'start_year')) {
                $table->dropColumn('start_year');
            }

            if (Schema::hasColumn('class_students', 'end_year')) {
                $table->dropColumn('end_year');
            }
        });
    }
};
