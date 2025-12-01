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
        Schema::table('graduation_ceremony_students', function (Blueprint $table): void {
            $table->string('industry_code')->nullable()->after('email');
            $table->string('industry_name')->nullable()->after('industry_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduation_ceremony_students_tables', function (Blueprint $table): void {
            $table->dropColumn('industry_code');
            $table->dropColumn('industry_name');
        });
    }
};
