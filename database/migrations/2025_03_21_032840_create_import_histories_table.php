<?php

declare(strict_types=1);

use App\Enums\StatusImport;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('import_histories', function (Blueprint $table): void {
            $table->id();
            $table->string('file_name');
            $table->string('status')->default(StatusImport::Pending->value);
            $table->integer('total_records')->default(0);
            $table->integer('successful_records')->default(0);
            $table->unsignedBigInteger('faculty_id');
            $table->string('type');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_histories');
    }
};
