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
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            // Change school_year column from bigInteger to string
            $table->string('school_year')->change();

            // Also change certification column from bigInteger to string if needed
            $table->string('certification')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduation_ceremonies', function (Blueprint $table): void {
            // Revert changes if needed
            $table->bigInteger('school_year')->change();
            $table->bigInteger('certification')->change();
        });
    }
};
