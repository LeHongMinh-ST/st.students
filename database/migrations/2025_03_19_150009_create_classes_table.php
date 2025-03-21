<?php

declare(strict_types=1);

use App\Enums\ClassType;
use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->text('description');
            $table->string('status')->default(Status::Active->value);
            $table->string('type')->default(ClassType::Basic->value);
            $table->unsignedBigInteger('marjor_id')->nullable()->index();
            $table->unsignedBigInteger('faculty_id')->index();
            $table->unsignedBigInteger('training_industry_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
