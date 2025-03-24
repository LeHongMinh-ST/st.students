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
            if (!Schema::hasColumn('students', 'full_name')) {
                $table->string('full_name')->nullable();
            }

            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable();
            }

            if (!Schema::hasColumn('students', 'code')) {
                $table->string('code')->unique();
            }

            if (!Schema::hasColumn('students', 'email')) {
                $table->string('email')->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            $table->dropColumn(['full_name', 'last_name', 'code', 'email']);
        });
    }
};
