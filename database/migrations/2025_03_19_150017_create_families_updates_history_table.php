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
        Schema::create('families_updates_history', function (Blueprint $table): void {
            $table->id();
            $table->string('relationship')->nullable();
            $table->string('full_name');
            $table->string('job');
            $table->string('phone');
            $table->unsignedBigInteger('student_info_updates_history_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('families_updates_history');
    }
};
