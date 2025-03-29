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
            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->nullable();
            }
            if (!Schema::hasColumn('students', 'email_edu')) {
                $table->string('email_edu')->nullable();
            }
            if (!Schema::hasColumn('students', 'code')) {
                $table->string('code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            if (Schema::hasColumn('students', 'first_name')) {
                $table->dropColumn('first_name');
            }

            if (Schema::hasColumn('students', 'last_name')) {
                $table->dropColumn('last_name');
            }


            if (Schema::hasColumn('students', 'email_edu')) {
                $table->dropColumn('email_edu');
            }

            if (Schema::hasColumn('students', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
