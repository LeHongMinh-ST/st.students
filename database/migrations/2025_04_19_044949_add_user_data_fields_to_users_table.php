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
        Schema::table('users', function (Blueprint $table): void {
            $table->text('access_token')->nullable()->after('sso_id');
            $table->json('user_data')->nullable()->after('access_token');
            $table->unsignedBigInteger('faculty_id')->nullable()->after('user_data');
            $table->string('role')->nullable()->after('faculty_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['access_token', 'user_data', 'faculty_id', 'role']);
        });
    }
};
