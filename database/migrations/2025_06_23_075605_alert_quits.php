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
        Schema::table('quits', function (Blueprint $table): void {
            $table->string('decision_number')->nullable();
            $table->date('decision_date')->nullable();
            $table->string('school_year')->nullable();
            $table->string('type')->nullable();
            $table->dropColumn('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quits', function (Blueprint $table): void {
            $table->dropColumn('decision_number');
            $table->dropColumn('decision_date');
            $table->dropColumn('school_year');
            $table->dropColumn('type');
            $table->integer('semester_id')->nullable();
        });
    }
};
