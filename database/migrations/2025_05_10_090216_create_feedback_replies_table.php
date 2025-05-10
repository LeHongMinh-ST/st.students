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
        Schema::create('feedback_replies', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('feedback_id'); // ID của phản ánh
            $table->unsignedBigInteger('user_id'); // ID của người phản hồi (giáo viên hoặc cán bộ khoa)
            $table->text('content'); // Nội dung phản hồi
            $table->timestamps();
            $table->softDeletes(); // Thêm trường deleted_at để xóa mềm

            // Khóa ngoại
            $table->foreign('feedback_id')->references('id')->on('feedbacks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_replies');
    }
};
