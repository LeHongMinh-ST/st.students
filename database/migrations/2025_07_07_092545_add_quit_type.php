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
        Schema::table('student_quits', function (Blueprint $table): void {
            if (!Schema::hasColumn('student_quits', 'quit_type')) {
                $table->string('quit_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_quits', function (Blueprint $table): void {
            if (Schema::hasColumn('student_quits', 'quit_type')) {
                $table->dropColumn('quit_type');
            }
        });
    }
};
