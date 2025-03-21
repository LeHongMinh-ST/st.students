<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_updates_history', function (Blueprint $table): void {
            $table->id();
            $table->string('person_email');
            $table->string('gender')->default(Gender::Male->value);
            $table->string('permanent_residence');
            $table->date('dob');
            $table->string('pob');
            $table->string('address');
            $table->string('countryside');
            $table->string('training_type')->default(TrainingType::FormalUniversity->value);
            $table->string('phone');
            $table->string('nationality');
            $table->string('citizen_identification');
            $table->string('ethnic');
            $table->string('religion');
            $table->string('thumbnail')->nullable();
            $table->string('social_policy_object')->default(SocialPolicyObject::None->value);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('student_info_update_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_updates_history');
    }
};
