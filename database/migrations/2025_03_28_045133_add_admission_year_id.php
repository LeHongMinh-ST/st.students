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
        Schema::table('classes', function (Blueprint $table): void {
            if (!Schema::hasColumn('classes', 'admission_year_id')) {
                $table->unsignedBigInteger('admission_year_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table): void {
            if (Schema::hasColumn('classes', 'admission_year_id')) {
                $table->dropColumn('admission_year_id');
            }

        });
    }
};
