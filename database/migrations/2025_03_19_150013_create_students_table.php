<?php

declare(strict_types=1);

use App\Enums\Gender;
use App\Enums\SocialPolicyObject;
use App\Enums\StudentStatus;
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
        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->string('status')->default(StudentStatus::CurrentlyStudying->value);
            $table->string('school_year')->nullable();
            $table->integer('admission_year')->nullable();
            $table->string('person_email')->nullable();
            $table->string('gender')->default(Gender::Male->value);
            $table->string('permanent_residence')->nullable();
            $table->date('dob')->nullable();
            $table->string('pob')->nullable();
            $table->string('address')->nullable();
            $table->string('countryside')->nullable();
            $table->string('training_type')->default(TrainingType::FormalUniversity->value);
            $table->string('phone')->nullable();
            $table->string('nationality')->nullable();
            $table->string('citizen_identification')->nullable();
            $table->string('ethnic')->nullable();
            $table->string('religion')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('social_policy_object')->default(SocialPolicyObject::None->value);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
