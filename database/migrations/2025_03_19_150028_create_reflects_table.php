<?php

declare(strict_types=1);

use App\Enums\ReflectStatus;
use App\Enums\ReflectSubject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reflects', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('title');
            $table->string('subject')->default(ReflectSubject::Other->value);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('status')->default(ReflectStatus::Pending->value);
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflects');
    }
};
