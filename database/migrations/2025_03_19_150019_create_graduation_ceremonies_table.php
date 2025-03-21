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
        Schema::create('graduation_ceremonies', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->bigInteger('school_year');
            $table->bigInteger('certification');
            $table->string('certification_date');
            $table->unsignedBigInteger('faculty_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_ceremonies');
    }
};
