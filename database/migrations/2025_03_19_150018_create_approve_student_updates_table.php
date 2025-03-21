<?php

declare(strict_types=1);

use App\Enums\StudentInfoUpdateStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approve_student_updates', function (Blueprint $table): void {
            $table->id();
            $table->string('approveable_type');
            $table->bigInteger('approveable_id');
            $table->string('status')->default(StudentInfoUpdateStatus::Pending->value);
            $table->unsignedBigInteger('student_info_updates_id')->nullable()->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approve_student_updates');
    }
};
