<?php

declare(strict_types=1);

use App\Enums\RankGraduate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('graduation_ceremony_students', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('graduation_ceremony_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->float('gpa');
            $table->string('rank')->default(RankGraduate::Good->value);
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_ceremony_students');
    }
};
