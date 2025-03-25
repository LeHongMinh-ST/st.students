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
        Schema::table('import_histories', function (Blueprint $table): void {
            if (!Schema::hasColumn('import_histories', 'path')) {
                $table->string('path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_histories', function (Blueprint $table): void {
            if (Schema::hasColumn('import_histories', 'path')) {
                $table->dropColumn('path');
            }

        });
    }
};
