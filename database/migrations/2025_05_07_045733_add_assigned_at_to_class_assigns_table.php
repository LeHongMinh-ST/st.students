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
        Schema::table('class_assigns', function (Blueprint $table): void {
            if (!Schema::hasColumn('class_assigns', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_assigns', function (Blueprint $table): void {
            if (Schema::hasColumn('class_assigns', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
        });
    }
};
