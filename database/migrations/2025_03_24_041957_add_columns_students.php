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
            if (! Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->nullable();
            }

            if (! Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable();
            }

            if (! Schema::hasColumn('students', 'code')) {
                $table->string('code')->unique();
            }

            if (! Schema::hasColumn('students', 'email')) {
                $table->string('email')->unique();
            }

            if (Schema::hasColumn('students', 'admission_year')) {
                $table->dropColumn('admission_year')->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            if (Schema::hasColumn('students', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('students', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('students', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('students', 'email')) {
                $table->dropColumn('email');
            }

        });
    }
};
