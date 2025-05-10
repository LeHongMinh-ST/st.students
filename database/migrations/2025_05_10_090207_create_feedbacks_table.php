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
        Schema::create('feedbacks', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('student_id'); // ID của sinh viên lớp trưởng tạo phản ánh
            $table->unsignedBigInteger('class_id'); // ID của lớp học
            $table->string('title'); // Tiêu đề phản ánh
            $table->text('content'); // Nội dung phản ánh
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected'])->default('pending'); // Trạng thái phản ánh
            $table->unsignedBigInteger('faculty_id'); // ID của khoa
            $table->timestamps();
            $table->softDeletes(); // Thêm trường deleted_at để xóa mềm

            // Khóa ngoại
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
